<?php

namespace App\Http\Controllers\backend\Page;

use App\Http\Controllers\Controller;
use App\Http\Requests\backend\Page\PageRequest;
use App\Models\Page\Page;
use DB,Request;

class TesPageCtrl extends Controller {

    public function __construct() {
        $this->data['title'] = 'Pages';
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        //
        
        $this->data['sub_title'] = 'Page List';
        return view('backend.page.index', $this->data);
    }

    public function getPage($position = '') {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(PageRequest $request) {
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(PageRequest $request, $id) {
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        
    }

}
