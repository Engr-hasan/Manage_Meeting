@extends('layouts.admin')
@section('content')
    <?php
    use App\Libraries\CommonFunction;$accessMode = ACL::getAccsessRight('spaceAllocation');
    if (!ACL::isAllowed($accessMode, $mode)) {
        die('You have no access right! Please contact with system admin if you have any query.');
    }
    $user_type = CommonFunction::getUserType();

    //    $allRequestVal = old();
    //    if(count($allRequestVal)>0){
    //        dd($allRequestVal);
    //    }
    ?>
    @include('partials.modal')
    <style>
        input.error[type="radio"]{
            outline: 2px solid red;
        }
    </style>
    <section class="content" id="applicationForm">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    {{--start application form with wizard--}}
                    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
                    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}
                    <div class="panel panel-info" id="inputForm">
                        <div class="panel-heading"><h5><strong>Application for Space Allocation</strong></h5></div>
                        <div class="form-body" style="margin:0.5em">
                            <div>

                                <?php
                                //                                $userDeskIds = CommonFunction::getUserDeskIds();
                                //                                dd($appInfo->locked_by==CommonFunction::getUserId());
                                ?>

                                {{--@if (ACL::isAllowed($accessMode, '-UP-') && $viewMode == 'on' && in_array($appInfo->status_id,[1,2,3,12,16, 17, 18,20,19,22])   && in_array($appInfo->desk_id,$userDeskIds) && $appInfo->locked_by==CommonFunction::getUserId())--}}
                                {{--@include('SpaceAllocation::batch-process')--}}
                                {{--@endif --}}{{-- checking desk id 3,4,5,6,7,9 --}}
                                <?php
                                $park_id=Auth::user()->park_id;

                                $desk_id_array = explode(',', \Session::get('user_desk_ids'));
                                $park_ids = explode(',', $park_id);

                                $delegation_desks_ids=CommonFunction::getDelegatedDeskIds();
                                $delegation_parks_ids=CommonFunction::getDelegatedParkIds();
                                ?>




                                <?php if ($viewMode == 'on') { ?>
                                <section class="content-header">
                                    <ol class="breadcrumb">
                                        <li><strong>Tracking no. : </strong>{{ $appInfo->tracking_no  }}</li>
                                        <li><strong> Date of Submission: </strong> {{ \App\Libraries\CommonFunction::formateDate($appInfo->application_date)  }} </li>
                                        <li><strong>Current Status : </strong>
                                            @if(isset($appInfo) && $appInfo->status_id == -1) Draft
                                            @else {!! $statusArray[$appInfo->status_id] !!}
                                            @endif
                                        </li>
                                        <li>
                                            @if($appInfo->desk_id != 0) <strong>Current Desk :</strong>
                                            {{ \App\Libraries\CommonFunction::getDeskName($appInfo->desk_id)  }}
                                            @else
                                                <strong>Current Desk :</strong> Applicant
                                            @endif
                                        </li>
                                        @if(isset($appInfo->status_id) && $appInfo->status_id == 8)
                                            <li>
                                                <strong>Discard Reason :</strong> {{ !empty($appInfo->process_desc)? $appInfo->process_desc : 'N/A' }}
                                            </li>
                                        @endif

                                        @if(isset($appInfo->status_id) && $appInfo->status_id == 18)
                                            <li>
                                                <strong>Challan Declined Reason :</strong> {{ !empty($appInfo->process_desc)? $appInfo->process_desc : 'N/A' }}
                                            </li>
                                        @endif

                                        <li>
                                            <?php if (isset($appInfo) && $appInfo->status_id == 25 && isset($appInfo->certificate) && $appInfo->certificate != '') { ?>
                                            <a href="{{ url($appInfo->certificate) }}" class="btn show-in-view btn-xs btn-info"
                                               title="Download Approval Letter" target="_blank"> <i class="fa  fa-file-pdf-o"></i> <b>Download Certificate</b></a>
                                            @if(Auth::user()->user_type == '1x101')
                                                <a onclick="return confirm('Are you sure ?')" href="/space-allocation/discard-certificate/{{ Encryption::encodeId($appInfo->id)}}" class="btn show-in-view btn-xs btn-danger"
                                                   title="Download Approval Letter"> <i class="fa  fa-trash"></i> <b>Discard Certificate</b></a>
                                            @endif
                                            @if(Auth::user()->user_type != '13x131')
                                                <a href="/space-allocation/project-cer-re-gen/{{ Encryption::encodeId($appInfo->id)}}" class="btn show-in-view btn-xs btn-warning"
                                                   title="Download Approval Letter" target="_self"> <i class="fa  fa-file-pdf-o"></i> <b>Re-generate certificate</b></a>
                                            @endif
                                            <?php } ?>
                                            @if((env('server_type') == 'local' OR env('server_type') == 'dev') && isset($appInfo) && $appInfo->status_id == 23)
                                                <a target="_blank" href="/land-requisition/open-certificate/{{ Encryption::encodeId($appInfo->id)}}" class="btn show-in-view btn-xs btn-success"
                                                   title="Download Approval Letter" target="_self"> <i class="fa  fa-file-pdf-o"></i> <b>Open Certificate</b></a>
                                            @endif
                                        </li>
                                    </ol>
                                </section>



                                @if(Auth::user()->user_type == '1x101' || Auth::user()->user_type == '5x505')
                                    <table class="table table-striped table-bordered table-hover " style="margin-bottom: 20px;">
                                        <thead>
                                        <tr style="background:#ADD7F0">
                                            <th>Organization Name</th>
                                            <th>Level</th>
                                            <th>Space(in sqft)</th>
                                            <th>3 Month's security deposit (30tk/sqft)</th>
                                            <th>Monthly Rent (30tk/sqft)</th>
                                            <th>Service Charge (5tk/sqft)</th>
                                            <th>remarks</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td> {{$appInfo->applicant_name}}</td>
                                            <td> {{$appInfo->ad_desk_level}}</td>
                                            <td> {{$appInfo->ad_desk_space}}</td>
                                            <td> {{$appInfo->ad_desk_security_deposite}}</td>
                                            <td> {{$appInfo->ad_desk_rent}}</td>
                                            <td> {{$appInfo->ad_desk_service_charge}}</td>
                                            <td> {{$appInfo->ad_desk_remarks}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                @endif

                                <?php } ?>
                                @if(ACL::isAllowed($accessMode, '-UP-') && $viewMode == 'on' && $hasDeskParkWisePermission)
                                    @include('ProcessPath::batch-process')
                                @endif

                                {{--@if((in_array($appInfo->desk_id, $userDeskIds) && in_array($appInfo->park_id, $park_ids)) ||--}}
                                {{--(in_array($appInfo->desk_id, $delegation_desks_ids) && in_array($appInfo->park_id, $delegation_parks_ids)))--}}
                                {{--@include('ProcessPath::batch-process')--}}
                                {{--@endif--}}

                                @if ($appInfo->status_id == 15 ||$appInfo->status_id == 16 || $appInfo->status_id == 18 || $appInfo->status_id == 17|| $appInfo->status_id == 25)
                                    @include('SpaceAllocation::pay-order')
                                    {{--@include('SpaceAllocation::pay-order-view')--}}

                                @endif


                                {!! Form::open(array('url' => '/space-allocation/add','method' => 'post','id' => 'appClearenceForm','role'=>'form','enctype'=>'multipart/form-data')) !!}


                                {!! Form::hidden('app_id', Encryption::encodeId($appInfo->id) ,['class' => 'form-control input-sm required', 'id'=>'app_id']) !!}
                                {!! Form::hidden('curr_process_status_id', $appInfo->status_id,['class' => 'form-control input-sm required', 'id'=>'process_status_id']) !!}

                                {{--for file upload--}}
                                <input type="hidden" name="selected_file" id="selected_file"/>
                                <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                                <input type="hidden" name="isRequired" id="isRequired"/>


                                {{--hidden field for show identification type radio box in preview page--}}
                                <input type="hidden" name="identificationValue" id="identificationValue" value=""/>

                                <div class="panel panel-info">
                                    <div class="panel-heading margin-for-preview"><strong>1. Applicant Information</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group clearfix">
                                            <div class="row">
                                                <div class="col-md-7 {{$errors->has('applicant_name') ? 'has-error': ''}}">
                                                    {!! Form::label('applicant_name','Name of the applicant organization:',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('applicant_name',$appInfo->applicant_name,
                                                        ['maxlength'=>'64', 'class' => 'form-control input-sm textOnly required']) !!}
                                                        {!! $errors->first('applicant_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group clearfix">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    {!! Form::label('infrastructureReq','Full Address of Registered Head Office of Applicant / Applying Firm or Company :',
                                                    ['class'=>'text-left col-md-12']) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group" style="clear: both">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('country') ? 'has-error': ''}}">
                                                    {!! Form::label('country','Country :',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('country', $countries, $appInfo->country, ['class' => 'form-control input-sm required','id'=>'country_id']) !!}
                                                        {!! $errors->first('country','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div id="bd_div" class="form-group hidden" style="clear: both">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('division_id') ? 'has-error': ''}}" id="division_div">
                                                    {!! Form::label('division_id','Division :',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('division_id', $divition_eng, $appInfo->division_id,['class' => 'form-control input-sm','placeholder'=>'Select One']) !!}
                                                        {!! $errors->first('division_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('district_id') ? 'has-error': ''}}" id="district_div">
                                                    {!! Form::label('district_id','District :',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('district_id', $district_eng, $appInfo->district_id,
                                                        ['class' => 'form-control input-sm','placeholder'=>'Select One']) !!}
                                                        {!! $errors->first('district_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="foreign_div"  class="form-group " style="clear: both">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('state') ? 'has-error': ''}}" id="state_div">
                                                    {!! Form::label('state','State :',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('state', $appInfo->state,['class' => 'form-control input-sm']) !!}
                                                        {!! $errors->first('state','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('province') ? 'has-error': ''}}" id="province_div">
                                                    {!! Form::label('province','Province :',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('province', $appInfo->province,['class' => 'form-control input-sm']) !!}
                                                        {!! $errors->first('province','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group" style="clear: both">
                                            <div class="row">

                                                <div class="col-md-6">
                                                    {!! Form::label('address_line1','Address Line 1 :',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('address_line1', $appInfo->address_line1, ['maxlength'=>'80',
                                                        'class' => 'form-control input-sm required']) !!}
                                                        {!! $errors->first('address_line1','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('address_line2') ? 'has-error': ''}}">
                                                    {!! Form::label('address_line2','Address Line 2 :', ['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('address_line2', $appInfo->address_line2,
                                                        ['maxlength'=>'80','class' => 'form-control input-sm']) !!}
                                                        {!! $errors->first('address_line2','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group" style="clear: both">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('phone_no') ? 'has-error': ''}}">
                                                    {!! Form::label('phone_no','Phone No :',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('phone_no', $appInfo->phone_no,
                                                        ['maxlength'=>'20', 'class' => 'phone form-control onlyNumber nocomma input-sm required','placeholder'=>'e.g. 012345678']) !!}
                                                        {!! $errors->first('phone_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    {!! Form::label('post_code','Post Code :',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('post_code', $appInfo->post_code, ['maxlength'=>'20',
                                                        'class' => 'form-control input-sm nocomma number']) !!}
                                                        {!! $errors->first('post_code','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group" style="clear: both">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {!! Form::label('fax_no','Fax No :',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('fax_no', $appInfo->fax_no, ['maxlength'=>'20',
                                                        'class' => 'form-control input-sm onlyNumber nocomma','placeholder'=>'e.g. 02 8059253 ']) !!}
                                                        {!! $errors->first('fax_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-6 {{$errors->has('email') ? 'has-error': ''}}">
                                                    {!! Form::label('email','Email :',['class'=>'text-left required-star col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('email', $appInfo->email, ['maxlength'=>'64','class' => 'form-control input-sm email required']) !!}
                                                        {!! $errors->first('email','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group" style="clear: both">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {!! Form::label('website','Website :',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('website', $appInfo->website,
                                                        ['maxlength'=>'100','class' => 'form-control input-sm', 'placeholder'=> 'www.example.com']) !!}
                                                        {!! $errors->first('website','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group" style="clear: both">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('identification_type') ? 'has-error': ''}}">
                                                    {!! Form::label('identification_type','Identification Type :',['class'=>'text-left required-star col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {{--<label class="radio-inline">--}}
                                                        {{--{!! Form::radio('identification_type','nid', ['class' => 'form-control required', 'id'=> 'nid']) !!} NID Number--}}
                                                        {{--</label>--}}
                                                        {{--<label class="radio-inline">--}}
                                                        {{--{!! Form::radio('identification_type','passport', ['class' => 'form-control required','id'=> 'passport']) !!} Passport--}}
                                                        {{--</label>--}}
                                                        <input type="radio" name="identification_type" id="nid" value="nid" class="required" @if($appInfo->identification_type == 1) checked @else @endif>
                                                        <label for="nid"  style="padding-right: 20px;font-weight: normal">NID Number</label>
                                                        <input type="radio" name="identification_type" id="passport" value="passport" class="required" @if($appInfo->identification_type == 2) checked @else @endif>
                                                        <label for="passport" style="font-weight: normal">Passport</label>
                                                        {!! $errors->first('identification_type','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    {!! Form::label('pass_nid_data','NID / Passport :',['class'=>'text-left col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('pass_nid_data', ($appInfo->identification_type == 1 ? $appInfo->nid : $appInfo->passport),['maxlength'=>'100','class' => 'form-control input-sm required', 'placeholder'=> 'NID / Passport']) !!}
                                                        {!! $errors->first('pass_nid_data','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group" style="clear: both">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('vat_reg_no') ? 'has-error': ''}}">
                                                    {!! Form::label('vat_reg_no','VAT Registration Number :',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('vat_reg_no', $appInfo->vat_reg_no,['maxlength'=>'100','class' => 'form-control input-sm', 'placeholder'=> 'VAT Registration Number']) !!}
                                                        {!! $errors->first('vat_reg_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('tin_reg_no') ? 'has-error': ''}}">
                                                    {!! Form::label('tin_reg_no','TIN Registration Number :',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('tin_reg_no', $appInfo->tin_reg_no,['maxlength'=>'100','class' => 'form-control input-sm', 'placeholder'=> 'TIN Registration Number']) !!}
                                                        {!! $errors->first('tin_reg_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>2. Company Information</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group clearfix">

                                            <div class="col-md-6 {{$errors->has('park_id') ? 'has-error': ''}}">
                                                {!! Form::label('park_id','Hi-Tech Park Name :',['class'=>'text-left required-star']) !!}
                                                {!! Form::select('park_id', $parkInfo, $appInfo->park_id,
                                                ['class' => 'form-control input-sm required', 'placeholder' => 'Select One']) !!}
                                                {!! $errors->first('park_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group clearfix">
                                            <div class="col-md-6 {{$errors->has('company_name') ? 'has-error': ''}}">
                                                {!! Form::label('company_name','Company Name :',
                                                ['class'=>'text-left required-star','style'=>'']) !!}
                                                {!! Form::text('company_name', $appInfo->company_name,['maxlength'=>'64','class' => 'form-control textOnly input-sm','readonly'=>'true']) !!}
                                                {!! $errors->first('company_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                            <div class="col-md-6 {{$errors->has('company_logo') ? 'has-error': ''}}">
                                                {!! Form::label('company_logo','Company logo :',
                                                ['class'=>'text-left required-star','style'=>'']) !!}

                                                <?php
                                                $isRequired = (!empty($appInfo->company_logo) ? '' : 'required');
                                                ?>
                                                <span id="company_logo_err" class="text-danger" style="font-size: 10px;"></span>
                                                {!! Form::file('company_logo', ['class'=> $isRequired ,
                                                'data-rule-maxlength'=>'40','onchange'=>'companyLogo(this)'])!!}

                                                <div id="user_image">
                                                    <input type="hidden" value="<?php
                                                    if ($appInfo->company_logo != '') {
                                                    echo $appInfo->company_logo;
                                                    } ?>" id="company_logo" name="company_logo"/>
                                                </div>

                                                <span class="text-danger" style="font-size: 9px; font-weight: bold">
                                                            [File Format: *.jpg/ .jpeg | File size within 3 MB]</span><br/>
                                                <div style="position:relative;">
                                                    <img id="companyLogoViewer"
                                                         style="width:auto;height:70px;position:absolute;top:-56px;right:0px;border:1px solid #ddd;padding:2px;background:#a1a1a1;"
                                                         src="{{ (!empty($appInfo->company_logo) ? url('company_logo/'.$appInfo->company_logo) : url('assets/images/company_logo.png')) }}" alt="">
                                                </div>
                                                {!! $errors->first('company_logo','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group clearfix">
                                            <div class="col-md-6 {{$errors->has('type_of_business_service') ? 'has-error': ''}}">
                                                {!! Form::label('type_of_business_service','Type of Business Services :',['class'=>'text-left required-star']) !!}
                                                {!! Form::select('type_of_business_service', $businessIndustryServices, $appInfo->type_of_business_service,
                                                ['class' => 'form-control input-sm required','placeholder'=>'Select One']) !!}
                                                {!! $errors->first('type_of_business_service','<span class="help-block">:message</span>') !!}
                                            </div>
                                            <div class="col-md-6 {{$errors->has('organization_type') ? 'has-error': ''}}">
                                                {!! Form::label('organization_type','Type of Organization :',['class'=>'text-left required-star']) !!}
                                                {!! Form::select('organization_type', $typeofOrganizations, $appInfo->organization_type, ['class' => 'form-control input-sm required',
                                                'onchange'=>'in_joint_com(this.value)','placeholder'=>'Select One','id' => 'organization_type']) !!}
                                                {!! $errors->first('organization_type','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group" style="clear:both">
                                            <div class="col-md-6 {{$errors->has('industry_type_id') ? 'has-error': ''}}">
                                                {!! Form::label('industry_type_id','Type of Industry :',['class'=>'text-left required-star']) !!}
                                                {!! Form::select('industry_type_id', $typeofIndustry, $appInfo->industry_type_id, ['class' => 'form-control input-sm required','placeholder'=>'Select One']) !!}
                                                {!! $errors->first('industry_type_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('industry_category_id','Industry Category :',['class'=>'text-left']) !!}
                                                {!! Form::select('industry_category_id', $industry_cat, $appInfo->industry_category_id,
                                                ['class' => 'form-control input-sm colors_name required','placeholder'=>'Select One']) !!}
                                            </div>
                                        </div>

                                        <div class="form-group clearfix" style="clear:both; padding-top: 5px;">
                                            <div class="col-md-6">

                                                <div id="eia_cer_file_div" class="row" style="<?php
                                                if(!isset($appInfo->eia_cer_exist) /*for first entry*/ ||
                                                (isset($appInfo->eia_cer_exist) && ($appInfo->eia_cer_exist != 'yes'))){
                                                echo 'display:none;'; } ?>">
                                                    <div class="col-md-12">
                                                        {!! Form::label('eia_cer_file','Environment Impact Assessment Certificate :',
                                                        ['class'=>'font-ok required-star']) !!}
                                                        <?php if($viewMode != 'on') {?>
                                                        <br/> [<span class="text-danger" style="font-size: 9px; font-weight: bold">
                                                                File Format: *.pdf | Maximum File size 3MB]
                                                             </span>
                                                        <?php }?>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <input type="file" size="20" name="eia_cer_file"   id="eia_cer_file"
                                                               class="<?php if(isset($appInfo->eia_cer_file) &&
                                                               (isset($appInfo->eia_cer_exist) &&
                                                               $appInfo->eia_cer_exist == 'yes'))
                                                               { echo " "; } ?>"/>
                                                        {!! $errors->first('eia_cer_file','<span class="help-block">:message</span>') !!}
                                                        @if (Session::has('error_message'))
                                                            <div class="text-danger">{{ Session::get('error_message') }}</div>
                                                        @endif
                                                        <span id="eia_cer_file_error" class="text-danger"></span>
                                                    </div>
                                                    @if(isset($appInfo->eia_cer_file))
                                                        <div class="save_file col-md-12" style="margin-top: 5px;">
                                                            <a target="_blank" title="{{ $appInfo->eia_cer_file}}"
                                                               href="{{URL::to('/'.$appInfo->eia_cer_file)}}"
                                                               class="btn btn-xs btn-info show-in-view">
                                                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                                <?php $certificate_name_exp = explode('/',$appInfo->eia_cer_file);
                                                                echo substr(end($certificate_name_exp), 0, 100 ); ?> </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6 ">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="alert alert-info clearfix hidden" style="padding: 5px" id="color_alert">
                                                            <div class="col-md-6" id="color_name" style="font-weight: bold;">
                                                                {{ (!empty($industryCatInfo->color_name)?$industryCatInfo->color_name:'') }}</div>
                                                            <?php $color_code = (!empty($industryCatInfo->color_code)? $industryCatInfo->color_code : '#FFFFFF') ?>
                                                            <div id="change_colours" class="col-md-6" style="height: 20px;background-color: {{ $color_code }};"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 hidden" id="eia_exist_div">
                                                    {!! Form::label('eia_cer_exist','Do you have approved Environmental Impact Assessment (EIA) Certificate?',
                                                    ['style'=>'font-size: 13px;']) !!}

                                                    <?php
                                                    if(isset($appInfo->eia_cer_exist)){
                                                    $eiaYes = ($appInfo->eia_cer_exist == 'yes') ? 'checked' : '';
                                                    $eiaNo = ($appInfo->eia_cer_exist == 'no') ? 'checked' : '';
                                                    } else{
                                                    $eiaYes = '';
                                                    $eiaNo = 'checked';
                                                    }
                                                    ?>
                                                    <div class="col-md-6">
                                                        <label>
                                                            <div class="col-md-5"> Yes</div>
                                                            <div class="col-md-6">
                                                                <input type="radio" name="eia_cer_exist" id="eia_cer_exist_yes" value="yes" class="eia_radio"
                                                                       <?php echo $eiaYes ?> onclick="showEiaCerDiv(this)"/>
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>
                                                            <div class="col-md-5">No</div>
                                                            <div class="col-md-6">
                                                                <input type="radio" name="eia_cer_exist" id="eia_cer_exist_no" value="no" class="eia_radio"
                                                                       <?php echo $eiaNo; ?> onclick="showEiaCerDiv(this)"/>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>




                                        <div class="form-group type_of_organizations_main hidden" style="clear:both;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table id="type_of_organizations" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                                                            <thead class="alert alert-info">
                                                            <tr>
                                                                <th>Company Name <span class="required-star"></span></th>
                                                                <th>Company Address <span class="required-star"></span></th>
                                                                <th>Country <span class="required-star"></span></th>
                                                                <th><span class="hashs">#</span></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @if(count($jointOrganizations) > 0)
                                                                <?php $inc = 0; ?>
                                                                @foreach($jointOrganizations as $eachJointOrg)
                                                                    <tr class="otherJointRows" id="joinRows{{$inc}}">
                                                                        <td>
                                                                            {!! Form::hidden("joint_id[$inc]",$eachJointOrg->id) !!}
                                                                            {!! Form::text("joint_company[$inc]",$eachJointOrg->joint_company, ['maxlength'=>'100',
                                                                            'class' => 'form-control input-sm']) !!}
                                                                            {!! $errors->first('joint_company','<span class="help-block">:message</span>') !!}
                                                                        </td>
                                                                        <td>
                                                                            {!! Form::text("joint_company_address[$inc]",$eachJointOrg->joint_company_address, ['maxlength'=>'100',
                                                                            'class' => 'form-control input-sm']) !!}
                                                                            {!! $errors->first("joint_company_address[$inc]",'<span class="help-block">:message</span>') !!}
                                                                        </td>
                                                                        <td>{!! Form::select("joint_com_country[$inc]", $countries, $eachJointOrg->joint_com_country, ['maxlength'=>'100',
                                                                            'class' => 'form-control input-sm']) !!}
                                                                            {!! $errors->first("joint_com_country[$inc]",'<span class="help-block">:message</span>') !!}
                                                                        </td>
                                                                        <td>
                                                                            <span class="add_new_span">
                                                                                <?php if ($inc !== 0) { ?>
                                                                                <a class="btn btn-sm addTableRows btn-danger" onclick="removeTableRow('type_of_organizations','joinRows{{$inc}}')">
                                                                                    <i class="fa fa-times"></i></a>
                                                                                <?php
                                                                                } else { ?>
                                                                                <a class="btn btn-sm btn-info addTableRows" onclick="addTableRow('type_of_organizations', 'joinRows0');"><i class="fa fa-plus"></i></a>
                                                                                <?php } ?>
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                    <?php $inc++; ?>
                                                                @endforeach
                                                            @else
                                                                <tr id="joinRows0">
                                                                    <td>
                                                                        {!! Form::text('joint_company[0]','', ['maxlength'=>'100',
                                                                        'class' => 'form-control input-sm']) !!}
                                                                        {!! $errors->first('joint_company','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text('joint_company_address[0]','', ['maxlength'=>'100',
                                                                        'class' => 'form-control input-sm']) !!}
                                                                        {!! $errors->first('joint_company_address[0]','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>{!! Form::select('joint_com_country[0]', $countries,'', ['maxlength'=>'100',
                                                                        'class' => 'form-control input-sm']) !!}
                                                                        {!! $errors->first('joint_com_country[0]','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                    <span class="add_new_span">
                                                                        <a class="btn btn-xs btn-primary addTableRows" onclick="addTableRow('type_of_organizations', 'joinRows0');"><i class="fa fa-plus"></i></a>
                                                                    </span>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>3. Nature Of Proposed Business</strong></div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table id="productionPrgTbl" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                                                        <thead class="alert alert-info">
                                                        <tr class="text-center">
                                                            <th class="valigh-middle text-center"><span class="required-star"></span>Description</th>
                                                            <th class="valigh-middle text-center" width="10%"> Unit</th>
                                                            <th class="text-center">1st Year <span class="required-star"></span><br/>Qty</th>
                                                            <th class="text-center">2nd Year <span class="required-star"></span><br/>Qty</th>
                                                            <th class="text-center">3rd Year <span class="required-star"></span><br/>Qty</th>
                                                            <th class="text-center">4th Year <span class="required-star"></span><br/>Qty</th>
                                                            <th class="text-center">5th Year <span class="required-star"></span><br/>Qty</th>
                                                            <th class="valigh-middle text-center"> Total</th>
                                                            <th class="valigh-middle text-center"><span class="hashs">#</span></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        @if(count($proposedBusinessInfo) > 0)
                                                            <?php $srl = 0; ?>
                                                            @foreach($proposedBusinessInfo as $businessInfo)
                                                                <tr id="rowProductionCount{{$srl}}">
                                                                    <td>
                                                                        {!! Form::hidden("proposeBusinessIds[$srl]",$businessInfo->id) !!}
                                                                        {!! Form::text("production_desc[$srl]",(isset($appInfo->applicant_name) ? $appInfo->applicant_name : $businessInfo->description),
                                                                        ['maxlength'=>'100','class' => 'form-control input-sm required textOnly production_desc_1st']) !!}
                                                                        {!! $errors->first('production_desc','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>{!! Form::select("production_unit[$srl]",$units, $businessInfo->unit_id,['maxlength'=>'100','class' =>'form-control input-sm','placeholder'=>'Select']) !!}
                                                                        {!! $errors->first('production_unit','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text("production_1st[$srl]",$businessInfo->qty_1st, ['maxlength'=>'100','class' => 'form-control input-sm required onlyNumber',
                                                                        'onKeyUp' => 'calculateTotal(this.parentNode.parentNode.id)']) !!}
                                                                        {!! $errors->first('production_1st','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text("production_2nd[$srl]", $businessInfo->qty_2nd, ['maxlength'=>'100','class' => 'form-control input-sm required onlyNumber',
                                                                        'onKeyUp' => 'calculateTotal(this.parentNode.parentNode.id)']) !!}
                                                                        {!! $errors->first('production_2nd','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>{!! Form::text("production_3rd[$srl]", $businessInfo->qty_3rd, ['maxlength'=>'100','class' => 'form-control input-sm required onlyNumber',
                                                                    'onKeyUp' => 'calculateTotal(this.parentNode.parentNode.id)']) !!}
                                                                        {!! $errors->first('production_3rd','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text("production_4th[$srl]", $businessInfo->qty_4th, ['maxlength'=>'100','class' => 'form-control input-sm required onlyNumber',
                                                                        'onKeyUp' => 'calculateTotal(this.parentNode.parentNode.id)']) !!}
                                                                        {!! $errors->first('production_4th','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>{!! Form::text("production_5th[$srl]", $businessInfo->qty_5th, ['maxlength'=>'100','class' => 'form-control input-sm required onlyNumber',
                                                                    'onKeyUp' => 'calculateTotal(this.parentNode.parentNode.id)']) !!}
                                                                        {!! $errors->first('production_5th','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>{!! Form::text("production_total[$srl]", $businessInfo->qty_total, ['maxlength'=>'100','class' => 'form-control input-sm onlyNumber', 'readonly'=>true]) !!}
                                                                        {!! $errors->first('production_total','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        <?php if ($srl !== 0) { ?>
                                                                        <a class="btn btn-sm btn-danger" onclick="removeTableRow('productionPrgTbl', 'rowProductionCount{{$srl}}');">
                                                                            <i class="fa fa-times"></i></a>
                                                                        <?php } else { ?>
                                                                        <a class="btn btn-sm btn-info addTableRows productionPrgAddRow" onclick="addTableRow('productionPrgTbl', 'rowProductionCount0');">
                                                                            <i class="fa fa-plus"></i></a>
                                                                        <?php } ?>

                                                                    </td>
                                                                </tr>
                                                                <?php $srl++; ?>
                                                            @endforeach
                                                        @endif

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>4. Service/Products</strong></div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class=" col-md-12 {{$errors->has('sp_product_description') ? 'has-error': ''}}">
                                                {!! Form::label('sp_product_description','a) Name / description of the product(s) :' ,
                                                ['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::textarea('sp_product_description', $appInfo->sp_product_description,['maxlength'=>'1000','class' => 'form-control input-sm required', 'size'=>'5x2']) !!}
                                                    {!! $errors->first('sp_product_description','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>

                                            <div class=" col-md-12 {{$errors->has('sp_product_usage') ? 'has-error': ''}}">
                                                {!! Form::label('sp_product_usage', 'b) Usage of the product(s) :' ,['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::textarea('sp_product_usage', $appInfo->sp_product_usage,['class' => 'form-control input-sm required required-star', 'size' => '5x2', 'maxlength'=>'1000']) !!}
                                                    {!! $errors->first('sp_product_usage','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>

                                            <div class=" col-md-12 {{$errors->has('sp_manufacture_process') ? 'has-error': ''}}">
                                                {!! Form::label('sp_manufacture_process','c) Manufacturing process :', ['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::textarea('sp_manufacture_process', $appInfo->sp_manufacture_process,['maxlength'=>'1000','class' => 'form-control input-sm required', 'size' => '5x2']) !!}
                                                    {!! $errors->first('sp_manufacture_process','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>

                                            <div class=" col-md-12 {{$errors->has('sp_project_cost') ? 'has-error': ''}}">
                                                {!! Form::label('sp_project_cost', 'd) Cost of the project (in US$) :' ,['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('sp_project_cost', $appInfo->sp_project_cost,['class' => 'form-control input-sm required onlyNumber','maxlength'=>'20']) !!}
                                                    {!! $errors->first('sp_project_cost','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class=" col-md-12 {{$errors->has('sp_annual_turnover') ? 'has-error': ''}}">
                                                {!! Form::label('sp_annual_turnover', 'e) Annual Turnover (in BDT) :' ,['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('sp_annual_turnover', $appInfo->sp_annual_turnover,
                                                    ['class' => 'form-control input-sm required onlyNumber','maxlength'=>'20']) !!}
                                                    {!! $errors->first('sp_annual_turnover','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class=" col-md-12 {{$errors->has('sp_liquid_asset') ? 'has-error': ''}}">
                                                {!! Form::label('sp_liquid_asset', 'e) Liquid Asset (in BDT) :' ,['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('sp_liquid_asset', $appInfo->sp_liquid_asset,
                                                    ['class' => 'form-control input-sm required onlyNumber','maxlength'=>'20']) !!}
                                                    {!! $errors->first('sp_liquid_asset','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>

                                        </div><!--/row-->
                                    </div><!--/panel-body-->
                                </div>

                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>5.Number of existing employees</strong></div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table id="mpReqTbl" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                                                        <thead class="alert alert-info">
                                                        <tr>
                                                            <th valign="top" class="text-center valigh-middle" width="11%">Year <span class="required-star"></span></th>
                                                            <th colspan="4" valign="top" class="text-center valigh-top">
                                                                <table class="table table-striped table-bordered dt-responsive">
                                                                    <thead class="alert alert-info">
                                                                    <tr class="text-center">
                                                                        <th colspan="4" valign="top" class="text-center valigh-top">IT Professional</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="text-center valigh-top" style="width:104px;">Managerial <span class="required-star"></span></th>
                                                                        <th class="text-center valigh-top" style="width:107px;">Skilled <span class="required-star"></span></th>
                                                                        <th class="text-center valigh-top" style="width:107px;">Unskilled <span class="required-star"></span></th>
                                                                        <th class="text-center valigh-top" style="width:105px;">Total <span class="required-star"></span></th>
                                                                    </tr>
                                                                    </thead>
                                                                </table>
                                                            </th>
                                                            <th colspan="4" valign="top" class="text-center valigh-top">
                                                                <table class="table table-striped table-bordered dt-responsive">
                                                                    <thead class="alert alert-info">
                                                                    <tr class="text-center">
                                                                        <th colspan="4" valign="top" class="text-center valigh-top">Supporting Staff</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="text-center valigh-top" style="width:104px;">Managerial <span class="required-star"></span></th>
                                                                        <th class="text-center valigh-top" style="width:107px;">Skilled <span class="required-star"></span></th>
                                                                        <th class="text-center valigh-top" style="width:107px;">Unskilled <span class="required-star"></span></th>
                                                                        <th class="text-center valigh-top" style="width:105px;">Total <span class="required-star"></span></th>
                                                                    </tr>
                                                                    </thead>
                                                                </table>
                                                            </th>
                                                            <th valign="top" class="text-center valigh-top">Grand Total <span class="required-star"></span></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        @if(count($existingEmployee) > 0)
                                                            <?php $srl = 0; ?>
                                                            @foreach($existingEmployee as $employee)
                                                                <tr>
                                                                    <td>
                                                                        {!! Form::hidden("exEmployeeIds[$srl]", $employee->id) !!}
                                                                        {!! Form::selectYear("ex_year[$srl]",  Date('Y'), Date('Y')+ 5, $employee->year,['maxlength'=>'20','class' => 'form-control input-sm required onlyNumber', 'placeholder' => 'Select One']) !!}
                                                                        {!! $errors->first('mp_year_1','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text("ex_it_managerial[$srl]", $employee->it_managerial,['maxlength'=>'40','class' => 'form-control input-sm required onlyNumber', 'id' => 'for_man_8',
                                                                        'onKeyUp' => 'Calculate3Numbers("for_man_8", "for_skill_8", "for_unskill_8", "for_total_8",1)',
                                                                        'onblur' => 'Calculate3Numbers("for_man_8", "for_skill_8", "for_unskill_8", "for_total_8", 1)']) !!}
                                                                        {!! $errors->first('for_man_1','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text("ex_it_skilled[$srl]", $employee->it_skilled,['maxlength'=>'40','class' => 'form-control input-sm required onlyNumber', 'id' => 'for_skill_8',
                                                                        'onKeyUp' => 'Calculate3Numbers("for_man_8", "for_skill_8", "for_unskill_8", "for_total_8", 1)',
                                                                        'onblur' => 'Calculate3Numbers("for_man_8", "for_skill_8", "for_unskill_8", "for_total_8", 1)']) !!}
                                                                        {!! $errors->first('for_skill_1','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text("ex_it_unskilled[$srl]", $employee->it_unskilled,['maxlength'=>'40','class' => 'form-control input-sm required onlyNumber', 'id' => 'for_unskill_8',
                                                                        'onKeyUp' => 'Calculate3Numbers("for_man_8", "for_skill_8", "for_unskill_8", "for_total_8",1)',
                                                                        'onblur' => 'Calculate3Numbers("for_man_8", "for_skill_8", "for_unskill_8", "for_total_8",1)']) !!}
                                                                        {!! $errors->first('for_unskill_1','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text("ex_it_total[$srl]", $employee->it_total,['maxlength'=>'40','class' => 'form-control input-sm required onlyNumber', 'id' => 'for_total_8', 'readonly']) !!}
                                                                        {!! $errors->first('for_total_1','<span class="help-block">:message</span>') !!}
                                                                    </td>

                                                                    <td>
                                                                        {!! Form::text("ex_ss_managerial[$srl]", $employee->ss_managerial,['maxlength'=>'40','class' => 'form-control input-sm required onlyNumber', 'id' => 'loc_man_8',
                                                                        'onKeyUp' => 'Calculate3Numbers("loc_man_8", "loc_skill_8", "loc_unskill_8", "loc_total_8",1)',
                                                                        'onblur' => 'Calculate3Numbers("loc_man_8", "loc_skill_8", "loc_unskill_8", "loc_total_8",1)']) !!}
                                                                        {!! $errors->first('loc_man_1','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text("ex_ss_skilled[$srl]", $employee->ss_skilled,['maxlength'=>'40','class' => 'form-control input-sm required onlyNumber', 'id' => 'loc_skill_8',
                                                                        'onKeyUp' => 'Calculate3Numbers("loc_man_8", "loc_skill_8", "loc_unskill_8", "loc_total_8",1)',
                                                                        'onblur' => 'Calculate3Numbers("loc_man_8", "loc_skill_8", "loc_unskill_8", "loc_total_8",1)']) !!}
                                                                        {!! $errors->first('loc_skill_1','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text("ex_ss_unskilled[$srl]", $employee->ss_unskilled,['maxlength'=>'40','class' => 'form-control input-sm required onlyNumber', 'id' => 'loc_unskill_8',
                                                                        'onKeyUp' => 'Calculate3Numbers("loc_man_8", "loc_skill_8", "loc_unskill_8", "loc_total_8",1)',
                                                                        'onblur' => 'Calculate3Numbers("loc_man_8", "loc_skill_8", "loc_unskill_8", "loc_total_8",1)']) !!}
                                                                        {!! $errors->first('loc_unskill_1','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text("ex_ss_total[$srl]", $employee->ss_total,['maxlength'=>'40','class' => 'form-control input-sm required onlyNumber', 'id' => 'loc_total_8', 'readonly']) !!}
                                                                        {!! $errors->first('loc_total_1','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text("ex_grand_total[$srl]", $employee->grand_total,['maxlength'=>'40','class' => 'form-control input-sm required onlyNumber', 'id'=>'gr_total_8', 'readonly']) !!}
                                                                        {!! $errors->first('gr_total_1','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                </tr>
                                                                <?php $srl++; ?>
                                                            @endforeach
                                                        @endif


                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>6. Proposed Business Plan For Next 5 Years</strong></div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table id="productionPlanTbl" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                                                        <thead class="alert alert-info">
                                                        <tr class="text-center">
                                                            <th class="valigh-middle text-center"><span class="required-star"></span>Description</th>
                                                            <th class="valigh-middle text-center" width="10%"> Unit</th>
                                                            <th class="text-center">1st Year <span class="required-star"></span><br/>Qty</th>
                                                            <th class="text-center">2nd Year <span class="required-star"></span><br/>Qty</th>
                                                            <th class="text-center">3rd Year <span class="required-star"></span><br/>Qty</th>
                                                            <th class="text-center">4th Year <span class="required-star"></span><br/>Qty</th>
                                                            <th class="text-center">5th Year <span class="required-star"></span><br/>Qty</th>
                                                            <th class="valigh-middle text-center"> Total</th>
                                                            <th class="valigh-middle text-center"><span class="hashs">#</span></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @if(count($proposedBusinessPlan) > 0)
                                                            <?php $srl = 0; ?>
                                                            @foreach($proposedBusinessPlan as $businessPlan)
                                                                <tr id="rowProductionPlanCount{{$srl}}">
                                                                    <td>
                                                                        {!! Form::hidden("proposeBusinessPlanIds[$srl]",$businessPlan->id) !!}
                                                                        {!! Form::text("production_desc_plan[$srl]", $businessPlan->description,['maxlength'=>'100','class' => 'form-control input-sm required production_desc_plan_1st']) !!}
                                                                        {!! $errors->first('production_desc_plan','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>{!! Form::select("production_unit_plan[$srl]",$units,$businessPlan->unit_id,['maxlength'=>'100','class' =>'form-control input-sm','placeholder'=>'Select']) !!}
                                                                        {!! $errors->first('production_unit_plan','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text("production_plan_1st[$srl]",$businessPlan->qty_1st, ['maxlength'=>'100','class' => 'form-control input-sm required onlyNumber',
                                                                        'onKeyUp' => 'calculateTotal(this.parentNode.parentNode.id)']) !!}
                                                                        {!! $errors->first('production_plan_1st','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text("production_plan_2nd[$srl]",$businessPlan->qty_2nd, ['maxlength'=>'100','class' => 'form-control input-sm required onlyNumber',
                                                                        'onKeyUp' => 'calculateTotal(this.parentNode.parentNode.id)']) !!}
                                                                        {!! $errors->first('production_plan_2nd','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>{!! Form::text("production_plan_3rd[$srl]",$businessPlan->qty_3rd, ['maxlength'=>'100','class' => 'form-control input-sm required onlyNumber',
                                                                    'onKeyUp' => 'calculateTotal(this.parentNode.parentNode.id)']) !!}
                                                                        {!! $errors->first('production_plan_3rd','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text("production_plan_4th[$srl]",$businessPlan->qty_4th, ['maxlength'=>'100','class' => 'form-control input-sm required onlyNumber',
                                                                        'onKeyUp' => 'calculateTotal(this.parentNode.parentNode.id)']) !!}
                                                                        {!! $errors->first('production_plan_4th','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>{!! Form::text("production_plan_5th[$srl]",$businessPlan->qty_5th, ['maxlength'=>'100','class' => 'form-control input-sm required onlyNumber',
                                                                    'onKeyUp' => 'calculateTotal(this.parentNode.parentNode.id)']) !!}
                                                                        {!! $errors->first('production_plan_5th','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>{!! Form::text("production_total_plan[$srl]", $businessPlan->qty_total, ['maxlength'=>'100','class' => 'form-control input-sm onlyNumber', 'readonly'=>true]) !!}
                                                                        {!! $errors->first('production_total_plan','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        @if($srl == 0)
                                                                            <a class="btn btn-sm btn-info addTableRows" onclick="addTableRow('productionPlanTbl', 'rowProductionPlanCount0');">
                                                                                <i class="fa fa-plus"></i></a>
                                                                        @else
                                                                            <a class="btn btn-sm btn-danger " onclick="removeTableRow('productionPlanTbl', 'rowProductionPlanCount{{$srl}}');">
                                                                                <i class="fa fa-times"></i></a>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <?php $srl++; ?>
                                                            @endforeach
                                                        @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>7.Planned Employment For Next 5 Years</strong></div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table id="mpReqTbl" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                                                        <thead class="alert alert-info">
                                                        <tr>
                                                            <th valign="top" class="text-center valigh-middle" width="11%">Year <span class="required-star"></span></th>
                                                            <th colspan="4" valign="top" class="text-center valigh-top">
                                                                <table class="table table-striped table-bordered dt-responsive">
                                                                    <thead class="alert alert-info">
                                                                    <tr class="text-center">
                                                                        <th colspan="4" valign="top" class="text-center valigh-top">IT Professional</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="text-center valigh-top" style="width:104px;">Managerial <span class="required-star"></span></th>
                                                                        <th class="text-center valigh-top" style="width:107px;">Skilled <span class="required-star"></span></th>
                                                                        <th class="text-center valigh-top" style="width:107px;">Unskilled <span class="required-star"></span></th>
                                                                        <th class="text-center valigh-top" style="width:105px;">Total <span class="required-star"></span></th>
                                                                    </tr>
                                                                    </thead>
                                                                </table>
                                                            </th>
                                                            <th colspan="4" valign="top" class="text-center valigh-top">
                                                                <table class="table table-striped table-bordered dt-responsive">
                                                                    <thead class="alert alert-info">
                                                                    <tr class="text-center">
                                                                        <th colspan="4" valign="top" class="text-center valigh-top">Supporting Staff</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="text-center valigh-top" style="width:104px;">Managerial <span class="required-star"></span></th>
                                                                        <th class="text-center valigh-top" style="width:107px;">Skilled <span class="required-star"></span></th>
                                                                        <th class="text-center valigh-top" style="width:107px;">Unskilled <span class="required-star"></span></th>
                                                                        <th class="text-center valigh-top" style="width:105px;">Total <span class="required-star"></span></th>
                                                                    </tr>
                                                                    </thead>
                                                                </table>
                                                            </th>
                                                            <th valign="top" class="text-center valigh-top">Grand Total <span class="required-star"></span></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @if(count($nextEmployee) > 0)
                                                            <?php $srl = 0; ?>
                                                            @foreach($nextEmployee as $nextEmp)
                                                                <tr>
                                                                    <td>
                                                                        {!! Form::hidden("nextEmployeeIds[$srl]",$nextEmp->id) !!}
                                                                        {!! Form::selectYear("year[$srl]",  Date('Y'), Date('Y')+ 5, $nextEmp->year,['maxlength'=>'20','class' => 'form-control input-sm required onlyNumber', 'placeholder' => 'Select One']) !!}
                                                                        {!! $errors->first('mp_year_1','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text("it_managerial[$srl]",$nextEmp->it_managerial,['maxlength'=>'40','class' => 'form-control input-sm required onlyNumber', 'id' => "for_man_$srl",
                                                                        'onKeyUp' => "Calculate3Numbers('for_man_$srl', 'for_skill_$srl', 'for_unskill_$srl', 'for_total_$srl',1)",
                                                                        'onblur' => "Calculate3Numbers('for_man_$srl', 'for_skill_$srl', 'for_unskill_$srl', 'for_total_$srl',1)"]) !!}
                                                                        {!! $errors->first('for_man_1','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text("it_skilled[$srl]",$nextEmp->it_skilled,['maxlength'=>'40','class' => 'form-control input-sm required onlyNumber', 'id' => "for_skill_$srl",
                                                                        'onKeyUp' => "Calculate3Numbers('for_man_$srl', 'for_skill_$srl', 'for_unskill_$srl', 'for_total_$srl',1)",
                                                                        'onblur' => "Calculate3Numbers('for_man_$srl', 'for_skill_$srl', 'for_unskill_$srl', 'for_total_$srl',1)"]) !!}
                                                                        {!! $errors->first('for_skill_1','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text("it_unskilled[$srl]",$nextEmp->it_unskilled,['maxlength'=>'40','class' => 'form-control input-sm required onlyNumber', 'id' => "for_unskill_$srl",
                                                                        'onKeyUp' => "Calculate3Numbers('for_man_$srl', 'for_skill_$srl', 'for_unskill_$srl', 'for_total_$srl',1)",
                                                                        'onblur' => "Calculate3Numbers('for_man_$srl', 'for_skill_$srl', 'for_unskill_$srl', 'for_total_$srl',1)"]) !!}
                                                                        {!! $errors->first('for_unskill_1','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text("it_total[$srl]",$nextEmp->it_total,['maxlength'=>'40','class' => 'form-control input-sm required onlyNumber', 'id' => "for_total_$srl", 'readonly']) !!}
                                                                        {!! $errors->first('for_total_1','<span class="help-block">:message</span>') !!}
                                                                    </td>

                                                                    <td>
                                                                        {!! Form::text("ss_managerial[$srl]",$nextEmp->ss_managerial,['maxlength'=>'40','class' => 'form-control input-sm required onlyNumber', 'id' => "loc_man_$srl",
                                                                        'onKeyUp' => "Calculate3Numbers('loc_man_$srl', 'loc_skill_$srl', 'loc_unskill_$srl', 'loc_total_$srl',1)",
                                                                        'onblur' => "Calculate3Numbers('loc_man_$srl', 'loc_skill_$srl', 'loc_unskill_$srl', 'loc_total_$srl',1)"]) !!}
                                                                        {!! $errors->first('loc_man_1','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text("ss_skilled[$srl]",$nextEmp->ss_skilled,['maxlength'=>'40','class' => 'form-control input-sm required onlyNumber', 'id' => "loc_skill_$srl",
                                                                        'onKeyUp' => "Calculate3Numbers('loc_man_$srl', 'loc_skill_$srl', 'loc_unskill_$srl', 'loc_total_$srl',1)",
                                                                        'onblur' => "Calculate3Numbers('loc_man_$srl', 'loc_skill_$srl', 'loc_unskill_$srl', 'loc_total_$srl',1)"]) !!}
                                                                        {!! $errors->first('loc_skill_1','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text("ss_unskilled[$srl]",$nextEmp->ss_unskilled,['maxlength'=>'40','class' => 'form-control input-sm required onlyNumber', 'id' => "loc_unskill_$srl",
                                                                        'onKeyUp' => "Calculate3Numbers('loc_man_$srl', 'loc_skill_$srl', 'loc_unskill_$srl', 'loc_total_$srl',1)",
                                                                        'onblur' => "Calculate3Numbers('loc_man_$srl', 'loc_skill_$srl', 'loc_unskill_$srl', 'loc_total_$srl',1)"]) !!}
                                                                        {!! $errors->first('loc_unskill_1','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text("ss_total[$srl]",$nextEmp->ss_total,['maxlength'=>'40','class' => 'form-control input-sm required onlyNumber', 'id' => "loc_total_$srl", 'readonly']) !!}
                                                                        {!! $errors->first('loc_total_1','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text("grand_total[$srl]",$nextEmp->grand_total,['maxlength'=>'40','class' => 'form-control input-sm required onlyNumber', 'id'=>"gr_total_$srl", 'readonly']) !!}
                                                                        {!! $errors->first('gr_total_1','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                </tr>
                                                                <?php $srl++; ?>
                                                            @endforeach
                                                        @endif

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>8.Space Required (In Sqft) </strong></div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table id="infraReqTbl" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                                                <thead class="alert alert-info">
                                                <tr>
                                                    <th>Space</th>

                                                    <th>Regular Operation Period at maximum capacity <span class="required-star"></span></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <label class="text-left" for="land">Space (in SqFT) :</label>
                                                    </td>

                                                    <td>
                                                        {!! Form::text('infrastructure_space',$appInfo->infrastructure_space,['maxlength'=>'40','class' => 'form-control input-sm required onlyNumber']) !!}
                                                        {!! $errors->first('infrastructure_space','<span class="help-block">:message</span>') !!}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        {!! Form::label('infrastructure_power','Power (in KW/H) :', ['class' => 'text-left']) !!}
                                                    </td>

                                                    <td>
                                                        {!! Form::text('infrastructure_power', $appInfo->infrastructure_power,['maxlength'=>'40','class' => 'form-control input-sm required onlyNumber']) !!}
                                                        {!! $errors->first('infrastructure_power','<span class="help-block">:message</span>') !!}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="text-left" for="gas">GAS (in M<sup>3</sup>) :</label>
                                                    </td>

                                                    <td>
                                                        {!! Form::text('infrastructure_gas',$appInfo->infrastructure_gas,['maxlength'=>'40','class' => 'form-control input-sm required onlyNumber']) !!}
                                                        {!! $errors->first('infrastructure_gas','<span class="help-block">:message</span>') !!}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="text-left" for="water">Water (in M<sup>3</sup>) :</label>
                                                    </td>

                                                    <td>
                                                        {!! Form::text('infrastructure_water',$appInfo->infrastructure_water,['maxlength'=>'40','class' => 'form-control input-sm required onlyNumber']) !!}
                                                        {!! $errors->first('infrastructure_water','<span class="help-block">:message</span>') !!}
                                                    </td>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>9. Foreign Partner / Investor</strong></div>
                                    <div class="panel-body">

                                        <div class="form-group" style="clear:both">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table id="directors_list" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                                                        <thead class="alert alert-info">
                                                        <tr class="text-center">
                                                            <th>Name <span class="required-star"></span></th>
                                                            <th>Address <span class="required-star"></span></th>
                                                            <th>Nationality</th>
                                                            <th>Status in the proposed company <span class="required-star"></span></th>
                                                            <th>Extent of share Holding (%) <span class="required-star"></span>
                                                                <span id="share_holder_percentage_error" style="color:red"> </span>
                                                            </th>
                                                            <th class=""><span class="hashs">#</span></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @if(count($foreignPartner) > 0)
                                                            <?php $srl = 0; ?>
                                                            @foreach($foreignPartner as $partner)

                                                                <tr id="templateRow{{ $srl }}">
                                                                    <td>
                                                                        {!! Form::hidden("sponsorsIds[$srl]", $partner->id)!!}
                                                                        {!! Form::text("sponsor_name[$srl]",$partner->sponsor_name, ['maxlength'=>'100',
                                                                        'class' => 'form-control input-sm required']) !!}
                                                                        {!! $errors->first('sponsor_name','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text("sponsor_address[$srl]",$partner->sponsor_address, ['maxlength'=>'100',
                                                                        'class' => 'form-control input-sm required']) !!}
                                                                        {!! $errors->first('sponsor_address','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::select("sponsor_nationality[$srl]", $nationality, $partner->sponsor_nationality, ['maxlength'=>'100',
                                                                        'class' => 'form-control input-sm', 'placeholder' => 'Select One']) !!}
                                                                        {!! $errors->first('sponsor_nationality','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>{!! Form::text("sponsor_status[$srl]",$partner->sponsor_status, ['maxlength'=>'100',
                                                                        'class' => 'form-control input-sm required']) !!}
                                                                        {!! $errors->first('sponsor_status','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text("sponsor_share_ext[$srl]", $partner->sponsor_share_ext, ['maxlength'=>'20', 'onblur'=>"countValue(this)",
                                                                        'class' => 'form-control input-sm required countValue onlyNumber']) !!}
                                                                        {!! $errors->first('sponsor_share_ext','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        @if($srl == 0)
                                                                            <a class="btn btn-sm btn-info addTableRows" onclick="addTableRow('directors_list', 'templateRow0');"><i
                                                                                        class="fa fa-plus"></i></a>
                                                                        @else
                                                                            <a class="btn btn-sm btn-danger" onclick="removeTableRow('directors_list', 'templateRow{{ $srl }}');"><i
                                                                                        class="fa fa-times"></i></a>
                                                                        @endif

                                                                    </td>
                                                                </tr>

                                                                <?php $srl++; ?>
                                                            @endforeach
                                                        @endif

                                                        </tbody>
                                                    </table>
                                                    <br>
                                                    <div class="table-responsive">
                                                        <table id="" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                                                            <thead class="alert alert-warning">
                                                            <tr>
                                                                <th>&nbsp;</th>
                                                                <th>Local Share<span class="required-star"></span></th>
                                                                <th>Foreign Share <span class="required-star"></span></th>
                                                                <th>Total Share <span class="required-star"></span> <span id="paid_cap_percentage_error" style="color:red"></span></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <th>Paid-up Capital (%)</th>
                                                                <td>
                                                                    {!! Form::text('paidup_capital_local', $appInfo->paidup_capital_local,['class' => 'form-control input-sm required onlyNumber','maxlength'=>20, 'id' => 'paid_cap_amount',
                                                                    'onKeyUp' => 'Calculate2Numbers("paid_cap_amount", "paid_cap_nature", "paid_cap_percentage","paid_cap_percentage_error")']) !!}
                                                                    {!! $errors->first('paidup_capital_local','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('paidup_capital_foreign', $appInfo->paidup_capital_foreign,['class' => 'form-control input-sm required onlyNumber','maxlength'=>20, 'id' => 'paid_cap_nature',
                                                                    'onKeyUp' => 'Calculate2Numbers("paid_cap_amount", "paid_cap_nature", "paid_cap_percentage","paid_cap_percentage_error")']) !!}
                                                                    {!! $errors->first('paidup_capital_foreign','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>{!! Form::text('paidup_capital_total', $appInfo->paidup_capital_total,['class' => 'form-control input-sm required onlyNumber','maxlength'=>20, 'id' => 'paid_cap_percentage', 'readonly']) !!}
                                                                    {!! $errors->first('paidup_capital_total','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-info">
                                    <div class="panel-heading"><b>10. Trade Body Membership Number</b></div>
                                    <!-- /.panel-heading -->
                                    <div class="panel-body">
                                        <table id="" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                                            <thead class="alert alert-info">

                                            <tr>
                                                <th>#Sl</th>
                                                <th>Name of the organization</th>
                                                <th> No.</th>

                                                <th> Attachment <br/>
                                                    <span class="text-danger text-sm">(PDF | Max file size 3 MB)</span>
                                                    <span onmouseover="toolTipFunction()" data-toggle="tooltip"
                                                          title="Attached PDF file (Maximum file size 3MB)!">
                                                                    <i class="fa fa-question-circle" aria-hidden="true"></i>
                                                                </span>
                                                </th>
                                                {{--<th>#</th>--}}
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php $sl = 1; ?>
                                            @foreach($tradeBody as $tradeData)
                                                <tr>
                                                    <td>{{ $sl }}</td>
                                                    <td>
                                                        {!! Form::hidden("tb_ids[$sl]", $tradeData->id) !!}
                                                        {!! Form::text("tb_org[$sl]",$tradeData->tb_org,['data-rule-maxlength'=>'40','class' => 'form-control input-sm', 'id'=>'incumbent_result']) !!}
                                                        {!! $errors->first('tb_org[]','<span class="help-block">:message</span>') !!}</td>
                                                    <td>{!! Form::text("tb_no[$sl]",$tradeData->tb_no,['data-rule-maxlength'=>'40','class' => 'form-control input-sm', 'id'=>'incumbent_result']) !!}
                                                        {!! $errors->first('tb_no[]','<span class="help-block">:message</span>') !!}
                                                    </td>
                                                    <td>
                                                        {{--<input type="file" size="20" name="tb_file[]" id="" class=""/>--}}
                                                        {!! Form::file("tb_file[$sl]") !!}
                                                        {!! $errors->first("tb_file[$sl]",'<span class="help-block">:message</span>') !!}
                                                        <span id="incumbent_certificate_error" class="text-danger"></span>
                                                        @if($tradeData->tb_file != '')
                                                            <a target="_blank" class="documentUrl" href="{{URL::to('/uploads/'. $tradeData->tb_file)}}"
                                                               title="{{$tradeData->tb_org}}">
                                                                <i class="fa fa-file-pdf-o"
                                                                   aria-hidden="true"></i> <?php $file_name = explode('/', $tradeData->tb_file); echo end($file_name); ?>
                                                            </a>
                                                        @endif
                                                    </td>
                                                    {{--<td>--}}
                                                    {{--<a href="javascript:;" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i></a>--}}
                                                    {{--</td>--}}
                                                </tr>
                                                <?php $sl++; ?>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {{--<div class="panel panel-info">--}}
                                {{--<div class="panel-heading"><b>11. Ad Desk Form</b></div>--}}

                                {{--<div class="panel-body">--}}
                                {{--<div class="table-responsive">--}}
                                {{--<table class="table table-striped table-bordered table-hover ">--}}
                                {{--<thead>--}}
                                {{--<tr style="background: #faebcc">--}}
                                {{--<th>Organization Name</th>--}}
                                {{--<th>Level</th>--}}
                                {{--<th>Space(in sqft)</th>--}}
                                {{--<th>3 Month's security deposit (30tk/sqft)</th>--}}
                                {{--<th>Monthly Rent (30tk/sqft)</th>--}}
                                {{--<th>Service Charge (5tk/sqft)</th>--}}
                                {{--<th>remarks</th>--}}
                                {{--</tr>--}}
                                {{--</thead>--}}
                                {{--<tbody>--}}
                                {{--<tr>--}}
                                {{--<td> {!! Form::text('ad_desk_org_name', $appInfo->ad_desk_org_name,['data-rule-maxlength'=>'40','class' => 'form-control input-sm']) !!}</td>--}}
                                {{--<td> {!! Form::text('ad_desk_level', $appInfo->ad_desk_level,['data-rule-maxlength'=>'40','class' => 'form-control input-sm']) !!}</td>--}}
                                {{--<td> {!! Form::text('ad_desk_space', $appInfo->ad_desk_space,['data-rule-maxlength'=>'40','class' => 'form-control input-sm']) !!}</td>--}}
                                {{--<td> {!! Form::text('ad_desk_security_deposite', $appInfo->ad_desk_security_deposite,['data-rule-maxlength'=>'40','class' => 'form-control input-sm']) !!}</td>--}}
                                {{--<td> {!! Form::text('ad_desk_rent', $appInfo->ad_desk_rent,['data-rule-maxlength'=>'40','class' => 'form-control input-sm']) !!}</td>--}}
                                {{--<td> {!! Form::text('ad_desk_service_charge', $appInfo->ad_desk_service_charge,['data-rule-maxlength'=>'40','class' => 'form-control input-sm']) !!}</td>--}}
                                {{--<td> {!! Form::textarea('ad_desk_remarks', $appInfo->ad_desk_remarks,['data-rule-maxlength'=>'40','class' => 'form-control input-sm','rows'=>'1']) !!}</td>--}}
                                {{--</tr>--}}
                                {{--</tbody>--}}
                                {{--</table>--}}

                                {{--</div>--}}
                                {{--</div><!-- /.panel-body -->--}}
                                {{--</div>--}}

                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>11. Required Documents for attachment</strong></div>
                                    <div class="panel-body">

                                        <div class="table-responsive">
                                            {{--these input type is needed for ajax file upload--}}
                                            <input type="hidden" name="selected_file" id="selected_file"/>
                                            <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                                            <input type="hidden" name="isRequired" id="isRequired"/>

                                            <table class="table table-striped table-bordered table-hover ">
                                                <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th colspan="6">Required Attachments</th>
                                                    <th colspan="2">Attached PDF file
                                                        <span onmouseover="toolTipFunction()" data-toggle="tooltip"
                                                              title="Attached PDF file (Each File Maximum size 1MB)!">
                                                        <i class="fa fa-question-circle" aria-hidden="true"></i></span>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php $i = 1; ?>
                                                @foreach($document as $row)
                                                    <tr>
                                                        <td>
                                                            <div align="center">{!! $i !!}<?php echo $row->is_required == "1" ? "<span class='required-star'></span>" : ""; ?></div>
                                                        </td>
                                                        <td colspan="6">{!!  $row->doc_name !!}</td>
                                                        <td colspan="2">
                                                            <input name="document_id_<?php echo $row->id; ?>" type="hidden"
                                                                   value="{{(!empty($clrDocuments[$row->id]['doucument_id']) ? $clrDocuments[$row->id]['doucument_id'] : '')}}">
                                                            <input type="hidden" value="{!!  $row->doc_name !!}"
                                                                   id="doc_name_<?php echo $row->id; ?>"
                                                                   name="doc_name_<?php echo $row->id; ?>"/>
                                                            <input name="file<?php echo $row->id; ?>"
                                                                   <?php if (empty($clrDocuments[$row->id]['doc_file_path']) && empty($allRequestVal["file$row->id"]) && $row->is_required == "1") {
                                                                   echo "class='required'";
                                                                   } ?>
                                                                   id="file<?php echo $row->id; ?>" type="file" size="20"
                                                                   onchange="uploadDocument('preview_<?php echo $row->id; ?>', this.id, 'validate_field_<?php echo $row->id; ?>', '<?php echo $row->is_required; ?>')"/>

                                                            @if($row->additional_field == 1)
                                                                <table>
                                                                    <tr>
                                                                        <td>Other file Name :</td>
                                                                        <td><input maxlength="64"
                                                                                   class="form-control input-sm <?php if ($row->is_required == "1") {
                                                                                   echo 'required';
                                                                                   } ?>"
                                                                                   name="other_doc_name_<?php echo $row->id; ?>"
                                                                                   type="text"
                                                                                   value="{{(!empty($clrDocuments[$row->id]['doc_name']) ? $clrDocuments[$row->id]['doc_name'] : '')}}">
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            @endif

                                                            @if(!empty($clrDocuments[$row->id]['doc_file_path']))
                                                                <div class="save_file saved_file_{{$row->id}}" style="display: block">
                                                                    <a target="_blank" class="documentUrl" href="{{URL::to('/uploads/'.(!empty($clrDocuments[$row->id]['doc_file_path']) ?
                                                                    $clrDocuments[$row->id]['doc_file_path'] : ''))}}"
                                                                       title="{{$row->doc_name}}">
                                                                        <i class="fa fa-file-pdf-o"
                                                                           aria-hidden="true"></i> <?php $file_name = explode('/', $clrDocuments[$row->id]['doc_file_path']); echo end($file_name); ?>
                                                                    </a>


                                                                    <?php if($viewMode != 'on' && Auth::user()->id == $appInfo->created_by) {?>
                                                                    <a href="javascript:void(0)"
                                                                       onclick="ConfirmDeleteFile({{ $row->id }})">
                                                                    <span class="btn btn-xs btn-danger"><i
                                                                                class="fa fa-times"></i></span>
                                                                    </a>
                                                                    <?php } ?>
                                                                </div>
                                                            @endif

                                                            <div id="preview_<?php echo $row->id; ?>">
                                                                <input type="hidden"
                                                                       value="<?php echo !empty($clrDocuments[$row->id]['doc_file_path']) ?
                                                                       $clrDocuments[$row->id]['doc_file_path'] : ''?>"
                                                                       id="validate_field_<?php echo $row->id; ?>"
                                                                       name="validate_field_<?php echo $row->id; ?>"
                                                                       class="<?php echo $row->is_required == "1" ? "required" : '';  ?>"/>
                                                            </div>

                                                            @if(!empty($allRequestVal["file$row->id"]))
                                                                <label id="label_file{{$row->id}}"><b>File: {{$allRequestVal["file$row->id"]}}</b></label>
                                                                <input type="hidden" class="required"
                                                                       value="{{$allRequestVal["validate_field_".$row->id]}}"
                                                                       id="validate_field_{{$row->id}}"
                                                                       name="validate_field_{{$row->id}}">
                                                            @endif

                                                        </td>
                                                    </tr>
                                                    <?php $i++; ?>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div><!-- /.table-responsive -->
                                    </div>
                                </div>

                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>12. Terms and Conditions</strong></div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12" style="margin: 12px 0;">
                                                <input {{ ($appInfo->acceptTerms == 1)?'checked':'' }} id="acceptTerms-2" name="acceptTerms" type="checkbox" class="required col-md-1 text-left" style="width:3%;">
                                                <label for="acceptTerms-2" class="col-md-11 text-left required-star">I agree with the Terms and Conditions.</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if(ACL::getAccsessRight('spaceAllocation','-E-') && $viewMode != "on")
                                    <div class="pull-left">
                                        @if($appInfo->status_id != 5)
                                            <button type="submit" class="btn btn-info btn-md cancel"
                                                    value="draft" name="actionBtn">Save as Draft
                                            </button>
                                        @endif
                                    </div>

                                    <div class="pull-right">
                                        <button type="submit" class="btn btn-info btn-md" value="save" name="actionBtn">
                                            @if($appInfo->status_id == 5) Re-Submit @else Submit @endif
                                        </button>
                                    </div>
                                    <div class="clearfix"></div>
                                @endif

                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    {{--End application form with wizard--}}

                    {{--application history panel--}}
                    {{--application history panel start--}}
                    {{--@if(isset($viewMode) && $viewMode == "on" && (in_array(Auth::user()->user_type, array('1x101','3x303','4x404')) || in_array($appInfo->desk_id, $desk_id_array) ) && $appInfo->status_id != -1)--}}
                    @if(in_array(Auth::user()->user_type, array('1x101','3x303','4x404')))
                        @include('ProcessPath::application-history')
                    @endif
                    {{--application history panel end--}}
                </div>
            </div>
        </div>
        </div>
        </div>
    </section>


@endsection

@section('footer-script')

    <script>

        $(document).ready(function () {

            // on select Country 'BD' show district/division field
            $('#country_id').on('change', function () {
                var countryCode = $(this).val();
                if (countryCode == 'BD') {
                    $('#bd_div').removeClass('hidden');
                    $('#division_id').addClass('required');
                    $('#district_id').addClass('required');

                    $('#foreign_div').addClass('hidden');
                    $('#state').removeClass('required');
                    $('#province').removeClass('required');
                } else {
                    $('#bd_div').addClass('hidden');
                    $('#division_id').removeClass('required');
                    $('#district_id').removeClass('required');

                    $('#foreign_div').removeClass('hidden');
                    $('#state').addClass('required');
                    $('#province').addClass('required');
                }
            });
            $('#country_id').trigger('change');




            $("#division_id").change(function () {
                var divisionId = $('#division_id').val();
                $(this).after('<span class="loading_data">Loading...</span>');
                var self = $(this);
                $.ajax({
                    type: "GET",
                    url: "<?php echo url(); ?>/users/get-district-by-division",
                    data: {
                        divisionId: divisionId
                    },
                    success: function (response) {
                        var option = '<option value="">Select One</option>';
                        if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
                                option += '<option value="' + id + '">' + value + '</option>';
                            });
                        }
                        $("#district_id").html(option);
                        $(self).next().hide();
                    }
                });
            });



            // Main form Validate
            $("#appClearenceForm").validate({
                rules:{
                    'paidup_capital_total':{
                        required:true,
                        min:100
                    }
                }
            });

            // challan form Validate
            $("#challanForm").validate();


            // challan form Validate
            $("#updateProcess").validate({});



            $("#industry_category_id").change(function () {
                var industry_category_id = $(this).val();
                if (!industry_category_id) {
                    $('#change_colours').hide();
                    $('#color_name').hide();
                }else{
                    $(this).after('<span class="loading_data">Loading...</span>');
                    var self = $(this);
                    $.ajax({
                        dataType: 'json',
                        type: "GET",
                        url: "<?php echo url(); ?>/space-allocation/colour-change/",
                        data: {
                            industry_category_id: industry_category_id
                        },
                        success: function (response) {
                            var code = response.code;
                            var color_name = response.name;
                            $('#color_alert').removeClass('hidden');
                            $('#change_colours').css('background-color', code);
                            $('#color_name').html(color_name);
                            if(color_name == "Red"){
                                var eia_cer_exist = '<?php echo isset($appInfo->eia_cer_exist) ?
                                    $appInfo->eia_cer_exist : '';?>';
                                if(eia_cer_exist == 'no'){
                                    $("input:radio:nth-child(2)").trigger('click'); // trigger EIA cer no
                                }else if (eia_cer_exist == 'yes'){
                                    $("input:radio:first").trigger('click'); // trigger EIA cer yes
                                    var eia_cer_file = '<?php echo isset($appInfo->eia_cer_file)?$appInfo->eia_cer_file:'';?>';
                                    if(eia_cer_file == ''){
                                        $('#eia_cer_file').addClass('required');
                                    }
                                } else{
                                    $('#eia_cer_file_div').css("display", "none");
                                    $("input:radio:nth-child(2)").trigger('click');
                                }
                                $('#eia_exist_div').removeClass('hidden');
                            } else{ // For other colors
                                $('#eia_exist_div').addClass('hidden');
                                $('#eia_cer_file_div').css("display", "none");
                                $('#eia_cer_file').removeClass('required');
                            } // checking colors for EIA
                            $('#change_colours').show();
                            $('#color_name').show();
                            $(self).next().hide();
                        }
                    });
                }
                $(this).next().css('display', 'none');
            });
            $('#industry_category_id').trigger('change');
        });

        // Type of Organization on select 'join venture'
        function in_joint_com(sel) {
            var thisVal = sel;
            if (thisVal !== '' && (thisVal === 'Joint Venture' || thisVal == 3)) {
                $('.type_of_organizations_main').removeClass('hidden');
                $('.type_of_organizations_main').find('input[type=text]').addClass('required');
                $('.type_of_organizations_main').find('select').addClass('required');
                //$('.add_new_span').html('<a class="btn btn-xs btn-primary addTableRows" onclick=addTableRow("type_of_organizations","joinRows0");><i class="fa fa-plus"></i></a>');
            } else {
                //$('.add_new_span').html('-');
                $('.type_of_organizations_main').addClass('hidden');
                $('.type_of_organizations_main').find('input[type=text]').removeClass('required');
                $('.type_of_organizations_main').find('select').removeClass('required');
                $('.otherJointRows[id!="joinRows0"]').remove();
                $('#joinRows0').find('input[type="text"]').val('');
                $('#joinRows0').find('select').prop('selectedIndex', 0);
            }
        }
        $("#organization_type").trigger('change');

        // Company logo preview and validation
        function companyLogo(input) {
            if (input.files && input.files[0]) {
                $("#company_logo_err").html('');
                var mime_type = input.files[0].type;
                if(!(mime_type=='image/jpeg' || mime_type=='image/jpg' || mime_type=='image/png')){
                    $("#company_logo_err").html("Image format is not valid. Only PNG or JPEG or JPG type images are allowed.");
                    return false;
                }
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#companyLogoViewer').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }


        // Environmental Impact Assessment (EIA) Certificate
        function showEiaCerDiv(thisRadio) {
            var eia_exist = $(thisRadio).val();
            if (eia_exist == 'no') {
                $('#eia_cer_file_div').css("display", "none");
                $('#eia_cer_file').removeClass('required');
            } else {
                $('#eia_cer_file_div').css("display", "block");
                var eia_cer_file = '<?php echo isset($appInfo->eia_cer_file) ? $appInfo->eia_cer_file : '';?>';
                if (eia_cer_file == '') {
                    $('#eia_cer_file').addClass('required')
                } else {
                    $('#eia_cer_file').removeClass('required');
                }
                ;
            }
        }


        // Paid-up Capital  Calculation
        function Calculate2Numbers(arg1, arg2, place, messArea) {
            var no1 = $('#' + arg1).val() ? parseFloat($('#' + arg1).val()) : 0;
            if (no1 > 100) $('#' + arg1).addClass("required");
            var no2 = $('#' + arg2).val() ? parseFloat($('#' + arg2).val()) : 0;
            if (no2 > 100) $('#' + arg2).addClass("required");
            var total = new SumArguments(no1, no2);
            $('#' + place).val(total.sum());
            if (total.sum() != 100) {
                $("#" + messArea).show().text("Should be 100%");
            } else {
                $("#" + messArea).hide().html('');
            }
            if(total.sum() == 100){
                $('#' + place).removeClass('error');
            }
        }
        $('#paid_cap_amount').trigger('keyup');


        // Paid-up Capital  Summation
        function SumArguments() {
            var _arguments = arguments;
            this.sum = function () {
                var i = _arguments.length;
                var result = 0;
                while (i--) {
                    result += _arguments[i];
                }
                return result;
            };
        }

        // Planned Employment For Next 5 Years Calculation
        function Calculate3Numbers(arg1, arg2, arg3, place, extra) {
            var no1 = $('#' + arg1).val() ? parseFloat($('#' + arg1).val()) : 0;
            var no2 = $('#' + arg2).val() ? parseFloat($('#' + arg2).val()) : 0;
            var no3 = $('#' + arg3).val() ? parseFloat($('#' + arg3).val()) : 0;
            var total = new SumArguments(no1, no2, no3);

            $('#' + place).val(total.sum());

            /********This parameter 1 will be  used only when calculating grand total of Manpower div ******/
            if (extra == 1) {
                var res = place.split("_");
                var totalField_Id = res[2];
                var fnT = $('#for_total_' + totalField_Id).val() ? parseFloat($('#for_total_' + totalField_Id).val()) : 0;
                var lcT = $('#loc_total_' + totalField_Id).val() ? parseFloat($('#loc_total_' + totalField_Id).val()) : 0;
                $("#gr_total_" + totalField_Id).val(fnT + lcT);
            }
        }


        // Extent of share Holding (%) calculation
        function countValue(thisItem) {
            var sum = 0;
            $('.countValue').each(function () {
                var value = $(this).val();
                if (!isNaN(value) && value.length !== 0) {
                    sum += parseFloat(value);
                }
            });
            if (sum == 100) {
                $('.countValue').each(function () {
                    var value = $(this).val();
                    if (value.length == '') {
                        $(this).val(0).prop('readonly', true);
                    }
                });
                $('#share_holder_percentage_error').text('');
            } else {
                $('#share_holder_percentage_error').text('Should be 100% in total');
            }
        }



        // Proposed Business Plan For Next 5 Years Calculation
        function calculateTotal(id) {
            var totalBoxStart = 1;
            var totalBoxEnd = 5;
            var setTotalBoxNum = 6;
            var totalVal = 0;
            $("#" + id).find("input[type=text]").each(function (key, value) {

                if (key >= totalBoxStart && key <= totalBoxEnd) {
                    if (this.value != '' && !isNaN(this.value))
                        totalVal += parseFloat(this.value);
                }
                if (key == setTotalBoxNum) {
                    this.value = totalVal;
                }
            });
        }


        //--------File Upload Script Start----------//
        function uploadDocument(targets, id, vField, isRequired) {
            var inputFile = $("#" + id).val();
            if (inputFile == '') {
                $("#" + id).html('');
                document.getElementById("isRequired").value = '';
                document.getElementById("selected_file").value = '';
                document.getElementById("validateFieldName").value = '';
                document.getElementById(targets).innerHTML = '<input type="hidden" class="required" value="" id="' + vField + '" name="' + vField + '">';
                if ($('#label_' + id).length)
                    $('#label_' + id).remove();
                return false;
            }

            try {
                document.getElementById("isRequired").value = isRequired;
                document.getElementById("selected_file").value = id;
                document.getElementById("validateFieldName").value = vField;
                document.getElementById(targets).style.color = "red";
                var action = "{{url('/space-allocation/upload-document')}}";
                $("#" + targets).html('Uploading....');
                var file_data = $("#" + id).prop('files')[0];
                var form_data = new FormData();
                form_data.append('selected_file', id);
                form_data.append('isRequired', isRequired);
                form_data.append('validateFieldName', vField);
                form_data.append('_token', "{{ csrf_token() }}");
                form_data.append(id, file_data);
                $.ajax({
                    target: '#' + targets,
                    url: action,
                    dataType: 'text', // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    success: function (response) {
                        $('#' + targets).html(response);
                        var fileNameArr = inputFile.split("\\");
                        var l = fileNameArr.length;
                        if ($('#label_' + id).length)
                            $('#label_' + id).remove();
                        var doc_id = parseInt(id.substring(4));
                        var newInput = $('<label class="saved_file_' + doc_id + '" id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + ' <a href="javascript:void(0)" onclick="EmptyFile(' + doc_id + ')"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
//                        var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                        $("#" + id).after(newInput);
                        //check valid data
                        var validate_field = $('#' + vField).val();
                        if (validate_field == '') {
                            document.getElementById(id).value = '';
                        }
                    }
                });
            } catch (err) {
                document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
            }
        }
        //--------File Upload Script End----------//


        function addTableRow(tableID, templateRow) {
            //rowCount++;
            //Direct Copy a row to many times
            var x = document.getElementById(templateRow).cloneNode(true);
            x.id = "";
            x.style.display = "";
            var table = document.getElementById(tableID);
            var rowCount = $('#' + tableID).find('tr').length - 1;
            var lastTr = $('#' + tableID).find('tr').last().attr('data-number');
            var production_desc_val = $('#' + tableID).find('tr').last().find('.production_desc_1st').val();
            if (lastTr != '' && typeof lastTr !== "undefined") {
                rowCount = parseInt(lastTr) + 1;
            }
            //var rowCount = table.rows.length;
            //Increment id
            var rowCo = rowCount;
            var idText = 'rowCount' + tableID + rowCount;
            x.id = idText;
            $("#" + tableID).append(x);
            //get select box elements
            var attrSel = $("#" + tableID).find('#' + idText).find('select');
            //edited by ishrat to solve select box id auto increment related bug
            for (var i = 0; i < attrSel.length; i++) {
                var nameAtt = attrSel[i].name;
                var repText = nameAtt.replace('[0]', '[' + rowCo + ']'); //increment all array element name
                attrSel[i].name = repText;
            }
            attrSel.val(''); //value reset
            // end of  solving issue related select box id auto increment related bug by ishrat

            //get input elements
            var attrInput = $("#" + tableID).find('#' + idText).find('input');
            for (var i = 0; i < attrInput.length; i++) {
                var nameAtt = attrInput[i].name;
                //increment all array element name
                var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
                attrInput[i].name = repText;
            }
            attrInput.val(''); //value reset
            //edited by ishrat to solve textarea id auto increment related bug
            //get textarea elements
            var attrTextarea = $("#" + tableID).find('#' + idText).find('textarea');
            for (var i = 0; i < attrTextarea.length; i++) {
                var nameAtt = attrTextarea[i].name;
                //increment all array element name
                var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
                attrTextarea[i].name = repText;
                $('#' + idText).find('.readonlyClass').prop('readonly', true);
            }
            attrTextarea.val(''); //value reset
            // end of  solving issue related textarea id auto increment related bug by ishrat

            attrSel.prop('selectedIndex', 0);  //selected index reset
            //Class change by btn-danger to btn-primary
            $("#" + tableID).find('#' + idText).find('.addTableRows').removeClass('btn-primary').addClass('btn-danger')
                .attr('onclick', 'removeTableRow("' + tableID + '","' + idText + '")');
            $("#" + tableID).find('#' + idText).find('.addTableRows > .fa').removeClass('fa-plus').addClass('fa-times');
            $('#' + tableID).find('tr').last().attr('data-number', rowCount);

            $("#" + tableID).find('#' + idText).find('.onlyNumber').on('keydown', function (e) {
                //period decimal
                if ((e.which >= 48 && e.which <= 57)
                    //numpad decimal
                    || (e.which >= 96 && e.which <= 105)
                    // Allow: backspace, delete, tab, escape, enter and .
                    || $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1
                    // Allow: Ctrl+A
                    || (e.keyCode == 65 && e.ctrlKey === true)
                    // Allow: Ctrl+C
                    || (e.keyCode == 67 && e.ctrlKey === true)
                    // Allow: Ctrl+V
                    || (e.keyCode == 86 && e.ctrlKey === true)
                    // Allow: Ctrl+X
                    || (e.keyCode == 88 && e.ctrlKey === true)
                    // Allow: home, end, left, right
                    || (e.keyCode >= 35 && e.keyCode <= 39)) {
                    var thisVal = $(this).val();
                    if (thisVal.indexOf(".") != -1 && e.key == '.') {
                        return false;
                    }
                    $(this).removeClass('error');
                    return true;
                }
                else {
                    $(this).addClass('error');
                    return false;
                }
            });

            var productionPrgAddRow = $("#" + tableID).find('.productionPrgAddRow').hasClass('productionPrgAddRow');
            if (productionPrgAddRow === true) {
                var xx = document.getElementById('rowProExportCount0').cloneNode(true);
                xx.id = "";
                xx.style.display = "";

                var rowCount = $('#proExportTbl').find('tr').length - 1;
                var lastTr = $('#proExportTbl').find('tr').last().attr('data-number');
                if (lastTr != '' && typeof lastTr !== "undefined") {
                    rowCount = parseInt(lastTr) + 1;
                }

                //Increment id
                var rowCo = rowCount;
                var idText = 'rowCountproExportTbl' + rowCount;
                xx.id = idText;
                $('#proExportTbl').append(xx);


                var attrSel = $("#proExportTbl").find('#' + idText).find('select');
                //edited by ishrat to solve select box id auto increment related bug
                for (var i = 0; i < attrSel.length; i++) {
                    var nameAtt = attrSel[i].name;
                    var repText = nameAtt.replace('[0]', '[' + rowCo + ']'); //increment all array element name
                    attrSel[i].name = repText;
                }
                attrSel.val(''); //value reset
                // end of  solving issue related select box id auto increment related bug by ishrat

                //get input elements
                var attrInput = $("#proExportTbl").find('#' + idText).find('input');
                for (var i = 0; i < attrInput.length; i++) {
                    var nameAtt = attrInput[i].name;
                    //increment all array element name
                    var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
                    attrInput[i].name = repText;
                }
                attrInput.val(''); //value reset
                //edited by ishrat to solve textarea id auto increment related bug
                //get textarea elements
                var attrTextarea = $("#proExportTbl").find('#' + idText).find('textarea');
                for (var i = 0; i < attrTextarea.length; i++) {
                    var nameAtt = attrTextarea[i].name;
                    //increment all array element name
                    var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
                    attrTextarea[i].name = repText;
                    $('#' + idText).find('.readonlyClass').prop('readonly', true);
                }
                attrTextarea.val(''); //value reset
                // end of  solving issue related textarea id auto increment related bug by ishrat

                attrSel.prop('selectedIndex', 0);  //selected index reset
                //Class change by btn-danger to btn-primary
                $("#proExportTbl").find('#' + idText).find('.addTableRows').removeClass('btn-primary').addClass('btn-danger')
                    .attr('onclick', 'removeTableRow("proExportTbl","' + idText + '")');
                $("#proExportTbl").find('#' + idText).find('.addTableRows > .fa').removeClass('fa-plus').addClass('fa-times');
                $("#proExportTbl").find('tr').last().attr('data-number', rowCount);

                $("#proExportTbl").find('#' + idText).find('.onlyNumber').on('keydown', function (e) {
                    //period decimal
                    if ((e.which >= 48 && e.which <= 57)
                        //numpad decimal
                        || (e.which >= 96 && e.which <= 105)
                        // Allow: backspace, delete, tab, escape, enter and .
                        || $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1
                        // Allow: Ctrl+A
                        || (e.keyCode == 65 && e.ctrlKey === true)
                        // Allow: Ctrl+C
                        || (e.keyCode == 67 && e.ctrlKey === true)
                        // Allow: Ctrl+V
                        || (e.keyCode == 86 && e.ctrlKey === true)
                        // Allow: Ctrl+X
                        || (e.keyCode == 88 && e.ctrlKey === true)
                        // Allow: home, end, left, right
                        || (e.keyCode >= 35 && e.keyCode <= 39)) {
                        var thisVal = $(this).val();
                        if (thisVal.indexOf(".") != -1 && e.key == '.') {
                            return false;
                        }
                        $(this).removeClass('error');
                        return true;
                    }
                    else {
                        $(this).addClass('error');
                        return false;
                    }
                });

                $("#proExportTbl").find('#' + idText).find('.pro_ext_desc_1st').val(production_desc_val);


                var xxx = document.getElementById('rowProDomesticCount0').cloneNode(true);
                xxx.id = "";
                xxx.style.display = "";
                var rowCountt = $('#proDomesticTbl').find('tr').length - 1;
                var lastTrr = $('#proDomesticTbl').find('tr').last().attr('data-number');
                if (lastTrr != '' && typeof lastTrr !== "undefined") {
                    rowCount = parseInt(lastTrr) + 1;
                }

                //Increment id
                var rowCoo = rowCountt;
                var idTextt = 'rowCountproDomesticTbl' + rowCountt;
                xxx.id = idTextt;
                $('#proDomesticTbl').append(xxx);

                var attrSel = $("#proDomesticTbl").find('#' + idTextt).find('select');
                //edited by ishrat to solve select box id auto increment related bug
                for (var i = 0; i < attrSel.length; i++) {
                    var nameAtt = attrSel[i].name;
                    var repText = nameAtt.replace('[0]', '[' + rowCoo + ']'); //increment all array element name
                    attrSel[i].name = repText;
                }
                attrSel.val(''); //value reset
                // end of  solving issue related select box id auto increment related bug by ishrat

                //get input elements
                var attrInput = $("#proDomesticTbl").find('#' + idTextt).find('input');
                for (var i = 0; i < attrInput.length; i++) {
                    var nameAtt = attrInput[i].name;
                    //increment all array element name
                    var repText = nameAtt.replace('[0]', '[' + rowCoo + ']');
                    attrInput[i].name = repText;
                }
                attrInput.val(''); //value reset
                //edited by ishrat to solve textarea id auto increment related bug
                //get textarea elements
                var attrTextarea = $("#proDomesticTbl").find('#' + idTextt).find('textarea');
                for (var i = 0; i < attrTextarea.length; i++) {
                    var nameAtt = attrTextarea[i].name;
                    //increment all array element name
                    var repText = nameAtt.replace('[0]', '[' + rowCoo + ']');
                    attrTextarea[i].name = repText;
                    $('#' + idText).find('.readonlyClass').prop('readonly', true);
                }
                attrTextarea.val(''); //value reset
                // end of  solving issue related textarea id auto increment related bug by ishrat

                attrSel.prop('selectedIndex', 0);  //selected index reset
                //Class change by btn-danger to btn-primary
                $("#proDomesticTbl").find('#' + idTextt).find('.addTableRows').removeClass('btn-primary').addClass('btn-danger')
                    .attr('onclick', 'removeTableRow("proDomesticTbl","' + idTextt + '")');
                $("#proDomesticTbl").find('#' + idTextt).find('.addTableRows > .fa').removeClass('fa-plus').addClass('fa-times');
                $("#proDomesticTbl").find('tr').last().attr('data-number', rowCount);

                $("#proDomesticTbl").find('#' + idTextt).find('.onlyNumber').on('keydown', function (e) {
                    //period decimal
                    if ((e.which >= 48 && e.which <= 57)
                        //numpad decimal
                        || (e.which >= 96 && e.which <= 105)
                        // Allow: backspace, delete, tab, escape, enter and .
                        || $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1
                        // Allow: Ctrl+A
                        || (e.keyCode == 65 && e.ctrlKey === true)
                        // Allow: Ctrl+C
                        || (e.keyCode == 67 && e.ctrlKey === true)
                        // Allow: Ctrl+V
                        || (e.keyCode == 86 && e.ctrlKey === true)
                        // Allow: Ctrl+X
                        || (e.keyCode == 88 && e.ctrlKey === true)
                        // Allow: home, end, left, right
                        || (e.keyCode >= 35 && e.keyCode <= 39)) {
                        var thisVal = $(this).val();
                        if (thisVal.indexOf(".") != -1 && e.key == '.') {
                            return false;
                        }
                        $(this).removeClass('error');
                        return true;
                    }
                    else {
                        $(this).addClass('error');
                        return false;
                    }
                });
                $("#proDomesticTbl").find('#' + idTextt).find('.pro_dom_desc_1st').val(production_desc_val);


            }

        } // end of addTableRow() function
        function removeTableRow(tableID, removeNum) {
            $('#' + tableID).find('#' + removeNum).remove();
        }

        $(".MoreInfo").click(function () {
            $(this).closest("tr").next().show();

        });


        var today = new Date();
        var yyyy = today.getFullYear();
        var mm = today.getMonth();
        var dd = today.getDate();
        $("body").on('focus', '.datepicker', function () {
            $(this).datetimepicker({
                viewMode: 'years',
                format: 'DD-MMM-YYYY',
                maxDate: today,
                minDate: '01/01/' + (yyyy - 50)
            });
        });


        // Process related script

        // Space rent calculation
        function CalculateSpaceRent(Advanced, monthlyRate, serviceCharge, spaceValue) {
            var givenValue = spaceValue;

            var threeMonthsAdvance = givenValue * 30 * 3;
            var monthlySpaceRent  = givenValue * 30;
            var spaceServiceCharge  = givenValue * 5;
            if((threeMonthsAdvance >= 0 ) && (monthlySpaceRent >= 0) && (spaceServiceCharge >= 0)){
                $('#' + Advanced).val(threeMonthsAdvance);
                $('#' + monthlyRate).val(monthlySpaceRent);
                $('#' + serviceCharge).val(spaceServiceCharge);
            }
        }
        $('.ad_desk_space').trigger('keyup');



        @if ($viewMode == 'on')

        $('#appClearenceForm :input').attr('disabled', true);
        $('#existing_challan_form :input').attr('disabled', true);
        $('#appClearenceForm :input[type=file]').hide();
        //        $('#appClearenceForm :input[type=file]').next().hide();
        $('.addTableRows').attr('disabled', 'true');

        $('#challanFormView :input').attr('disabled', true);
        $('#challanFormView :input[type=file]').hide();
        @endif // viewMode is on
    </script>

@endsection