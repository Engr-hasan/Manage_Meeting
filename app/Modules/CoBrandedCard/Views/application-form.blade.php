@extends('layouts.admin')
@section('content')
    <section class="content" id="LoanLocator">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">


                    <div class="panel panel-red" id="inputForm">
                        <div class="panel-heading">Application for Co-Branded Credit Card Recommendation</div>
                        <div class="panel-body" style="margin:6px;">
                            {!! Form::open(array('url' => '/co-branded-card/add','method' => 'post','id' => 'appClearenceForm','role'=>'form','enctype'=>'multipart/form-data')) !!}
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
                                                {!! Form::label('company_name','Company Name :',['class'=>'col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('company_name', $data['companyInfo']->company_name, ['maxlength'=>'80',
                                                    'class' => 'form-control input-sm  required','readonly'=>true]) !!}
                                                    {!! $errors->first('applicant_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('address') ? 'has-error': ''}}">
                                                {!! Form::label('address','Address:',['class'=>'col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('address',Auth::user()->road_no, ['maxlength'=>'150',
                                                    'class' => 'form-control input-sm  required','readonly'=>true]) !!}
                                                    {!! $errors->first('address','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>


                                        </div>
                                    </div>

                                    <div class="form-group" style=" ">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('membership_no','BASIS Membership No. :',['class'=>'col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('membership_no',$data['memberShipNo']->membership_no, ['maxlength'=>'80',
                                                    'class' => 'form-control bnEng input-sm ','readonly'=>true]) !!}
                                                    {!! $errors->first('membership_no','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('phone_number') ? 'has-error': ''}}">
                                                {!! Form::label('phone_number','Phone Number:',['class'=>'col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('phone_number',Auth::user()->user_phone, ['maxlength'=>'30',
                                                    'class' => 'form-control   bnEng input-sm  required','readonly'=>true]) !!}
                                                    {!! $errors->first('phone_number','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="form-group" style=" ">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('name_and_designation','Name Of The Card Holder. :',['class'=>'col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('name','', ['maxlength'=>'150',
                                                    'class' => 'form-control input-sm  required']) !!}
                                                    {!! $errors->first('name_and_designation','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('designation','Designation Of The Card Holder. :',['class'=>'col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('designation','', ['maxlength'=>'150',
                                                    'class' => 'form-control input-sm  required']) !!}
                                                    {!! $errors->first('name_and_designation','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('mobile_number') ? 'has-error': ''}}">
                                                {!! Form::label('mobile_number','Mobile Number:',['class'=>'col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('mobile_number','', ['maxlength'=>'30',
                                                    'class' => 'form-control number onlyNumber input-sm  required']) !!}
                                                    {!! $errors->first('mobile_number','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>


                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('business_nature','Business Nature:',['class'=>'col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('business_nature', ['1'=>'Software','2'=>'ITS','3'=>'Both'], '',['class' => 'form-control input-sm required','placeholder'=>'Select One','id'=>'s']) !!}
                                                    {!! $errors->first('business_nature','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                {!! Form::label('email','Email :',['class'=>'col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('email','', ['maxlength'=>'80',
                                                    'class' => 'form-control input-sm  email required']) !!}
                                                    {!! $errors->first('email','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div><br></div>
                                    <div class="">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Specific business propose of payment:
                                            </legend>
                                            <div class="form-group">
                                                <div class="row">
                                                    @foreach($data['CoBrandedBusinessPurpose'] as $row)
                                                        <label style="font-weight: normal;cursor: pointer"> <input name="CoBrandedBusinessPurpose[]" value="{{$row->id}}" type="checkbox">{{$row->name}}
                                                        </label>&nbsp;&nbsp;&nbsp;
                                                    @endforeach

                                                </div>
                                            </div>

                                        </fieldset>
                                    </div>

                                    <div class="clearfix"></div>
                                </div><!--/col-md-12-->
                                <!--/panel-body-->
                            </div> <!--/panel-->


                            <div class="panel panel-primary">
                                <div class="panel-heading"><strong>2. Online Transaction</strong></div>
                                <div class="panel-body">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    {!! Form::label('estimated_online_transaction','Estimated Online Transaction Amount for 1 Year:',['class'=>'col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('estimated_online_transaction','', ['maxlength'=>'30',
                                                          'class' => 'form-control number onlyNumber input-sm  required']) !!}
                                                        {!! $errors->first('estimated_online_transaction','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group" style="">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    {!! Form::label('bank_name','Select Bank:',['class'=>'col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {{--                                                        {!! Form::select('bank_name', $data['bank'],  '',['class' => 'form-control DivisionCountry  input-sm required','id'=>'bank']) !!}--}}
                                                        {!! Form::select('bank_name', $data['bank'],  '',['class' => 'form-control input-sm required','id'=>'bank_name']) !!}
                                                    </div>

                                                </div>
                                                {{--<div class="col-md-6">--}}
                                                {{--{!! Form::label('branch_name','Branch Name:',['class'=>'col-md-5 required-star']) !!}--}}
                                                {{--<div class="col-md-7">--}}
                                                {{--{!! Form::select('branch_name', [], '',['class' => 'form-control DivisionCountry  input-sm required','placeholder'=>'Select Branch','id'=>'branch']) !!}--}}
                                                {{--</div>--}}
                                                {{--</div>--}}

                                            </div>
                                        </div>


                                        <div class="clearfix"></div>
                                        <p></p>

                                    </div><!--/col-md-12-->
                                </div> <!--/panel-body-->
                            </div>

                            <div class="panel panel-primary">
                                <div class="panel-heading"><strong>3. Declaration</strong></div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <input id="acceptTerms-2" name="acceptTerms" type="checkbox"
                                               class="required col-md-1 col-xs-1 col-sm-1 text-left"
                                               style="width: 3%; margin-left: 2px;">
                                        <label for="acceptTerms-2"
                                               class="col-md-11 col-xs-11 col-sm-11 text-left required-star"> I confirm that information given above in completed and i agree to comply with the
                                        terms and conditions of BASIS-BRAC Bank Co-Branded VISA card with the existing changes
                                        </label>
                                    </div><!--/col-md-12-->
                                </div> <!--/panel-body-->
                            </div>

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
        <div class="col-md-12"><br></div>
        <div class="col-md-12"><br></div>
        <div class="col-md-12"><br></div>
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