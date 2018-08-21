<?php

namespace App\Http\Controllers\backend\Order;
use Illuminate\Http\Request;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\Users\User;
use App\Models\Options\Payments;
use App\Http\Controllers\Controller;
use Storage,Image,Entrust,DB;

class OrderCtrl extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct() {
        $this->data['title'] = 'Order';
        $this->data['sub_title'] = 'List Order';
    }

    public function index() {
        $tampilorder = DB::table('order')
                ->select('order.id','order_id','name','address','name_regencies','name_provinces','order.created_at','date','order_status','total_price')
                ->leftjoin('users', 'users.id', '=', 'user_id')
                ->leftjoin('option_payment', 'option_payment.id', '=', 'payment_id')
                ->leftjoin('regencies', 'id_regencies', '=', 'city')
                ->leftjoin('provinces', 'province_id', '=', 'id_provinces')
                ->get();
        /*
        $tampilorder =  Order::all();

        if (!Entrust::can('category-read')) {
            return redirect('');
        }
        $this->data['sub_title'] = 'List Category';
        $this->data['id'] = 0;
        return view('backend.barcode.index', $this->data)
            ->with('tampilbarcode', $tampilbarcode);
        */
        //
        return view('backend.order.index', $this->data)
                    ->with('tampilorder', $tampilorder);
        //$this->data['title'] = 'Barcode';
        //$this->data['sub_title'] = 'List Barcode';
        //return view('backend.barcode.index', $this->data);

        //return view('adminlte::category',['category' => $category]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */

    public function store(Request $request)
    {
        //
        $data = new Barcode();
        $data->barcode_number = $request->get('no_barcode');
        $data->barcode_status = 0;
        $data->save();

  
        $this->data['id'] = 0;

        return redirect()->route('backend.barcode.index', $this->data);
        //return redirect()->route('backend.category.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
        $this->data['idorder'] = $id;
        
        $tampiledit = DB::table('order')
            ->leftjoin('users', 'users.id', '=', 'user_id')
            ->leftjoin('option_payment', 'option_payment.id', '=', 'payment_id')
            ->leftjoin('regencies', 'id_regencies', '=', 'city')
            ->leftjoin('provinces', 'province_id', '=', 'id_provinces')
            ->where('order.id', $id)
            ->first();

        $tampilshipping = DB::table('order')
                ->leftjoin('regencies', 'id_regencies', '=', 'shipping_to')
                ->leftjoin('provinces', 'province_id', '=', 'id_provinces')
                ->get();

        $tampilitem = DB::table('order')
                ->leftjoin('order_product', 'order_product.order_id', '=', 'id')
                ->leftjoin('product', 'product_id', '=', 'product.id')
                ->where('order_product.order_id', $id)
                ->get();

        return view('backend.order.edit', $this->data)
                ->with('tampilshipping', $tampilshipping)
                ->with('tampiledit', $tampiledit)
                ->with('tampilitem', $tampilitem);
            
    }
    public function edititem($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
        $dataupdate = Order::findOrFail($id);
        $dataupdate->order_status = $request->get('status');
        $dataupdate->comments = $request->get('note');
        $dataupdate->update();
        
        $this->data['id'] = 0;

        return redirect()->route('backend.order.index', $this->data);

    }
    public function updateitem($id) {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
        
        $dataupdate = Barcode::findOrFail($id);

        $dataupdate->delete();
        $this->data['id'] = 0;

        return redirect()->route('backend.barcode.index', $this->data);
        
    }

}
