<?php


namespace App\Http\Controllers\Front;

use Request;
use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\Page\Page;
use App\Models\Page\Contact;
use App\Models\Widget\Provinces;
use App\Models\Widget\Regencies;
use App\Models\Widget\Slideshow;
use App\Models\Users\User;
use Illuminate\Support\Str;
use App\Http\Requests\ShippingRequest;
use Session,
    Auth;
use Veritrans_Config;
use Veritrans_VtWeb;
use Veritrans_Transaction;
use Exception,DB;

class PageCtrl extends Controller {

    public function __construct() {
        
        parent::__construct();
        // Set your Merchant Server Key
Veritrans_Config::$serverKey = 'SB-Mid-server-GMQ-2j3BY5j0fQc1Mm1zivGM';
// Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
Veritrans_Config::$isProduction = false;
// Set sanitization on (default)
Veritrans_Config::$isSanitized = true;
// Set 3DS transaction for credit card to true
Veritrans_Config::$is3ds = true;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        //
        $tampilproduct = DB::table('product')
                ->leftjoin('product_img', 'product.id', '=', 'id_product')
                ->orderBy('product.terjual','DESC')
                ->limit(8)
                ->get();

        $tampilproduct1 = DB::table('product')
                ->leftjoin('product_img', 'product.id', '=', 'id_product')
                ->orderBy('product.created_at','DESC')
                ->limit(8)
                ->get();
        return view('front.eshopper.pages.home', $this->data)
                ->with('tampilproduct',$tampilproduct)
                ->with('tampilproduct1',$tampilproduct1);
    }

    public function registerSuccess() {
        return view('front.eshopper.pages.postRegister', $this->data);
    }

    public function activateAccount($code, User $user) {
        if ($user->accountIsActive($code)) {
            return view('front.eshopper.pages.activated', $this->data);
        }
    }

    public function show($slug) {
        //
        $findcat = Products\Category::with('product')->where('slug', $slug)->first();
        if (count($findcat) == 0) {
            $findpro = Products\Product::where('slug', $slug)->first();
            $findcate = DB::table('product')->leftjoin('product_category','product_category.id','=','id_category')->where('product.slug', $slug)->first();

            $this->data['product'] = $findpro;
            $this->data['showcat'] = $findcate;
            $this->data['related_product'] = Products\Product::leftjoin('product_img', 'product.id', '=', 'id_product')->where('id_category', $findpro->id_category)->where('product.id', '!=', $findpro->id)->get();
            if (count($findpro) > 0) {
                return view('front.eshopper.pages.product', $this->data);
            }
        } else {
            $this->data['products'] = $findcat->product()->paginate(8);
            return view('front.eshopper.pages.category', $this->data);
        }
        return abort('404', 'Page Not Found');
    }

    public function shop() {
        $tampilproduct = DB::table('product')
                ->leftjoin('product_img', 'product.id', '=', 'id_product')
                ->paginate(8);
        
        $tampilproduct->setPath('product');

        return view('front.eshopper.pages.shop', $this->data)
                ->with('tampilproduct', $tampilproduct);
    }

    public function showpage($slug) {
        //
        $this->data['page'] = Page\Page::where('page_slug', $slug)->first();
        if (count($this->data['page'])) {
            return view('front.eshopper.pages.pages', $this->data);
        }
        return abort('404', 'Page Not Found');
    }

    public function postContact() {
        $input = Request::except('_token');
        $contact = new Contact;
        $contact->contact_us_name = $input['contact_us_name'];
        $contact->contact_us_email = $input['contact_us_email'];
        $contact->contact_us_subject = $input['contact_us_subject'];
        $contact->contact_us_message = $input['contact_us_message'];
        if ($contact->save()) {
            return redirect()->back();
        }
    }

    public function checkout() {
//        dd(Request::all());
        $this->data['ship_method'] = shipOption('shipping_method');
        return view('front.eshopper.pages.cart', $this->data);
    }

    public function postcheckout() {
        if (Request::method() == 'POST') {
            if (Request::get('itemCount') == 0) {
                return redirect()->back()->withErrors(['message' => 'Keranjang Belanja Kosong']);
            }
            if (Auth::check()) {
                return redirect()->to('checkout/shipping')->with(['data' => Request::all()]);
            }
        }
        return view('front.eshopper.pages.checkout', $this->data);
    }

    public function shipping() {
        $data = Session::get('data');
        if (empty($data)) {
            if (Session::has('order')) {
                $this->data['order'] = Session::get('order');
            } else {
                return redirect('checkout');
            }
        } else {
            $this->data['order'] = $this->setOrder($data);
            Session::put(['order' => $this->setOrder($data)]);
        }
        $this->data['user'] = Auth::user();

        $tampilprovinsi = DB::table('provinces')
                ->get();

                
        return view('front.eshopper.pages.shipping', $this->data)
                ->with('tampilprovinsi', $tampilprovinsi);
    }

