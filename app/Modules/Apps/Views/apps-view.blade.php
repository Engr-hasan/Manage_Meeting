@extends('layouts.admin')
@section('content')
    <style>
        #app-form label.error {display: none !important; }
    </style>
    <section class="content" id="inputForm">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <strong>Application</strong>
                        </div>
                        <div class="panel-body">
                            <fieldset>
                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>1. Applicant Information</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group clearfix">
                                            <div class="col-md-7">
                                                {!! Form::label('application_title','Title of the Application :',['class'=>'col-md-5 text-left required-star text-right']) !!}
                                                <div class="col-md-7">
                                                    {!! $applicationInfo->application_title !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group clearfix">
                                            <div class="col-md-7">
                                                {!! Form::label('applicant_name','Applicant Name :',['class'=>'col-md-5 text-left required-star text-right']) !!}
                                                <div class="col-md-7">
                                                    {!! $applicationInfo->applicant_name !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group clearfix">
                                            <div class="col-md-7">
                                                {!! Form::label('applicant_father_name','Father Name :',['class'=>'col-md-5 text-left required-star text-right']) !!}
                                                <div class="col-md-7">
                                                    {!! $applicationInfo->applicant_father_name !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group clearfix">
                                            <div class="col-md-7">
                                                {!! Form::label('applicant_mother_name','Mother Name :',['class'=>'col-md-5 text-left required-star text-right']) !!}
                                                <div class="col-md-7">
                                                    {!! $applicationInfo->applicant_mother_name !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group clearfix">
                                            <div class="col-md-7">
                                                {!! Form::label('agency_id','Agency Name :',['class'=>'col-md-5 text-left required-star text-right']) !!}
                                                <div class="col-md-7">
                                                    {!! $agency[$applicationInfo->agency_id] !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>2. Applicant Address</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group clearfix">
                                            <div class="col-md-7">
                                                {!! Form::label('present_address','Present Address :',['class'=>'col-md-5 text-left required-star text-right']) !!}
                                                <div class="col-md-7">
                                                    {!! $applicationInfo->present_address !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group clearfix">
                                            <div class="col-md-7">
                                                {!! Form::label('permanent_address','Permanent Address :',['class'=>'col-md-5 text-left required-star text-right']) !!}
                                                <div class="col-md-7">
                                                    {!! $applicationInfo->permanent_address !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>3. Required Documents for attachment</strong></div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th colspan="6">Required Attachments</th>
                                                    <th colspan="2">Attached PDF file
                                                                <span onmouseover="toolTipFunction()" data-toggle="tooltip" title="Attached PDF file (Each File Maximum size 3MB)!">
                                                                    <i class="fa fa-question-circle" aria-hidden="true"></i>
                                                                </span>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php $i = 1; ?>
                                                @foreach($document as $row)
                                                    <tr>
                                                        <td><div align="center">{!! $i !!}<?php echo $row->doc_priority == "1" ? "<span class='required-star'></span>" : ""; ?></div></td>
                                                        <td colspan="6">{!!  $row->doc_name !!}</td>
                                                        <td colspan="2">
                                                            @if(!empty($clrDocuments[$row->doc_id]['file']))
                                                                <div class="save_file">
                                                                    <a target="_blank" class="documentUrl" title="{{$row->doc_name}}"
                                                                       href="{{URL::to('/uploads/'.(!empty($clrDocuments[$row->doc_id]['file']) ? $clrDocuments[$row->doc_id]['file'] : ''))}}">
                                                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                                        <?php $file_name = explode('/',$clrDocuments[$row->doc_id]['file']); echo end($file_name);  ?></a>
                                                                </div>
                                                            @endif

                                                            <div id="preview_<?php echo $row->doc_id; ?>">
                                                                <input type="hidden" <?php echo $row->doc_priority == "1" ? "class='required'" : ""; ?> value=""
                                                                       id="validate_field_<?php echo $row->doc_id; ?>" name="validate_field_<?php echo $row->doc_id; ?>" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php $i++; ?>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                    </div>
                </div>
                 @if (in_array(Auth::user()->user_type, array('1x101', '3x301'))) <!-- for system admin and administrative official -->
                    @include('Apps::apps_history')
                @endif
            </div>
        </div>
    </section>
@endsection

@section('footer-script')
    <script type="text/javascript">
        </script>
@endsection <!--- footer-script--->