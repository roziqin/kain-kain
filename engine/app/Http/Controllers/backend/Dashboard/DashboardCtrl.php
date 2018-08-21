<?php

namespace App\Http\Controllers\backend\Dashboard;
session_start();

use Illuminate\Http\Request;
use App\Models\Products\Product;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\Products\Item;
use App\Models\Barcode\Barcode;
use App\Http\Controllers\Controller;
use Storage;
use DB;
use Image,Entrust;

class DashboardCtrl extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        //
        $this->data['title'] = 'Dashboard ';
        $this->data['id'] = 0;
        date_default_timezone_set('Asia/jakarta');
        $tgl=date('Y-m-j');

        $tampilorder = DB::table('order')
                ->select('order.id','date','month',DB::raw('sum(total_price) as total'))
                ->groupBy('month')
                ->get();
        $tampilproduk = DB::table('order')
                ->select('product_name','product_id','date','ukuran','quantity')
                ->leftjoin('order_product', 'order.id', '=', 'order_product.order_id')
                ->leftjoin('product', 'product.id', '=', 'product_id')
                ->where('date', $tgl)
                ->groupBy('product_id')
                ->get();

        $jumlahprodukmeter = DB::table('order')
                ->select('product_id','date','ukuran',DB::raw('sum(quantity) as quantity'))
                ->leftjoin('order_product', 'order.id', '=', 'order_product.order_id')
                ->where('date', $tgl)
                ->where('ukuran', 'ukuran:Meter')
                ->groupBy('product_id')
                ->get();

        $jumlahprodukroll = DB::table('order')
                ->select('product_id','date','ukuran',DB::raw('sum(quantity) as quantity'))
                ->leftjoin('order_product', 'order.id', '=', 'order_product.order_id')
                ->where('date', $tgl)
                ->where('ukuran', 'ukuran:Roll')
                ->groupBy('product_id')
                ->get();
        return view('backend.dashboard', $this->data)
                    ->with('jumlahprodukroll', $jumlahprodukroll)
                    ->with('jumlahprodukmeter', $jumlahprodukmeter)
                    ->with('tampilproduk', $tampilproduk)
                    ->with('tampilorder', $tampilorder);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request) {

       

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(ItemRequest $request) {
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
        //$this->data['images'] = Gambar::where('id_product', '=', $id)->get();
        //return view('backend.product.image', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {
        //

       
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $id) {
        
    }

    public function getData() {
        $tampilorder = DB::table('order')
                ->select('month','month_text',DB::raw('sum(total_price) as total'))
                ->groupBy('month')
                ->get();

        $data = array();
        foreach ($tampilorder as $row) {
            $data[] = $row;
        }

        return response()->json($data);
    }

   
}
