@extends('layouts.admin')
@section('content')
    <?php

    $accessMode = ACL::getAccsessRight('loanLocator');
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
    <section class="content" id="LoanLocator">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
                    {{--{!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}--}}


                @if(ACL::isAllowed($accessMode, '-UP-') && $viewMode == 'on' && $hasDeskParkWisePermission)
                        @if(Request::segment(4) == ''){{-- 4= meeting module --}}
                        @include('ProcessPath::batch-process')
                        @endif
                    @endif
                    @if($viewMode == 'on')

                        @if(Request::segment(4)){{-- 4= meeting module --}}
                        <?php
                        $boardMeetingInfo = CommonFunction::getBoardMeetingInfo(Request::segment(3));
                        ?>
                        <div class="panel-body">
                            <div class="panel panel-info">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-4  col-md-offset-1 "
                                             style="border-bottom: 1px solid #c7bebe">
                                            <label style="font-size: 15px;color: #478fca;padding: 0px"
                                                   for="infrastructureReq" class="text-success col-md-6">Board Meeting
                                                Info
                                            </label>
                                            <br>
                                        </div>
                                        <div class="col-md-4 col-md-offset-1 " style="border-bottom: 1px solid #c7bebe">
                                            <label style="font-size: 15px;color: #478fca;">Agenda Info</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">

                                        <div class="col-md-4  col-md-offset-1">
                                            <table>
                                                <tr>
                                                    <td style="font-weight: bold;font-size: 14px; color: #5f5f5f;">Board
                                                        meeting no. :
                                                    </td>
                                                    <td style="font-size: 13px;color: #5f5f5f;">
                                                        &nbsp;&nbsp; {{$boardMeetingInfo['board_meeting_info']->meting_number}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold;font-size: 14px; color: #5f5f5f;">Board
                                                        Meeting Date:
                                                    </td>
                                                    <td style="font-size: 13px;color: #5f5f5f;">
                                                        &nbsp;&nbsp; {{$boardMeetingInfo['board_meeting_info']->meting_date}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold;font-size: 14px; color: #5f5f5f;">
                                                        Location:
                                                    </td>
                                                    <td style="font-size: 13px;color: #5f5f5f;">
                                                        &nbsp;&nbsp;{{$boardMeetingInfo['board_meeting_info']->location}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold;font-size: 14px; color: #5f5f5f;">
                                                        Status:
                                                    </td>
                                                    <td style="font-size: 13px;color: #5f5f5f;">
                                                        &nbsp;&nbsp;<button class="btn btn-xs btn-{{$boardMeetingInfo['board_meeting_info']->panel}}">{{$boardMeetingInfo['board_meeting_info']->status_name}}</button>
                                                    </td>
                                                </tr>
                                            </table>

                                        </div>
                                        <div class="col-md-4 col-md-offset-1">
                                            <table>
                                                <tr>
                                                    <td style="font-weight: bold; color: #5f5f5f;">Agenda Name: :</td>
                                                    <td style="font-size: 13px;color: #5f5f5f;">
                                                        &nbsp;&nbsp; {{$boardMeetingInfo['agenda_info']->name}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold; color: #5f5f5f;">Description:</td>
                                                    <td style="font-size: 13px;color: #5f5f5f;">
                                                        &nbsp;&nbsp; {{$boardMeetingInfo['agenda_info']->description}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold; color: #5f5f5f;">Process Type:</td>
                                                    <td style="font-size: 13px;color: #5f5f5f;">
                                                        &nbsp;&nbsp; {{$boardMeetingInfo['agenda_info']->process_name}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold; color: #5f5f5f;">Status:</td>
                                                    <td style="font-size: 13px;color: #5f5f5f;">
                                                        &nbsp;@if($boardMeetingInfo['agenda_info']->status_name == '')
                                                            <label class="btn btn-warning btn-xs">Pending</label>
                                                        @else
                                                            <label class="btn btn-{{$boardMeetingInfo['agenda_info']->panel}} btn-xs">{{$boardMeetingInfo['agenda_info']->status_name}}</label>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold; color: #5f5f5f;">Remarks:</td>
                                                    <td style="font-size: 13px;color: #5f5f5f;">
                                                        <span>{!! isset($boardMeetingInfo['agenda_info']->remarks)  !!}</span>
                                                    </td>
                                                </tr>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-info">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-9  col-md-offset-1 "
                                             style="border-bottom: 1px solid #c7bebe">
                                            <label
                                                   for="infrastructureReq" class="text-success col-md-6">
                                                <span style="font-size: 16px;color: #478fca;padding: 0px">Chairman Remarks:</span> {{isset($boardMeetingInfo['chairmanRemarks']->bm_remarks)?$boardMeetingInfo['chairmanRemarks']->bm_remarks:''}}
                                            </label>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="btn-group btn-breadcrumb steps">
                                    <?php
                                        $x = 0;
                                    ?>
                                    @foreach($statusName as $status)
                                        <?php
                                        if($status->id == $appInfo->status_id){
                                            $class = 'warning';
                                            $color = '#';
                                            $disable = 'disable';
                                        }elseif($status->id > $appInfo->status_id){
                                            $class = 'info';
                                            $color = '#';
                                            $disable = 'disaabled';
                                        }elseif($status->id ==''){

                                        }
                                        else{
//                                              $current_status = 'Shorfall';
                                            $class = 'success';
                                            $color = '#358e35';
                                            $disable = 'disable';

                                            ?>
                                            <?php
                                        }
                                        $x++;
                                        ?>
                                        <a href="#" class="btn btn-<?php echo $class;?>" style="background-color:{{$color}};">{{$status->status_name}}</a>

                                        {{--<a href="#" class="btn btn-danger">{{$status->status_name}}</a>--}}
                                        {{--<a href="#" class="btn btn-warning">{{$status->status_name}}</a>--}}
                                    @endforeach

                                </div>
                            </div>

                        </div>
                        <br>
                        @endif
                    @endif
                    <div class="panel panel-red" id="inputForm">
                        <div class="panel-heading">Application for Loan</div>
                        <div class="panel-body" style="margin:6px;">
                        @if($viewMode == 'on')
                        <section>
                        <div class="panel-body">
                            <div class="panel panel-primary">
                                <div class="panel-heading"></div>
                                <ol class="breadcrumb">
                                    <li><strong>Tracking no. : </strong>{{ $appInfo->tracking_no  }}</li>
                                    <li><strong> Date of Submission: </strong> {{ \App\Libraries\CommonFunction::formateDate($appInfo->date_of_submission)  }} </li>
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
                                        <a href="{{ url($appInfo->certificate) }}" class="btn show-in-view btn-xs btn-success"
                                           title="Download Approval Letter" target="_blank"> <i class="fa  fa-file-pdf-o"></i> <b>Download Certificate</b>
                                        </a>
                                        {{--@if(Auth::user()->user_type == '1x101')--}}
                                            {{--<a onclick="return confirm('Are you sure ?')" href="/space-allocation/discard-certificate/{{ Encryption::encodeId($appInfo->id)}}" class="btn show-in-view btn-xs btn-danger"--}}
                                               {{--title="Download Approval Letter"> <i class="fa  fa-trash"></i> <b>Discard Certificate</b></a>--}}
                                        {{--@endif--}}
                                        {{--@if(Auth::user()->user_type != '13x131')--}}
                                            {{--<a href="/space-allocation/project-cer-re-gen/{{ Encryption::encodeId($appInfo->id)}}" class="btn show-in-view btn-xs btn-warning"--}}
                                               {{--title="Download Approval Letter" target="_self"> <i class="fa  fa-file-pdf-o"></i> <b>Re-generate certificate</b></a>--}}
                                        {{--@endif--}}
                                        <?php } ?>


                                    </li>
                                </ol>
                            </div>
                        </div>
                            </section>
                        @endif
                            {!! Form::open(array('url' => '/loan-locator/add','method' => 'post','id' => 'appClearenceForm','role'=>'form','enctype'=>'multipart/form-data')) !!}
                            <input type="hidden" name="app_id" value="{{ Encryption::encodeId($appInfo->id)}}">
                            <input type="hidden" name="selected_file" id="selected_file">
                            <input type="hidden" name="validateFieldName" id="validateFieldName">
                            <input type="hidden" name="isRequired" id="isRequired">

                            <div class="panel panel-primary">
                                <div class="panel-heading"><strong>1. General Information</strong></div>
                                <div class="panel-body">

                                    <div class="form-group">
                                        <div class="row">

                                            <div class="col-md-6  {{$errors->has('applicant_name') ? 'has-error': ''}}">
                                                {!! Form::label('name','Name :',['class'=>'col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('applicant_name',$appInfo->applicant_name, ['maxlength'=>'80',
                                                    'class' => 'form-control input-sm  required']) !!}
                                                    {!! $errors->first('applicant_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('phone_number') ? 'has-error': ''}}">
                                                {!! Form::label('phone_number','Phone Number:',['class'=>'col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('phone_number',$appInfo->phone_number, ['maxlength'=>'80',
                                                    'class' => 'form-control number  input-sm  required']) !!}
                                                    {!! $errors->first('phone_number','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>


                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('father_name') ? 'has-error': ''}}">
                                                {!! Form::label('father_name','Father Name :',['class'=>'col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('father_name',$appInfo->father_name, ['maxlength'=>'80',
                                                    'class' => 'form-control input-sm  required']) !!}
                                                    {!! $errors->first('father_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('mother_name','Mother Name :',['class'=>'col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('mother_name',$appInfo->mother_name, ['maxlength'=>'80',
                                                    'class' => 'form-control input-sm  required']) !!}
                                                    {!! $errors->first('mother_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group" style=" ">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('email','Email :',['class'=>'col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('email',$appInfo->email, ['maxlength'=>'80',
                                                    'class' => 'form-control input-sm email required']) !!}
                                                    {!! $errors->first('email','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('gender') ? 'has-error': ''}}">
                                                {!! Form::label('gender','Gender :',['class'=>'col-md-5  required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('gender', ['1'=>'Male','2'=>'Female'], $appInfo->gender,['class' => 'form-control  input-sm required','placeholder'=>'Select One']) !!}
                                                    {!! $errors->first('gender','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>


                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <div class="row">

                                            <div class="col-md-6">
                                                {!! Form::label('national_id','National Id :',['class'=>'col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('national_id',$appInfo->national_id, ['maxlength'=>'80',
                                                    'class' => 'form-control input-sm onlyNumber  required']) !!}
                                                    {!! $errors->first('national_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                {!! Form::label('passport_no','Passport No:',['class'=>'col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('passport_no',$appInfo->passport_no, ['maxlength'=>'80',
                                                    'class' => 'form-control input-sm']) !!}
                                                    {!! $errors->first('passport_no','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Present Address:</legend>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        {{--<label for="pre_division" class="col-md-5 required-star">Present Address:</label>--}}
                                                        {!! Form::select('pre_division',$data['division'], $appInfo->pre_division,['class' => 'form-control input-sm required','id'=>'pr_division']) !!}
                                                    </div>
                                                    <div class="col-md-4">
                                                        {!! Form::select('pre_district', $data['district'], $appInfo->pre_district,['class' => 'form-control input-sm required','placeholder'=>'Select District','id'=>'pre_distract']) !!}
                                                    </div>
                                                    <div class="col-sm-4">
                                                        {!! Form::select('pre_thana', $data['thana'], $appInfo->pre_thana,['class' => 'form-control DivisionCountry  input-sm required','placeholder'=>'Select Thana','id'=>'pre_thana']) !!}
                                                    </div>

                                                </div>
                                            </div>

                                        </fieldset>
                                    </div>
                                    <div class="">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Permanent Address:</legend>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        {!! Form::select('per_division',$data['division'], $appInfo->per_division,['class' => 'form-control   input-sm required','id'=>'per_division']) !!}

                                                    </div>
                                                    <div class="col-md-4">
                                                        {{--<label for="invoice_ref_no" class="col-md-5 required-star">Present Address. :</label>--}}
                                                        {!! Form::select('per_district',$data['district'], $appInfo->per_district,['class' => 'form-control input-sm required','id'=>'per_distract']) !!}
                                                    </div>
                                                    <div class="col-sm-4">
                                                        {!! Form::select('per_thana', $data['thana'], $appInfo->per_thana,['class' => 'form-control  input-sm required','placeholder'=>'Select Thana','id'=>'per_thana']) !!}
                                                    </div>

                                                </div>
                                            </div>

                                        </fieldset>
                                    </div>

                                    <div class="clearfix"></div>
                                </div><!--/col-md-12-->
                                <!--/panel-body-->
                            </div> <!--/panel-->




                            <div class="panel panel-primary">
                                <div class="panel-heading"><strong>2. Loan Information</strong></div>
                                <div class="panel-body">

                                    <div class="col-md-12">

                                        <div class="form-group" style="">
                                            <div class="row">

                                                <div class="col-md-6">
                                                    {!! Form::label('loan_type','Loan Type:',['class'=>'col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('loan_type', $data['loanType'], $appInfo->loan_type,['class' => 'form-control loan_type  input-sm required','id'=>'loan_type']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    {!! Form::label('amount_of_money','Amount of Money:',['class'=>'col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('amount_of_money',$appInfo->amount_of_money, ['maxlength'=>'80',
                                                        'class' => 'form-control onlyNumber required input-sm']) !!}
                                                        {!! $errors->first('amount_of_money','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-group" style="">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {!! Form::label('bank_name','Bank Name:',['class'=>'col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('bank_name', $data['bank'],  $appInfo->bank_id,['class' => 'form-control DivisionCountry  input-sm required','id'=>'bank']) !!}
                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    {!! Form::label('branch_name','Branch Name:',['class'=>'col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('branch_name',  $data['branch'], $appInfo->branch_id,['class' => 'form-control DivisionCountry  input-sm required','placeholder'=>'Select Branch','id'=>'branch']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="clearfix"></div>
                                        <p></p>

                                    </div><!--/col-md-12-->
                                </div> <!--/panel-body-->
                            </div>


                            <div class="panel panel-primary">
                                <div class="panel-heading"><strong>3. Required Documents for attachment</strong></div>
                                <div class="panel-body">
                                    <div class="table-responsive">
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
                                            @foreach($data['document'] as $row)
                                                <tr>
                                                    <td>
                                                        <div align="center">{!! $i !!}<?php echo $row->doc_priority == "1" ? "<span class='required-star'></span>" : ""; ?></div>
                                                    </td>
                                                    <td colspan="6">{!!  $row->doc_name !!}</td>
                                                    <td colspan="2">
                                                        <input name="document_id_<?php echo $row->doc_id; ?>"
                                                               type="hidden"
                                                               value="{{(!empty($clrDocuments[$row->doc_id]['doucument_id']) ? $clrDocuments[$row->doc_id]['doucument_id'] : '')}}">
                                                        <input type="hidden" value="{!!  $row->doc_name !!}"
                                                               id="doc_name_<?php echo $row->doc_id; ?>"
                                                               name="doc_name_<?php echo $row->doc_id; ?>"/>
                                                        <input name="file<?php echo $row->doc_id; ?>"
                                                               <?php if (empty($clrDocuments[$row->doc_id]['file']) && empty($allRequestVal["file$row->doc_id"]) && $row->doc_priority == "1") {
                                                                   echo "class='required'";
                                                               } ?>
                                                               id="file<?php echo $row->doc_id; ?>" type="file"
                                                               size="20"
                                                               onchange="uploadDocument('preview_<?php echo $row->doc_id; ?>', this.id, 'validate_field_<?php echo $row->doc_id; ?>', '<?php echo $row->doc_priority; ?>')"/>

                                                        @if($row->additional_field == 1)
                                                            <table>
                                                                <tr>
                                                                    <td>Other file Name :</td>
                                                                    <td><input maxlength="64"
                                                                               class="form-control input-sm <?php if ($row->doc_priority == "1") {
                                                                                   echo 'required';
                                                                               } ?>"
                                                                               name="other_doc_name_<?php echo $row->doc_id; ?>"
                                                                               type="text"
                                                                               value="{{(!empty($clrDocuments[$row->doc_id]['doc_name']) ? $clrDocuments[$row->doc_id]['doc_name'] : '')}}">
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        @endif


                                                        @if(!empty($clrDocuments[$row->doc_id]['file']))

                                                            <div class="save_file saved_file_{{$row->doc_id}}">
                                                                <a target="_blank"
                                                                   class="documentUrl btn btn-success btn-xs" href="{{URL::to('/uploads/'.(!empty($clrDocuments[$row->doc_id]['file']) ?
                                                                    $clrDocuments[$row->doc_id]['file'] : ''))}}"
                                                                   title="{{$row->doc_name}}">
                                                                    <b><i class="fa fa-download"></i> Download</b>
                                                                </a>

                                                                <?php if(!empty($appInfo) && Auth::user()->id == $appInfo->created_by && $viewMode != 'on') {?>
                                                                <a style="line-height: 2.1em" href="javascript:void(0)"
                                                                   onclick="ConfirmDeleteFile({{ $row->doc_id }})">
                                                                    <span class="btn btn-xs btn-danger"><i
                                                                                class="fa fa-times"></i></span>
                                                                </a>
                                                                <?php } ?>
                                                            </div>
                                                        @else
                                                            {{--<span>No Attachment</span>--}}
                                                        @endif


                                                        <div id="preview_<?php echo $row->doc_id; ?>">
                                                            <input type="hidden"
                                                                   value="<?php echo !empty($clrDocuments[$row->doc_id]['file']) ?
                                                                       $clrDocuments[$row->doc_id]['file'] : ''?>"
                                                                   id="validate_field_<?php echo $row->doc_id; ?>"
                                                                   name="validate_field_<?php echo $row->doc_id; ?>"
                                                                   class="<?php echo $row->doc_priority == "1" ? "required" : '';  ?>"/>
                                                        </div>


                                                        @if(!empty($allRequestVal["file$row->doc_id"]))
                                                            <label id="label_file{{$row->doc_id}}"><b>File: {{$allRequestVal["file$row->doc_id"]}}</b></label>
                                                            <input type="hidden" class="required"
                                                                   value="{{$allRequestVal["validate_field_".$row->doc_id]}}"
                                                                   id="validate_field_{{$row->doc_id}}"
                                                                   name="validate_field_{{$row->doc_id}}">
                                                        @endif

                                                    </td>
                                                </tr>
                                                <?php $i++; ?>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div><!-- /.table-responsive -->
                                </div><!-- /.panel-body -->
                            </div>


                            <div class="panel panel-primary hiddenDiv">
                                <div class="panel-heading"><strong>Terms and Conditions</strong></div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12" style="margin: 12px 0;">
                                            <input {{ ($appInfo->acceptTerms == 1)?'checked':'' }} id="acceptTerms-2"
                                                   name="acceptTerms" type="checkbox"
                                                   class="required col-md-1 text-left no_remove"
                                                   style="width:3%;">
                                            {{--<input id="acceptTerms-2" name="acceptTerms" type="checkbox" class="required col-md-1 col-xs-1 col-sm-1 text-left" style="width: 3%; margin-left: 2px;">--}}
                                            <label for="acceptTerms-2" class="col-md-11 col-xs-11 col-sm-11 text-left required-star">The above information is correct.</label>
                                        </div>
                                    </div>
                                </div>
                            </div> <!--/ main red panel with margin-->

                            <div style="margin:6px;">
                                <div class="row">

                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        @if($appInfo->status_id != 5)
                                        <button type="submit" class="btn btn-primary btn-md cancel" value="draft" name="actionBtn">Save as Draft</button>
                                        @endif
                                    </div>

                                    <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                                        <button type="submit" class="btn btn-primary btn-md" value="save" name="submitInsert">Submit
                                        </button>
                                    </div>
                                    <!-- /.form end -->
                                </div>


                            </div>
                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
                        @if(in_array(Auth::user()->user_type, array('1x101','3x303','4x404')) && Auth::user()->desk_id !=1 )
                            @include('ProcessPath::application-history')
                        @endif
                </div>
            </div>
    </section>


@endsection
@section('footer-script')

    <script type="text/javascript">
        $("#appClearenceForm").validate();
        $("#pr_division").change(function () {
            var divisionId = $(this).val();
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
                    $("#pre_distract").html(option);
                    $(self).next().hide();
                }
            });
        });
        // get thana list by district id for Office Address

        $("#pre_distract").change(function () {
            var self = $(this);
            var districtId = $(this).val();
            if (districtId !== '') {
                $(this).after('<span class="loading_data">Loading...</span>');
                $("#loaderImg").html("<img style='margin-top: -15px;' src='<?php echo url(); ?>/public/assets/images/ajax-loader.gif' alt='loading' />");
                $.ajax({
                    type: "GET",
                    url: "<?php echo url(); ?>/users/get-thana-by-district-id",
                    data: {
                        districtId: districtId
                    },
                    success: function (response) {
                        var option = '<option value="">Select One</option>';
                        if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
                                // for edit list , applicant thana
                                {{--if (id == '{{$users->thana}}'){--}}
                                {{--option += '<option value="'+ id + '" selected>' + value + '</option>';--}}
                                {{--}--}}
                                //                                    else {
                                option += '<option value="' + id + '">' + value + '</option>';
//                                    }
                            });
                        }
                        $("#pre_thana").html(option);
                        self.next().hide();
                    }
                });
            }
        });



        $("#per_division").change(function () {
            var divisionId = $(this).val();
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
                    $("#per_distract").html(option);
                    $(self).next().hide();
                }
            });
        });
        $("#per_distract").change(function () {
            var self = $(this);
            var districtId = $(this).val();
            if (districtId !== '') {
                $(this).after('<span class="loading_data">Loading...</span>');
                $("#loaderImg").html("<img style='margin-top: -15px;' src='<?php echo url(); ?>/public/assets/images/ajax-loader.gif' alt='loading' />");
                $.ajax({
                    type: "GET",
                    url: "<?php echo url(); ?>/users/get-thana-by-district-id",
                    data: {
                        districtId: districtId
                    },
                    success: function (response) {
                        var option = '<option value="">Select One</option>';
                        if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
                                option += '<option value="' + id + '">' + value + '</option>';
                            });
                        }
                        $("#per_thana").html(option);
                        self.next().hide();
                    }
                });
            }
        });



        $("#bank").change(function () {
            var bank_id = $(this).val();
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "<?php echo url(); ?>/loan-locator/get-branch-by-bank",
                data: {
                    bank_id: bank_id
                },
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            option += '<option value="' + id + '">' + value + '</option>';
                        });
                    }
                    $("#branch").html(option);
                    $(self).next().hide();
                }
            });

        });
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
                var action = "{{url('/loan-locator/upload-document')}}";
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

        <?php if ($viewMode == 'on') { ?>
        $(".MoreInfo").click(function () {
            $(this).closest("tr").next().show();

        });

        $('#inputForm select').each(function (index) {
            var text = $(this).find('option:selected').text();
            var id = $(this).attr("id");
            var val = $(this).val();
            $('#' + id + ' option:selected').replaceWith("<option value='" + val + "' selected>" + text + "</option>");
        });
        $("#inputForm :input[type=text]").each(function (index) {
            $(this).attr("value", $(this).val());
        });
        $("#inputForm textarea").each(function (index) {
            $(this).text($(this).val());
        });

        $("#inputForm select").css({
            "border": "none",
            "background": "#fff",
            "pointer-events": "none",
            "box-shadow": "none",
            "-webkit-appearance": "none",
            "-moz-appearance": "none",
            "appearance": "none"
        });

        $("#inputForm .actions").css({"display": "none"});
        $("#inputForm .draft").css({"display": "none"});
        $("#inputForm .title ").css({"display": "none"});
        //document.getElementById("previewDiv").innerHTML = document.getElementById("projectClearanceForm").innerHTML;

        $('#inputForm #showPreview').remove();
        $('#inputForm #save_btn').remove();
        $('#inputForm #save_draft_btn').remove();
        $('#inputForm .stepHeader, #inputForm .calender-icon,#inputForm .pss-error,#inputForm .hiddenDiv, #inputForm .input-group-addon').remove();
        $('#inputForm .required-star').removeClass('required-star');
        $('#inputForm input[type=hidden], #inputForm input[type=file]').remove();
        $('#inputForm .panel-orange > .panel-heading').css('margin-bottom', '10px');
        $('#invalidInst').html('');

        $('#inputForm').find('input:not(:checked),textarea').each(function () {
            if (this.value != '') {
                var displayOp = ''; //display:block
            } else {
                var displayOp = 'display:none';
            }

            if ($(this).hasClass("onlyNumber") && !$(this).hasClass("nocomma")) {
                var thisVal = commaSeparateNumber(this.value);
                $(this).replaceWith("<span class='onlyNumber " + this.className +
                    "' style='background-color:#ddd !important;border-radius:3px;padding:6px; height:auto; margin-bottom:2px;"
                    + displayOp + "'>" + thisVal + "</span>");
            } else {
                $(this).replaceWith("<span class='" + this.className + "' style='background-color:#ddd;padding:6px; height:auto; margin-bottom:2px;"
                    + displayOp + "'>" + this.value + "</span>");
            }
        });

        $('#inputForm').find('textarea').each(function () {
            var displayOp = '';
            if (this.value != '') {
                displayOp = ''; //display:block
            } else {
                displayOp = 'display:none';
            }
            $(this).replaceWith("<span class='" + this.className + "' style='background-color:#ddd;height:auto;padding:6px;margin-bottom:2px;"
                + displayOp + "'>" + this.value + "</span>");
        });


        $('#inputForm .btn').not('.show-in-view,.documentUrl').each(function () {
            $(this).replaceWith("");
        });

        $('#inputForm').find('input[type=radio]').each(function () {
            jQuery(this).attr('disabled', 'disabled');
        });

        $("#inputForm select").replaceWith(function () {
            var selectedText = $(this).find('option:selected').text().trim();
            var displayOp = '';
            if (selectedText != '' && selectedText != 'Select One') {
                displayOp = ''; //display:block
            } else {
                displayOp = 'display:none';
            }

            return "<span class='" + this.className + "' style='background-color:#ddd;height:auto;padding:6px;margin-bottom:2px;"
                + displayOp + "'>" + selectedText + "</span>";
        });

        $("#inputForm select").replaceWith(function () {
            var selectedText = $(this).find('option:selected').text();
            return "<span style='background-color:#ddd;width:68%; height:auto; margin-bottom:2px;padding:6px;display:block;'>"
                + selectedText + "</span>";
        });

        function commaSeparateNumber(val) {
            while (/(\d+)(\d{3})/.test(val.toString())) {
                val = val.toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2');
            }
            return val;
        }

        <?php } ?> /* viewMode is on */
    </script>
@endsection