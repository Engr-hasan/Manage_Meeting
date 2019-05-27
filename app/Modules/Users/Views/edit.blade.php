@extends('layouts.admin')

@section('page_heading',trans('messages.profile'))

@section("content")
    <?php
    $accessMode = ACL::getAccsessRight('user');
    if (!ACL::isAllowed($accessMode, 'V'))
        die('no access right!');

    $user_type_explode = explode('x', $users->user_type);
    $random_number = str_random(30);
    ?>

    <div class="col-md-12">
        @include('message.message')
    </div>

    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <strong><i class="fa fa-user" aria-hidden="true"></i> Edit User</strong>
            </div>
            {!! Form::open(array('url' => '/users/update/'.Encryption::encodeId($users->id),'method' => 'patch', 'class' => 'form-horizontal',
                    'id'=> 'user_edit_form')) !!}

            {!! Form::hidden('selected_file', '', array('id' => 'selected_file')) !!}
            {!! Form::hidden('validateFieldName', '', array('id' => 'validateFieldName')) !!}
            {!! Form::hidden('isRequired', '', array('id' => 'isRequired')) !!}
            {!! Form::hidden('TOKEN_NO', $random_number) !!}

            <div class="panel-body">
                <div class="col-md-6">
                    <div class="form-group has-feedback {{ $errors->has('user_full_name') ? 'has-error' : ''}}">
                        <label class="col-md-4 text-left required-star">{!! trans('messages.first_name') !!}</label>

                        <div class="col-md-7">
                            {!! Form::text('user_full_name', $value = $users->user_full_name, $attributes = array('class'=>'form-control',
                            'id'=>"user_full_name", 'data-rule-maxlength'=>'50')) !!}
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                            @if($errors->first('user_full_name'))
                                <span class="control-label">
                            <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('user_full_name','') }}</em>
                        </span>
                            @endif
                        </div>
                    </div>
                    @if(isset($user_type_explode[0]) && $user_type_explode[0]=='11')
                        <div class="form-group has-feedback">
                            <label class="col-md-4 text-left">Bank Name</label>

                            <div class="col-md-7">
                                {{ $bank_name }}
                            </div>
                        </div>


                        <div class="form-group has-feedback {{ $errors->has('bank_branch_id') ? 'has-error' : ''}}">
                            <label class="col-md-4 text-left">Bank Branch</label>

                            <div class="col-md-7">
                                {!! Form::select('bank_branch_id', $branch_list, $users->bank_branch_id, array('class'=>'form-control required',
                                'placeholder' => 'Select One', 'id'=>"bank_branch_id")) !!}
                                @if($errors->first('bank_branch_id'))
                                    <span class="control-label">
                            <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('bank_branch_id','') }}</em>
                        </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="form-group has-feedback {{ $errors->has('user_type') ? 'has-error' : ''}}">
                        <label class="col-md-4 text-left required-star">User Type</label>

                        <div class="col-md-7">
                            {!! Form::select('user_type', $user_types, $users->user_type, $attributes = array('class'=>'form-control required',
                            'id'=>"user_type",'readonly' => "readonly")) !!}
                            @if($errors->first('user_type'))
                                <span class="control-label">
                            <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('user_type','') }}</em>
                        </span>
                            @endif
                        </div>
                    </div>

                    @if($users->passport_no == '')
                        <div class="form-group has-feedback {{ $errors->has('user_nid') ? 'has-error' : ''}}">
                            <label class="col-md-4 text-left">{!! trans('messages.nid') !!}</label>

                            <div class="col-md-7">
                                {!! Form::text('user_nid', $value = $users->user_nid, $attributes = array('class'=>'form-control onlyNumber', 'id'=>"user_nid",
                                 'data-rule-maxlength'=>'100')) !!}
                                <span class="glyphicon glyphicon-flag form-control-feedback"></span>
                                @if($errors->first('user_nid'))
                                    <span class="control-label">
                            <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('user_nid','') }}</em>
                        </span>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="form-group has-feedback {{ $errors->has('user_nid') ? 'has-error' : ''}}">
                            <label class="col-md-4 text-left">Passport no</label>

                            <div class="col-md-7">
                                {!! Form::text('user_nid', $value = $users->passport_no, $attributes = array('class'=>'form-control', 'id'=>"user_nid",
                                'data-rule-maxlength'=>'100')) !!}
                                <span class="glyphicon glyphicon-flag form-control-feedback"></span>
                                @if($errors->first('user_nid'))
                                    <span class="control-label">
                            <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('user_nid','') }}</em>
                        </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="form-group has-feedback {{ $errors->has('user_DOB') ? 'has-error' : ''}}">
                        <label class="col-md-4 text-left">{!! trans('messages.dob') !!}</label>

                        <div class="col-md-7">
                            {!! Form::text('user_DOB', $value = $users->user_DOB, $attributes = array('class'=>'form-control required',
                            'placeholder'=>'Enter your Birth Date','id'=>"user_DOB", 'readonly' => "readonly")) !!}
                            <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                            @if($errors->first('user_DOB'))
                                <span class="control-label">
                            <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('user_DOB','') }}</em>
                        </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group has-feedback {{ $errors->has('road_no') ? 'has-error' : ''}}">
                        <label  class="col-md-4 required-star"> Address Line 1 </label>
                        <div class="col-md-7">
                            {!! Form::text('road_no', $users->road_no, $attributes = array('class'=>'form-control bnEng required', 'data-rule-maxlength'=>'100',
                            'placeholder' => 'Enter Road / Street Name / No.', 'id'=>"road_no")) !!}
                            {!! $errors->first('road_no','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group has-feedback {{ $errors->has('house_no') ? 'has-error' : ''}}">
                        <label  class="col-md-4"> Address Line 2</label>
                        <div class="col-md-7">
                            {!! Form::text('house_no', $users->house_no, $attributes = array('class'=>'form-control', 'data-rule-maxlength'=>'100',
                            'placeholder' => 'Enter House / Flat / Holding No.', 'id'=>"house_no")) !!}
                            {!! $errors->first('house_no','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>

                {{--Right Side--}}
                <div class="col-md-6">
                    <div class="form-group has-feedback {{ $errors->has('user_phone') ? 'has-error' : ''}}">
                        <label class="col-md-5 text-left required-star">{!! trans('messages.mobile') !!}</label>

                        <div class="col-md-7">
                            {!! Form::text('user_phone', $value = $users->user_phone, $attributes = array('class'=>'form-control required mobile_number_validation',
                            'placeholder'=>'Enter the Mobile Number','id'=>"user_phone", 'data-rule-maxlength'=>'16')) !!}
                            <span class="text-danger mobile_number_error"></span>
                            <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                            @if($errors->first('user_phone'))
                                <span class="control-label">
                            <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('user_phone','') }}</em>
                        </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group has-feedback {{ $errors->has('designation') ? 'has-error' : ''}}">
                        <label class="col-md-5 text-left required-star">Designation</label>

                        <div class="col-md-7">
                            {!! Form::text('designation', $value = $users->designation, $attributes = array('class'=>'form-control',
                            'placeholder'=>'Enter the designation','id'=>"designation", 'data-rule-maxlength'=>'50')) !!}
                            <span class="glyphicon glyphicon-briefcase form-control-feedback"></span>
                            @if($errors->first('designation'))
                                <span class="control-label">
                            <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('designation','') }}</em>
                        </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group has-feedback {{ $errors->has('user_email') ? 'has-error' : ''}}">
                        <label class="col-md-5 text-left">{!! trans('messages.email') !!}</label>

                        <div class="col-md-7">
                            {{ $users->user_email }}
                        </div>
                    </div>

                    @if($users->country == 'BD')
                    <div class="form-group has-feedback {{ $errors->has('district') ? 'has-error' : ''}}">
                        <label class="col-md-5 text-left required-star">{!! trans('messages.district') !!}</label>

                        <div class="col-md-7">
                            {!! Form::select('district', $districts, $users->district, $attributes = array('class'=>'form-control required',
                             'id'=>"district")) !!}
                            @if($errors->first('district'))
                                <span class="control-label">
                            <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('district','') }}</em>
                        </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group has-feedback {{ $errors->has('thana') ? 'has-error' : ''}}">
                        <label class="col-md-5 text-left required-star">{!! trans('messages.thana') !!}</label>

                        <div class="col-md-7">
                            {!! Form::select('thana', [], $users->thana, $attributes = array('class'=>'form-control required',
                            'placeholder' => 'Select One', 'id'=>"thana")) !!}
                            @if($errors->first('thana'))
                                <span class="control-label">
                            <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('thana','') }}</em>
                        </span>
                            @endif
                        </div>
                    </div>
                    @else
                        <div class="form-group has-feedback {{ $errors->has('state') ? 'has-error' : ''}}">
                            <label  class="col-md-5 required-star"> State </label>
                            <div class="col-md-7">
                                {!! Form::text('state', $users->state, $attributes = array('class'=>'form-control', 'placeholder' => 'Name of your state / division',
                                'data-rule-maxlength'=>'40')) !!}
                                {!! $errors->first('state','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group has-feedback {{ $errors->has('province') ? 'has-error' : ''}}">
                            <label  class="col-md-5 required-star"> Province </label>
                            <div class="col-md-7">
                                {!! Form::text('province', $users->province, $attributes = array('class'=>'form-control', 'data-rule-maxlength'=>'40',
                                'placeholder' => 'Enter your Province')) !!}
                                {!! $errors->first('province','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    @endif
                    <div class="form-group has-feedback {{ $errors->has('post_code') ? 'has-error' : ''}}">
                        <label  class="col-md-5 text-left"> Post Code </label>
                        <div class="col-md-7">
                            {!! Form::text('post_code', $users->post_code, $attributes = array('class'=>'form-control', 'data-rule-maxlength'=>'40',
                            'placeholder' => 'Enter your Post Code ', 'id'=>"post_code")) !!}
                            {!! $errors->first('post_code','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>


                <div class="col-md-12">
                    <div class="col-md-6">
                        <a href="/users/lists" class="btn btn-default btn-sm"><i class="fa fa-times"></i><b> Close</b></a>
                    </div>
                    <div class="col-md-6">
                        @if(ACL::getAccsessRight('user','E'))
                            <button type="submit" class="btn btn-sm btn-primary pull-right col-md-3" id='submit_btn'><b>Save</b>
                            </button>
                        @endif
                    </div>
                </div>
                <div class="col-md-12 text-center">
                    <div class="col-md-12">
                        {!! App\Libraries\CommonFunction::showAuditLog($users->updated_at, $users->updated_by) !!}
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('footer-script')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
            $("#user_edit_form").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });

        $(document).ready(function () {
            $("#district").change(function () {
                var self = $(this);
                var districtId = $('#district').val();
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
                                    if (id == '{{$users->thana}}') {
                                        option += '<option value="' + id + '" selected>' + value + '</option>';
                                    }
                                    else {
                                        option += '<option value="' + id + '">' + value + '</option>';
                                    }
                                });
                            }
                            $("#thana").html(option);
                            self.next().hide();
                        }
                    });
                }
            });
            $("#district").trigger('change');
        });

        $("#code").blur(function () {
            var code = $(this).val().trim();
            if (code.length > 0 && code.length < 12) {
                $('.code-error').html('');
                $('#submit_btn').attr("disabled", false);
            } else {
                $('.code-error').html('Code number should be at least 1 character to maximum  11 characters!');
                $('#submit_btn').attr("disabled", true);
            }
        });
    </script>
    @endsection <!--- footer-script--->
