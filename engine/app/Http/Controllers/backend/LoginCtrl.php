<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Users\User;
use App\Http\Requests\backend\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\AuthenticateUser;
use Request,
    Auth,
    Exception,
    Entrust,
    Socialize,
    Mail;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Contracts\Auth\PasswordBroker;

class LoginCtrl extends Controller {

    use ResetsPasswords;

    protected $redirectTo = '/customer/login';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct(PasswordBroker $passwords) {
        $this->passwords = $passwords;
        parent::__construct();
        if (Auth::check()) {
            if (Entrust::can(['backend'])) {
                return redirect()->route('backend.dashboard');
            }
            return redirect('');
        }
    }

    public function getEmail() {
        return view('auth.password', $this->data);
    }

    public function getReset($token = null) {
        if (is_null($token)) {
            throw new NotFoundHttpException;
        }

        return view('auth.reset', $this->data)->with('token', $token);
    }

    public function index() {
//
        return view('backend.login');
    }

    public function postRegister(RegisterRequest $request) {
        $input = $request->all();
        $input['status'] = 0;
        $input['password'] = bcrypt($input['password']);
        $input['activation_code'] = str_random(60) . $input['email'];
        $user = new User($input);
        if ($user->save()) {
            $data = array(
                'name' => $user->name,
                'code' => $input['activation_code'],
            );
            Mail::queue('emails.hello', $data, function($message) use ($user) {
                $message->from('no-reply@lara.shop', 'No Reply');
                $message->to($user->email, 'Please activate your account.');
            });
            $user->attachRole(5);
            return redirect()->route('register.success');
        }
    }
    public function updateaccount() {
        $input = Request::except('_token');
        $id = $input['id'];
        $datauser = User::findOrFail($id);
        $datauser->name = $input['name'];
        $datauser->email = $input['email'];
        if ($input['password']!=NULL||$input['password']!='') {
            # code...
            $datauser->password = bcrypt($input['password']);
        }
        $datauser->address = $input['address'];
        $datauser->province = $input['province'];
        $datauser->phone = $input['phone'];
        $datauser->postal_code = $input['postal_code'];
        $datauser->city = $input['city'];
        if ($datauser->update()) {
            return redirect()->back();
        }
    }

    public function doLogin(LoginRequest $request) {
        $message = '';
        $email = $request->get('email');
        $password = $request->get('password');
        $remember = $request->get('remember');
        $check = User::where('email', '=', $email)->get();
        try {
            if (!count($check) > 0) {
                throw new Exception("Email Tidak Terdaftar");
            }
            if (!Auth::validate(['email' => $email, 'password' => $password, 'status' => 1])) {
                throw new Exception("Email atau Password Salah");
            } elseif ($remember) {
                if (Auth::attempt(['email' => $email, 'password' => $password, 'status' => 1, $remember])) {
                    if (Entrust::can(['backend'], true)) {
                        return redirect('backend/dashboard');
                    }
                    return redirect('customer/account');
                }
            } else {
                if (Auth::attempt(['email' => $email, 'password' => $password, 'status' => 1])) {
                    if (Entrust::can(['backend'], true)) {
                        return redirect('backend/dashboard');
                    }
                    return redirect('customer/account');
                }
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        if ($request->has('page')) {
            return redirect('customer/login')->withInput()->withErrors(['message' => $message]);
        }
        return redirect('login')->withInput()->withErrors(['message' => $message]);
    }

    public function redirect($provider) {
        return Socialize::with($provider)->redirect();
    }

// to get authenticate user data
    public function account($provider) {
        $user = Socialize::with($provider)->user();
// Do your stuff with user data.
        print_r($user);
        die;
    }

    public function auth(AuthenticateUser $authenticateUser, $provider = null) {
        return $authenticateUser->execute(Request::all(), $this, $provider);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
}
