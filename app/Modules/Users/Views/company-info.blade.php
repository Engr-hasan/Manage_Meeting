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
        <div class="panel panel-info">
            <div class="panel-heading">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false"><strong>Company Associate</strong></a></li>

                    <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"><b>New Company</b></a></li>
                </ul>
            </div>
            <div class="panel-body">
                <div class="tab-content">
                <div class="tab-pane table-responsive active" id="tab_1">
                  <br>
                <div class="col-lg-8">
                    {!! Form::open(array('url' => 'users/company-associated-save', 'method' => 'post')) !!}
                    <div class="form-group col-md-12">
                        {!! Form::label('email', 'User Email:', ['class' => 'col-md-3']) !!}
                        <input type="hidden" name="user_id" value="{{ $user_id }}">
                        <div class="col-md-9">
                            {!! Form::text('email', $user_exist_company->user_email, $attributes = array('class'=>'form-control',
                                 'id'=>"user_full_name",'readonly', 'data-rule-maxlength'=>'100')) !!}
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('assign_desk', 'Select Company to Associated:', ['class' => 'col-md-3']) !!}
                     <input type="hidden" name="user_id" value="{{ $user_id }}">
                        <div class="col-md-9">
                            <select name="company_associated[]" class="city form-control limitedNumbSelect2" data-placeholder="Select Desk to assign" style="width: 100%;" multiple="multiple">
                                @foreach($company_list as $data)
                                    @if(in_array( $data->id, $select))
                                        <option value="{{ $data->id }}" selected="true">{{ $data->company_info }}</option>
                                    @else
                                        <option value="{{ $data->id }}">{{ $data->company_info }}</option>
                                    @endif
                                @endforeach
                            </select>
                            {{--{!! Form::select('user_types[]', $desk_list, $select, ['class' => 'form-control input-sm limitedNumbSelect2','multiple'=>'true', 'placeholder' => 'Select Desk to assign']) !!}--}}
                            {!! $errors->first('company_associated','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-3">
                            <a href="{{ url('users/lists') }}" class="btn btn-sm btn-default"><i class="fa fa-close"></i> Close</a>
                        </div>
                        <div class="col-md-9">
                            @if(ACL::getAccsessRight('user','E'))
                            <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>
                            @endif
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
                    </div>
                    <div class="tab-pane table-responsive" id="tab_2">
                        <br>
                        <div class="col-lg-8">
                            {!! Form::open(array('url' => 'users/company-info-save', 'method' => 'post','id'=>'reg_form')) !!}
                            <div class="form-group col-md-12">
                                {!! Form::label('Company', 'Company Name:', ['class' => 'col-md-3 required-star']) !!}
                                <input type="hidden" name="user_id" value="{{ $user_id }}">
                                <div class="col-md-9">
                                    {!! Form::text('company_name', '', $attributes = array('class'=>'form-control required',
                                         'id'=>"user_full_name", 'data-rule-maxlength'=>'100')) !!}
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                {!! Form::label('division', 'Division:', ['class' => 'col-md-3 required-star']) !!}
                                <div class="col-md-9">
                                    {!! Form::select('division', $divisions, null, $attributes = array('class'=>'form-control required',
                                  'id'=>"division")) !!}
                                    {!! $errors->first('division','<p class="text-danger pss-error">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                {!! Form::label('district', 'District:', ['class' => 'col-md-3 required-star']) !!}
                                <div class="col-md-9">
                                    {!! Form::select('district', array(), '', $attributes = array('class'=>'form-control required', 'placeholder' => 'Select Division first',
                                   'data-rule-maxlength'=>'40','id'=>"district")) !!}
                                    {!! $errors->first('district','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                {!! Form::label('thana', 'Thana:', ['class' => 'col-md-3 required-star']) !!}
                                <div class="col-md-9">
                                    {!! Form::select('thana', array(), '', $attributes = array('class'=>'form-control required', 'placeholder' => 'Select division first',
                                'data-rule-maxlength'=>'50','id'=>"thana")) !!}
                                    {!! $errors->first('district','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <div class="col-md-3">
                                    <a href="{{ url('/dashboard') }}" class="btn btn-sm btn-default"><i class="fa fa-close"></i> Close</a>
                                </div>
                                <div class="col-md-9">
                                    @if(ACL::getAccsessRight('user','E'))
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>
                                    @endif
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer-script')
    <link rel="stylesheet" href="{{ asset("assets/plugins/select2.min.css") }}">
    <script src="{{ asset("assets/plugins/select2.min.js") }}"></script>
    <script>
        $(document).ready(function(){
            $(function () {
                var _token = $('input[name="_token"]').val();
                $("#reg_form").validate({
                    errorPlacement: function () {
                        return false;
                    }
                });
            });
            //Select2
            $(".limitedNumbSelect2").select2({
                //maximumSelectionLength: 1
            });
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
@endsection