@extends('layouts.admin')
@section('content')
    <section class="content" id="LoanLocator">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">


                    <div class="panel panel-red" id="inputForm">
                        <div class="panel-heading">Application for Loan</div>
                        <div class="panel-body" style="margin:6px;">
                            {!! Form::open(array('url' => '/loan-locator/add','method' => 'post','id' => 'appClearenceForm','role'=>'form','enctype'=>'multipart/form-data')) !!}
                            <input type="hidden" name="app_id" value="">
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
                                                    {!! Form::text('applicant_name','', ['maxlength'=>'80',
                                                    'class' => 'form-control input-sm  required']) !!}
                                                    {!! $errors->first('applicant_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('phone_number') ? 'has-error': ''}}">
                                                {!! Form::label('phone_number','Phone Number:',['class'=>'col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('phone_number','', ['maxlength'=>'80',
                                                    'class' => 'form-control number onlyNumber input-sm  required']) !!}
                                                    {!! $errors->first('phone_number','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>


                                        </div>
                                    </div>

                                    <div class="form-group" style=" ">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('father_name') ? 'has-error': ''}}">
                                                {!! Form::label('father_name','Father Name :',['class'=>'col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('father_name','', ['maxlength'=>'80',
                                                    'class' => 'form-control input-sm  required']) !!}
                                                    {!! $errors->first('father_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('mother_name','Mother Name :',['class'=>'col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('mother_name','', ['maxlength'=>'80',
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
                                                    {!! Form::text('email','', ['maxlength'=>'80',
                                                    'class' => 'form-control input-sm  email required']) !!}
                                                    {!! $errors->first('email','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('gender') ? 'has-error': ''}}">
                                                {!! Form::label('gender','Gender :',['class'=>'col-md-5  required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('gender', ['1'=>'Male','2'=>'Female'], '',['class' => 'form-control  input-sm required','placeholder'=>'Select One']) !!}
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
                                                    {!! Form::text('national_id','', ['maxlength'=>'80',
                                                    'class' => 'form-control input-sm  required']) !!}
                                                    {!! $errors->first('national_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                {!! Form::label('passport_no','Passport No:',['class'=>'col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('passport_no','', ['maxlength'=>'80',
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
                                                        {!! Form::select('pre_division',$data['division'], '',['class' => 'form-control input-sm required','id'=>'pr_division']) !!}
                                                    </div>
                                                    <div class="col-md-4">
                                                        {!! Form::select('pre_district', [], '',['class' => 'form-control input-sm required','placeholder'=>'Select District','id'=>'pre_district']) !!}
                                                    </div>
                                                    <div class="col-sm-4">
                                                        {!! Form::select('pre_thana', [], '',['class' => 'form-control DivisionCountry  input-sm required','placeholder'=>'Select Thana','id'=>'pre_thana']) !!}
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
                                                        {!! Form::select('per_division',$data['division'], '',['class' => 'form-control   input-sm required','placeholder'=>'Select Division','id'=>'per_division']) !!}

                                                    </div>
                                                    <div class="col-md-4">
                                                        {{--<label for="invoice_ref_no" class="col-md-5 required-star">Present Address. :</label>--}}
                                                        {!! Form::select('per_district', [], '',['class' => 'form-control input-sm required','placeholder'=>'Select District','id'=>'per_district']) !!}
                                                    </div>
                                                    <div class="col-sm-4">
                                                        {!! Form::select('per_thana', [], '',['class' => 'form-control  input-sm required','placeholder'=>'Select Thana','id'=>'per_thana']) !!}
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
                                                        {!! Form::select('loan_type', $data['loanType'],  '',['class' => 'form-control loan_type  input-sm required','id'=>'loan_type']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    {!! Form::label('amount_of_money','Amount of Money:',['class'=>'col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('amount_of_money','', ['maxlength'=>'80',
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
                                                        {!! Form::select('bank_name', $data['bank'],  '',['class' => 'form-control DivisionCountry  input-sm required','id'=>'bank']) !!}
                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    {!! Form::label('branch_name','Branch Name:',['class'=>'col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('branch_name', [], '',['class' => 'form-control DivisionCountry  input-sm required','placeholder'=>'Select Branch','id'=>'branch']) !!}
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
                                <div class="panel-heading"><strong>3. Required Documents</strong></div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover ">
                                            <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th colspan="6">Documents' Name</th>
                                                <th colspan="2">Attached PDF file (Max File Size is 3MB)
                                                    <span onmouseover="toolTipFunction()" data-toggle="tooltip"
                                                          title="Attached PDF file (Each File Maximum size 3MB)!">
                                                        <i class="fa fa-question-circle" aria-hidden="true"></i></span>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $i = 1; ?>
                                            @foreach($data['document'] as $row)
                                                <tr>
                                                    <td>
                                                        <div align="left">{!! $i !!}<?php echo $row->doc_priority == "1" ? "<span class='required-star'></span>" : ""; ?></div>
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
                                                                <a target="_blank" class="documentUrl" href="{{URL::to('/uploads/'.(!empty($clrDocuments[$row->doc_id]['file']) ?
                                                                    $clrDocuments[$row->doc_id]['file'] : ''))}}"
                                                                   title="{{$row->doc_name}}">
                                                                    <i class="fa fa-file-pdf-o"
                                                                       aria-hidden="true"></i> <?php $file_name = explode('/', $clrDocuments[$row->doc_id]['file']); echo end($file_name); ?>
                                                                </a>

                                                                <?php if(!empty($alreadyExistApplicant) && Auth::user()->id == $alreadyExistApplicant->created_by && $viewMode != 'on') {?>
                                                                <a href="javascript:void(0)"
                                                                   onclick="ConfirmDeleteFile({{ $row->doc_id }})">
                                                                    <span class="btn btn-xs btn-danger"><i
                                                                                class="fa fa-times"></i></span>
                                                                </a>
                                                                <?php } ?>
                                                            </div>
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
                                            <input id="acceptTerms-2" name="acceptTerms" type="checkbox"
                                                   class="required col-md-1 col-xs-1 col-sm-1 text-left"
                                                   style="width: 3%; margin-left: 2px;">
                                            <label for="acceptTerms-2"
                                                   class="col-md-11 col-xs-11 col-sm-11 text-left required-star">The
                                                above information is correct.</label>
                                        </div>
                                    </div>
                                </div>
                            </div> <!--/ main red panel with margin-->

                            <div style="margin:6px;">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <button type="submit" class="btn btn-primary btn-md cancel" value="draft"
                                                name="actionBtn">Save as Draft
                                        </button>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                                        <button type="submit" class="btn btn-primary btn-md" value="save"
                                                name="submitInsert">Submit
                                        </button>
                                    </div>
                                    <!-- /.form end -->
                                </div>


                            </div>
                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
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
                    $("#pre_district").html(option);
                    $(self).next().hide();
                }
            });
        });
        // get thana list by district id for Office Address

        $("#pre_district").change(function () {
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
                    $("#per_district").html(option);
                    $(self).next().hide();
                }
            });
        });
        $("#per_district").change(function () {
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

    </script>
@endsection