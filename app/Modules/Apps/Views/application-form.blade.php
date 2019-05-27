@extends('layouts.admin')
@section('content')
    <style>
        #app-form label.error {display: none !important; }
    </style>
    <section class="content" id="inputForm">
        <div class="col-md-12">
            @if(Session::has('success'))
                <div class="alert alert-success">
                    {!!Session::get('success') !!}
                </div>
            @endif
            @if(Session::has('error'))
                <div class="alert alert-danger">
                    {!! Session::get('error') !!}
                </div>
            @endif
        </div>
        <div class="col-md-12" style="padding:0px;">
            <div class="box">
                <div class="box-body">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <strong>Application</strong>
                            </div>
                            {!! Form::open(array('url' => 'application/store', 'method' => 'post', 'files' => true, 'role' => 'form', 'id'=> 'app-form')) !!}
                            <input type ="hidden" name="app_id" value="{{(isset($alreadyExistApplicant->application_id) ? App\Libraries\Encryption::encodeId($alreadyExistApplicant->application_id) : '')}}">
                            <input type="hidden" name="selected_file" id="selected_file" />
                            <input type="hidden" name="validateFieldName" id="validateFieldName" />
                            <input type="hidden" name="isRequired" id="isRequired" />
                            <div class="panel-body">
                                <h3 class="text-center">Applicant Information (Part A)</h3>
                                <fieldset>
                                    <div class="panel panel-info">
                                        <div class="panel-heading"><strong>1. Applicant Information</strong></div>
                                        <div class="panel-body">
                                            <div class="form-group clearfix">
                                                <div class="col-md-7 {{$errors->has('application_title') ? 'has-error': ''}}">
                                                    {!! Form::label('application_title','Title of the Application :',['class'=>'col-md-5 text-left required-star text-right']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('application_title','', ['maxlength'=>'64', 'class' => 'form-control input-sm required']) !!}
                                                        {!! $errors->first('application_title','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group clearfix">
                                                <div class="col-md-7 {{$errors->has('applicant_name') ? 'has-error': ''}}">
                                                    {!! Form::label('applicant_name','Applicant Name :',['class'=>'col-md-5 text-left required-star text-right']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('applicant_name','', ['maxlength'=>'64', 'class' => 'form-control input-sm required']) !!}
                                                        {!! $errors->first('applicant_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group clearfix">
                                                <div class="col-md-7 {{$errors->has('applicant_father_name') ? 'has-error': ''}}">
                                                    {!! Form::label('applicant_father_name','Father Name :',['class'=>'col-md-5 text-left required-star text-right']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('applicant_father_name','', ['maxlength'=>'64', 'class' => 'form-control input-sm required']) !!}
                                                        {!! $errors->first('applicant_father_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group clearfix">
                                                <div class="col-md-7 {{$errors->has('applicant_mother_name') ? 'has-error': ''}}">
                                                    {!! Form::label('applicant_mother_name','Mother Name :',['class'=>'col-md-5 text-left required-star text-right']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('applicant_mother_name','', ['maxlength'=>'64', 'class' => 'form-control input-sm required']) !!}
                                                        {!! $errors->first('applicant_mother_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group clearfix">
                                                <div class="col-md-7 {{$errors->has('agency_id') ? 'has-error': ''}}">
                                                    {!! Form::label('agency_id','Agency Name :',['class'=>'col-md-5 text-left required-star text-right']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('agency_id', $agency, '', ['maxlength'=>'64', 'class' => 'form-control input-sm required']) !!}
                                                        {!! $errors->first('agency_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <h3 class="text-center">Applicant Address (Part B)</h3>
                                <fieldset>
                                    <div class="panel panel-info">
                                        <div class="panel-heading"><strong>2. Applicant Address</strong></div>
                                        <div class="panel-body">
                                            <div class="form-group clearfix">
                                                    <div class="col-md-7 {{$errors->has('present_address') ? 'has-error': ''}}">
                                                        {!! Form::label('present_address','Present Address :',['class'=>'col-md-5 text-left required-star text-right']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::textarea('present_address','', ['maxlength'=>'64', 'class' => 'form-control input-sm required', 'size' => '10x5']) !!}
                                                            {!! $errors->first('present_address','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="form-group clearfix">
                                                    <div class="col-md-7 {{$errors->has('permanent_address') ? 'has-error': ''}}">
                                                        {!! Form::label('permanent_address','Permanent Address :',['class'=>'col-md-5 text-left required-star text-right']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::textarea('permanent_address','', ['maxlength'=>'64', 'class' => 'form-control input-sm required', 'size' => '10x5']) !!}
                                                            {!! $errors->first('permanent_address','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <h3 class="text-center">Required Documents (Part C)</h3>
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
                                                                    <input name="document_id_<?php echo $row->doc_id; ?>" type="hidden" value="{{(!empty($clrDocuments[$row->doc_id]['doucument_id']) ? $clrDocuments[$row->doc_id]['doucument_id'] : '')}}">
                                                                    <input type="hidden" value="{!!  $row->doc_name !!}" id="doc_name_<?php echo $row->doc_id; ?>"
                                                                           name="doc_name_<?php echo $row->doc_id; ?>" />
                                                                    <input name="file<?php echo $row->doc_id; ?>" id="file<?php echo $row->doc_id; ?>" type="file" size="20"
                                                                           <?php if (empty($clrDocuments[$row->doc_id]['file'])){
                                                                               echo $row->doc_priority == "1" ? "class='required'" : "";  }?>
                                                                           onchange="uploadDocument('preview_<?php echo $row->doc_id; ?>', this.id, 'validate_field_<?php echo $row->doc_id; ?>', <?php echo $row->doc_priority; ?>)"/>
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
                                <h3>Submit</h3>
                                <fieldset>
                                    <input id="acceptTerms" name="acceptTerms" type="checkbox" class="required"> <label for="acceptTerms">I agree with the Terms and Conditions.</label>
                                </fieldset>
                                <input type="submit" class="btn btn-primary btn-md cancel" value="Save As Draft" name="sv_draft">
                            </div>
                            {!! Form::close() !!}
                        </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('footer-script')
    <link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
    <script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
    <script>
        function uploadDocument(targets, id, vField, isRequired) {
            var inputFile = $("#" + id).val();
            if (inputFile == ''){
                $("#" + id).html('');
                document.getElementById("isRequired").value = '';
                document.getElementById("selected_file").value = '';
                document.getElementById("validateFieldName").value = '';
                document.getElementById(targets).innerHTML = '<input type="hidden" class="required" value="" id="' + vField + '" name="' + vField + '">';
                if ($('#label_' + id).length) $('#label_' + id).remove();
                return false;
            }

            try{
                document.getElementById("isRequired").value = isRequired;
                document.getElementById("selected_file").value = id;
                document.getElementById("validateFieldName").value = vField;
                document.getElementById(targets).style.color = "red";
                var action = "{{url('/application/upload-document')}}";
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
                    url:action,
                    dataType: 'text', // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    success: function(response){
                        $('#' + targets).html(response);
                        var fileNameArr = inputFile.split("\\");
                        var l = fileNameArr.length;
                        if ($('#label_' + id).length)
                            $('#label_' + id).remove();
                        var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                        $("#" + id).after(newInput);
                        //check valid data
                        var validate_field = $('#' + vField).val();
                        if (validate_field == ''){
                            document.getElementById(id).value = '';
                        }
                    }
                });
            } catch (err) {
                document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
            }
        } // end of uploadDocument function
        $(document).ready(function () {
            var form = $("#app-form").show();
            form.validate({
                errorPlacement: function errorPlacement(error, element) { element.before(error); },
                rules: {

                }
            });
            form.children("div").steps({
                headerTag: "h3",
                bodyTag: "fieldset",
                transitionEffect: "slideLeft",
                onStepChanging: function (event, currentIndex, newIndex)
                {
                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                },
                onFinishing: function (event, currentIndex)
                {
                    form.validate().settings.ignore = ":disabled";
                    return form.valid();
                },
                onFinished: function (event, currentIndex)
                {
                    //alert("Submitted!");
                }
            });
            var popupWindow = null;
            $('.finish').on('click', function (e) {
                if ($('#acceptTerms').is(":checked")){
                    $('#acceptTerms').removeClass('error');
                    $('#home').css({"display": "none"});
                    popupWindow = window.open('<?php echo URL::to('/application/preview'); ?>', 'Sample', '');
                } else {
                    $('#acceptTerms').addClass('error');
                    return false;
                }
            });
        });

        function toolTipFunction() {
            $('[data-toggle="tooltip"]').tooltip();
        }
    </script>
@endsection