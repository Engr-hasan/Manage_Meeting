@extends('layouts.admin')
@section('content')
    <?php
    $accessMode = ACL::getAccsessRight('user');
    if (!ACL::isAllowed($accessMode, 'V'))
        die('no access right!');
    ?>
    <div class="col-lg-12">
        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}
        <div class="panel panel-primary">
            <div class="panel-heading">
                <b>{!! trans('messages.edit_service') !!} {!! $data->name !!} </b>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <br>

                <div class="col-lg-8">
                    {!! Form::open(array('url' => 'settings/update-service-info-details/'.$encrypted_id, 'method' => 'patch','id'=>'reg_form1')) !!}
                    <div class="form-group col-md-12 {{$errors->has('terms_and_conditions') ? 'has-error' : ''}}">
                        {!! Form::label('title', 'Title', ['class' => 'col-md-3 required-star']) !!}
                        <div class="col-md-9">
                            {!! Form::text('title', $data->title, $attributes = array('class'=>'form-control required bnEng',
                            'id'=>"title", 'data-rule-maxlength'=>'100')) !!}
                            {!! $errors->first('title','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group col-md-12{{$errors->has('process_type_id') ? 'has-error' : ''}} ">
                        {!! Form::label('process_type_id', 'Process Type:', ['class' => 'col-md-3 required-star']) !!}
                        <div class="col-md-9">
                            {!! Form::select('process_type_id', $services, $data->process_type_id, ['class' => 'form-control required ','disabled']) !!}
                            {!! $errors->first('process_type_id','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group col-md-12 {{$errors->has('description') ? 'has-error' : ''}}">
                        {!! Form::label('description','description: ',['class'=>'col-md-3  required-star']) !!}
                        <div class="col-md-9">
                            {!! Form::textarea('description',  $data->description, ['class'=>'wysihtml5-editor bnEng form-control required', 'size' => "10x9"]) !!}
                            {!! $errors->first('description','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group col-md-12 {{$errors->has('login_page_details') ? 'has-error' : ''}}">
                        {!! Form::label('login_page_details','Login Page Details: ',['class'=>'col-md-3  required-star']) !!}
                        <div class="col-md-9">
                            {!! Form::textarea('login_page_details',  $data->login_page_details, ['class'=>'wysihtml5-editor bnEng form-control required', 'size' => "10x9"]) !!}
                            {!! $errors->first('login_page_details','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group col-md-12 {{$errors->has('terms_and_conditions') ? 'has-error' : ''}}">
                        {!! Form::label('terms_and_conditions', 'Terms and Conditions', ['class' => 'col-md-3 required-star']) !!}
                        <div class="col-md-9">
                            {!! Form::text('terms_and_conditions', $data->terms_and_conditions, $attributes = array('class'=>'form-control required bnEng',
                            'id'=>"terms_and_conditions", 'data-rule-maxlength'=>'100')) !!}
                            {!! $errors->first('terms_and_conditions','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('is_active','Active Status: ',['class'=>'col-md-2 required-star']) !!}
                        <div class="col-md-6 {{$errors->has('is_active') ? 'has-error' : ''}}">
                            <label>{!! Form::radio('is_active', '1', $data->status  == '1', ['class'=>'required']) !!}
                                Active</label>
                            <label>{!! Form::radio('is_active', '0', $data->status  == '0', ['class'=>' required']) !!}
                                Inactive</label>
                            {!! $errors->first('is_active','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="col-md-3">
                        <a href="{{ url('/settings/service-info') }}" class="btn btn-sm btn-default"><i
                                    class="fa fa-close"></i> Close</a>
                    </div>
                    <div class="col-md-9">
                        @if(ACL::getAccsessRight('user','E'))
                            <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i>
                                Save
                            </button>
                        @endif
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    </div>
@endsection
@section('footer-script')

    <link rel="stylesheet" href="{{ asset("assets/stylesheets/bootstrap3-wysihtml5.min.css") }}">
    <script src="{{ asset("assets/scripts/bootstrap3-wysihtml5.all.min.js") }}" type="text/javascript"></script>

    <script>
        $(document).ready(function () {
            $(function () {
                var _token = $('input[name="_token"]').val();
                $("#reg_form").validate({
                    errorPlacement: function () {
                        return false;
                    }
                });
                var _token = $('input[name="_token"]').val();
                $("#reg_form1").validate({
                    errorPlacement: function () {
                        return false;
                    }
                });
            });
            //Select2
        });

    </script>
    <script>

        $(document).ready(function () {
            $("#division").change(function () {
                var divisionId = $('#division').val();
                $(this).after('<span class="loading_data">Loading...</span>');
                var self = $(this);
                $.ajax({
                    type: "GET",
                    url: "<?php echo url(); ?>/users/get-district-by-division",
                    data: {
                        divisionId: divisionId
                    },
                    success: function (response) {
                        var option = '<option value="">Select district</option>';
                        if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
                                option += '<option value="' + id + '">' + value + '</option>';
                            });
                        }
                        $("#district").html(option);
                        $(self).next().hide();
                    }
                });
            });

            $("#district").change(function () {
                var districtId = $('#district').val();
                $(this).after('<span class="loading_data">Loading...</span>');
                var self = $(this);
                $.ajax({
                    type: "GET",
                    url: "<?php echo url(); ?>/users/get-thana-by-district-id",
                    data: {
                        districtId: districtId
                    },
                    success: function (response) {
                        var option = '<option value="">Select Thana</option>';
                        if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
                                option += '<option value="' + id + '">' + value + '</option>';
                            });
                        }
                        $("#thana").html(option);
                        $(self).next().hide();
                    }
                });
            });
        });


    </script>

    <script>
        var _token = $('input[name="_token"]').val();

        var age = -1;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
            $("#faq-info").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });

        $(function () {
            $(".wysihtml5-editor").wysihtml5();
        });
    </script>
    <style>
        ul, ol {
            list-style-type: none;
        }
    </style>
@endsection