    private function setOrder($data) {
        $order = [];
        $total = 0;

        for ($i = 1; $i <= $data['itemCount']; $i++) {
            $order['shipping'] = [
                'service' => isset($data['service']) ? $data['service'] : '',
                'city' => isset($data['city']) ? $data['city'] : Auth::user()->city,
                'province' => isset($data['province']) ? $data['city'] : Auth::user()->province,
                'fee' => $data['shipping']
            ];
            $product_id = explode(',', str_replace(' ', '', $data['item_options_' . $i]));
            $pro_id = preg_replace("/[^0-9,.]/", "", $product_id[0]);
            unset($product_id[0]);
            $options = implode(',', $product_id);
            $order['product'][] = [
                'id' => $pro_id,
                'product_img' => Products\Product::find($pro_id)->image->first() ? Products\Product::find($pro_id)->image->first()->path_thumb : '',
                'product_name' => $data['item_name_' . $i],
                'product_qty' => $data['item_quantity_' . $i],
                'product_price' => $data['item_price_' . $i],
                'product_options' => $options,
                'product_tprice' => $data['item_quantity_' . $i] * $data['item_price_' . $i]
            ];
            $total += $data['item_quantity_' . $i] * $data['item_price_' . $i];
            $order['sub_total'] = $total;
        }
        $order['total'] = $order['sub_total'] + $order['shipping']['fee'];
        return $order;
    }

    public function postShipping(ShippingRequest $request) {
        $input = $request->all();
        $user = User::find($input['user_id']);
        $order = Session::get('order');
        $order['user'] = $request->except('_token', 'payment_id');
        Session::put(['order' => $order]);
        if ($user->update($input)) {
            return response()->json(['success' => TRUE]);
        }
    }

    public function getReviewPayment() {
        $tampilcity = DB::table('regencies')
                ->leftjoin('provinces', 'province_id', '=', 'id_provinces')
                ->where('id_regencies', Auth::user()->city)
                ->get();
        $this->data['order'] = Session::get('order');
        //$this->data['pay'] = $this->data['order'];
        return view('front.eshopper.pages.review-payment', $this->data)
                ->with('tampilcity', $tampilcity);
    }

    public function postOrder() {
        date_default_timezone_set('Asia/jakarta');
        $tgl=date('Y-m-j');
        $bulan=date('Y-m');
        $bulantext=date('F');
        $orders = Session::get('order');
        $order = new Order();
        $order->user_id = $orders['user']['user_id'];
        $order->order_id = Order::max('order_id')+1;
        $order->total_price = $orders['total'];
        $order->shipping_type = $orders['shipping']['service'];
        $order->shipping_to = $orders['shipping']['city'];
        $order->shipping_fee = $orders['shipping']['fee'];
        $order->payment_id = 2;
        $order->order_status = 'Pending';
        $order->comments = $orders['user']['comments'];
        $order->date = $tgl;
        $order->month = $bulan;
        $order->month_text = $bulantext;
        $orid = Order::max('id')+1;
        
        $order->save();
        foreach ($orders['product'] as $product) {
            $orderprod = new OrderProduct();
            $orderprod->order_id = $orid;
            $orderprod->product_id = $product['id'];
            $orderprod->quantity = $product['product_qty'];
            $orderprod->ukuran = $product['product_options'];
            $orderprod->save();

            //$order->orders()->attach($product['id'], ['quantity' => $product['product_qty']], ['ukuran' => $product['product_options']]);
        }
        //return response()->json(['success' => TRUE]);
        return view('front.eshopper.pages.checkout-success', $this->data);
    }

    public function testorder() {
        //$this->data['order'] = Session::get('order');
        //$this->data['pay'] = $this->data['order'];
        return view('front.eshopper.pages.review-payment', $this->data);
    }

    public function getAccount() {
        if (!Auth::check()) {
            return redirect('customer/login');
        }

        $tampilorder = DB::table('order')
                ->select('order.id','order_id','date','order_status','total_price')
                ->leftjoin('users', 'users.id', '=', 'user_id')
                ->leftjoin('option_payment', 'option_payment.id', '=', 'payment_id')
                ->where('user_id', Auth::user()->id)
                ->get();

        $tampilprovinsi = DB::table('provinces')
                ->get();

        return view('front.eshopper.pages.account', $this->data)
                ->with('tampilorder', $tampilorder)
                ->with('tampilprovinsi', $tampilprovinsi);
    }

    public function getContact() {

        return view('front.eshopper.pages.contact', $this->data);
    }

