<?php

namespace App\Modules\AddOnForm\Controllers;

use App\Libraries\Encryption;
use App\Modules\ProcessPath\Models\ProcessStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddOnFormController extends Controller
{

    public function requestFormContent(Request $request)
    {
        $process_list_id = Encryption::decodeId($request->get('process_list_id'));
        $process_type_id = Encryption::decodeId($request->get('process_type_id'));
        $request_status_id = $request->get('request_status_id');
        $form_id = ProcessStatus::where('process_type_id',$process_type_id)->where('id',$request_status_id)->pluck('form_id');

        if($form_id != '')
        {
            $responseCode = 1;
            $public_html = strval(view("AddOnForm::{$form_id}",compact('form_id','process_list_id')));
        }
        else
        {
            $responseCode = 0;
            $public_html = '';
        }
        $data = ['responseCode' => $responseCode, 'data' => $public_html];
        return response()->json($data);
    }





    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("AddOnForm::index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
