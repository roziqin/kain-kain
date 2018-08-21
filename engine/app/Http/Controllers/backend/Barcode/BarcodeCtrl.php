<?php

namespace App\Http\Controllers\backend\Barcode;
use Illuminate\Http\Request;
use App\Models\Barcode\Barcode;
//use App\Models\Products\Barcode;
use App\Http\Controllers\Controller;
use Storage,Image,Entrust;

class BarcodeCtrl extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct() {
        $this->data['title'] = 'Barcode';
        $this->data['sub_title'] = 'List Barcode';
    }

    public function index() {

        $tampilbarcode =  Barcode::all();

        if (!Entrust::can('category-read')) {
            return redirect('');
        }
        $this->data['sub_title'] = 'List Category';
        $this->data['id'] = 0;
        return view('backend.barcode.index', $this->data)
            ->with('tampilbarcode', $tampilbarcode);

        //
        //$this->data['title'] = 'Barcode';
        //$this->data['sub_title'] = 'List Barcode';
        //return view('backend.barcode.index', $this->data);

        //return view('adminlte::category',['category' => $category]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex() {

        if (!Entrust::can('product-read')) {
            return redirect('');
        }
        $this->data['id'] = 0;
        return view('backend.barcode.index', $this->data);
        //
//        if (!Entrust::can('page-read')) {
//            return redirect('');
//        }
        //$this->data['sub_title'] = '';
        //$this->data['city'] = Options\Shipping::where('ship_option_name', 'shipping_from')->first()->ship_option_value;
        //return view('backend.options.index', $this->data);
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
        $this->data['id'] = $id;
        $tampiledit = Barcode::where('barcode_id', $id)->first();
        //$this->data['barcode_id']
        $this->data['barcode_id'] = $tampiledit->barcode_id;
        $this->data['barcode_number'] = $tampiledit->barcode_number;
        return redirect()->route('backend.barcode.index', $this->data);
            
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
        $dataupdate = Barcode::findOrFail($id);
        $dataupdate->barcode_number = $request->get('no_barcode');
        $dataupdate->update();
        
        $this->data['id'] = 0;

        return redirect()->route('backend.barcode.index', $this->data);

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
