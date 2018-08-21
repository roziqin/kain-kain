<?php

namespace App\Http\Controllers\backend\Items;
session_start();

use Illuminate\Http\Request;
use App\Models\Products\Product;
use App\Models\Products\Item;
use App\Models\Barcode\Barcode;
use App\Http\Controllers\Controller;
use Storage;
use DB;
use Image,Entrust;

class ItemsCtrl extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        //
        if (!Entrust::can('category-read')) {
            return redirect('');
        }
        $this->data['sub_title'] = 'List Category';
        $this->data['id'] = 0;
        return view('backend.items.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request) {

        //
        
        $id = $_GET['id_product'];
        $barcodeid = $_GET['barcodeid'];
        $item_perroll = $_GET['item_perroll'];

        $img = new Item();
        $img->item_id_product = $id;
        $img->item_barcode = $barcodeid;
        $img->item_perroll = $item_perroll;
        $img->item_totalroll = 1;
        $img->item_totalmeter = $item_perroll;
        $img->save();


        $dataupdate = Barcode::findOrFail($barcodeid);
        $dataupdate->barcode_status = 1;
        $dataupdate->update();

        $_SESSION['tabcontent'] = 'additem';
        //return view('backend.product.edit', $this->data);
        
        return redirect()->route('backend.product.edit',$id);
            //->with('tampilitem', $tampilitem);

        //echo $id." - ".$item_barcode." - ".$item_perroll." - ".$item_totalroll;

    }
    public function getbarcode(Request $request) {
        
        $aaData = Barcode::where('barcode_status', '0')->get();
        return response()->json(compact('aaData'));


    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(ItemRequest $request) {
        //
        $thumb = public_path('images/products/thumb');
        $full = public_path('images/products/full');
        $input = $request->all();
        echo $input['id_product'];
        /*
        if ($request->hasFile('image')) {
            $images = $request->file('image');
            if ($request->has('id_product')) {
                $count = Gambar::where('id_product', '=', $request->get('id_product'))->count();
                if ($count >= 5) {
                    if ($request->ajax()) {
                        return response()->json(['error' => 'Maximum file gambar adalah 5.']);
                    }
                    return redirect()->back()->withErrors('Maximum file gambar adalah 5');
                }
            }
            foreach ($images as $image) {
                $name = str_random(5) . '.' . $image->getClientOriginalExtension();
                $img = new Gambar();
                $img->img_name = $name;
                if ($request->has('id_product')) {
                    $img->id_product = $input['id_product'];
                }
                $img->path_thumb = 'images/products/thumb/' . $name;
                $img->path_full = 'images/products/full/' . $name;
                $img->save();
                Image::make($image)->save($full . '/' . $name);
                Image::make($image)->resize('100', '100')->save($thumb . '/' . $name);
            }
        }
        if ($request->ajax()) {
            return response()->json(['success' => TRUE]);
        }
        return redirect()->back();
        */
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
        $tampilitembarcode = DB::table('product_item')
            ->leftjoin('product_barcode', 'item_barcode', '=', 'barcode_id')
            ->where('item_id', $id)
            ->first();

        $this->data['title'] = 'Item';
        $this->data['sub_title'] = 'Edit Item';
        $this->data['iditem'] = $id;
        return view('backend.items.edit', $this->data)
            ->with('tampilitembarcode', $tampilitembarcode);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {
        //

        $dataupdate = Item::findOrFail($id);
        $dataupdate->item_perroll = $request->get('item_perroll');
        $dataupdate->item_totalmeter = $request->get('item_perroll');
        $dataupdate->update();

        $productid = $request->get('productid');
        return redirect()->route('backend.product.edit',$productid);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $id) {
        //
        $iditem = $request->get('iditem');
        $iditemproduct = $request->get('iditemproduct');
        $idbarcode = $request->get('idbarcode');
        
        $dataupdate = Item::findOrFail($id);
        $dataupdate->delete();


        $dataupdate = Barcode::findOrFail($idbarcode);
        $dataupdate->barcode_status = 0;
        $dataupdate->update();


        $_SESSION['tabcontent'] = 'additem';
        return redirect()->route('backend.product.edit',$iditemproduct);
    }

    public function getItem() {
        $data = Item::DtProduct();
        return response()->json($data);
//        $aaData = Product::with('category', 'image')->get();
//        return response()->json(compact('aaData'));
    }

}