    public function getKota($id) {

        $cities = DB::table("regencies")
                    ->where("province_id",$id)
                    ->get();

        $return = '<option value="">Pilih Kabupaten/Kota</option>';
        # lakukan perulangan untuk tabel kabupaten lalu kirim
        if (!Auth::check()) {
            foreach($cities as $temp) 
            # isi nilai return
                $return .= "<option value='$temp->id_regencies'>$temp->name_regencies</option>";
        } else {
            foreach($cities as $temp) 
            # isi nilai return
                if (Auth::user()->city==$temp->id_regencies) {
                    # code...
                    $return .= "<option value='$temp->id_regencies' selected >$temp->name_regencies</option>";
                } else {
                    # code...
                    $return .= "<option value='$temp->id_regencies'>$temp->name_regencies</option>";
                }
                
                
        }
        
        # kirim
        return $return;

    }

    public function getOrder($id) {
        if (!Auth::check()) {
            return redirect('customer/login');
        }

        $tampilorder = DB::table('order')
                ->leftjoin('users', 'users.id', '=', 'user_id')
                ->leftjoin('option_payment', 'option_payment.id', '=', 'payment_id')
                ->leftjoin('regencies', 'id_regencies', '=', 'city')
                ->leftjoin('provinces', 'province_id', '=', 'id_provinces')
                ->where('order.id', $id)
                ->first();

        $tampilitem = DB::table('order')
                ->leftjoin('order_product', 'order_product.order_id', '=', 'id')
                ->leftjoin('product', 'product_id', '=', 'product.id')
                ->where('order_product.order_id', $id)
                ->get();
                
        return view('front.eshopper.pages.view-order', $this->data)
                ->with('tampilitem', $tampilitem)
                ->with('tampilorder', $tampilorder);
    }

    public function getPaymentDescription($id) {
        $payment = \App\Models\Options\Payments::find($id);
        if ($payment) {
            return $payment->payment_description;
        }
    }

    public function getOrderComplete() {
        try {
            $status = Veritrans_Transaction::status(Request::get('order_id'));
            $order = Order::where('order_id', $status->order_id)->first();
            $order->payment_id = \App\Models\Options\Payments::where('payment_type', $status->payment_type)->first()->id;
            $order->order_status = $status->transaction_status;
            $order->save();
        } catch (Exception $exc) {
            Session::flash('error', $exc->getMessage());
        }
        if (!Session::has('error')) {
            Session::forget('order');
        }
        return view('front.eshopper.pages.checkout-success', $this->data);
    }

    private function renderPaymentLink($array = []) {

        $order = $array;
        $transaction_details = array(
            'order_id' => Order::max('order_id') + 1,
            'gross_amount' => $order['total'], // no decimal allowed for creditcard
        );
        $i = 0;
        foreach ($order['product'] as $product) {
            $items_details = [
                $i => [
                    'id' => $product['id'],
                    'price' => $product['product_price'],
                    'quantity' => $product['product_qty'],
                    'name' => $product['product_name']
                ]
            ];
            $i++;
        }
        $shipping_fee = [
            'id' => 'shipping',
            'price' => $order['shipping']['fee'],
            'quantity' => '1',
            'name' => $order['shipping']['service']
        ];
        array_push($items_details, $shipping_fee);
// Optional
        $billing_address = array(
            'first_name' => $order['user']['first_name'],
            'last_name' => $order['user']['last_name'],
            'address' => $order['user']['address'],
            'city' => getCity($order['user']['city']),
            'postal_code' => "55281",
            'phone' => $order['user']['mob_phone'],
            'country_code' => 'IDN'
        );

// Optional
        $customer_details = array(
            'first_name' => $order['user']['first_name'],
            'last_name' => $order['user']['last_name'],
            'email' => $order['user']['email'],
            'phone' => $order['user']['mob_phone'],
            'billing_address' => $billing_address,
            'shipping_address' => $billing_address
        );

// Fill transaction details
        $transaction = array(
            'payment_type' => 'vtweb',
            "vtweb" => array(
                "enabled_payments" => getPayment(),
                "credit_card_3d_secure" => true,
                //Set Redirection URL Manually
                "finish_redirect_url" => url('checkout/complete'),
                "unfinish_redirect_url" => url('checkout/uncomplete'),
                "error_redirect_url" => url('checkout/error'),
            ),
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $items_details,
        );
        $vtweb_url = Veritrans_VtWeb::getRedirectionUrl($transaction);
        return $vtweb_url;
    }
    public function updateaccount(UserRequest $request, $id) {
        echo "updateaccount";
        /*
        $input = $request->all();
        $input['status'] = $request->get('status') == 'on' ? 1 : 0;
        if ($request->has('password')) {
            $input['password'] = bcrypt($request->get('password'));
        }
        $user = User::find($id);
        if ($user->update($input)) {
            $user->roles()->sync([]);
            $user->attachRole($request->get('role'));
            return redirect()->route('backend.user.index');
        }
        */
    }

}
