<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barcodenew;

use Storage,Request,Image,Entrust,DB;

class BarcodenewCtrl extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
    public function __construct() {
        $this->data['title'] = 'Barcode';
        $this->data['sub_title'] = 'List Category';
    }

	public function index()
	{
		//
		if (!Entrust::can('category-read')) {
            return redirect('');
        }
        $this->data['id'] = 0;
        return view('backend.barcode.index', $this->data);
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
        $data = new Barcodenew();
        $data->barcode_number = $request->no_barcode;
        $data->barcode_status = 0;
        $data->save();

        $this->data['id'] = 0;
        return view('backend.barcode.index', $this->data);
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
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
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
	}

}
