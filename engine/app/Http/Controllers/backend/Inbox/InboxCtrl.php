<?php

namespace App\Http\Controllers\backend\Inbox;
use App\Models\Page\Contact;
use App\Http\Controllers\Controller;
use Storage,Image,Entrust,DB,Request,Exception;
use Redirect;

class InboxCtrl extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct() {
        $this->data['title'] = 'Inbox';
        $this->data['sub_title'] = 'List Inbox';
    }

    public function index() {
        $tampilinbox = DB::table('contact_us')
                ->get();
        return view('backend.inbox.index', $this->data)
                ->with('tampilinbox', $tampilinbox);
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
        $this->data['idinbox'] = $id;
        
        $tampiledit = DB::table('contact_us')
            ->where('id', $id)
            ->first();

        return view('backend.inbox.edit', $this->data)
                ->with('tampiledit', $tampiledit);
            
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
        $dataupdate = Contact::findOrFail($id);
        $dataupdate->contact_us_status = 1;
        //$dataupdate->update();
        
        $this->data['id'] = 0;

        //return redirect('backend/inbox/1/edit');

    }

    public function updateInbox()
    {
        $input = Request::except('_token');
        $id = $input['id'];
        $dataupdate = Contact::findOrFail($id);
        $dataupdate->contact_us_status = 1;
        $dataupdate->update();
        return redirect('backend/inbox/'.$id.'/edit');

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
