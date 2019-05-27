@extends('layouts.admin')

@section("content")
    <?php
    $accessMode = ACL::getAccsessRight('user');
    if (!ACL::isAllowed($accessMode, 'V')) {
        die('You have no access right! For more information please contact system admin.');
    }
    ?>

    <div class="col-md-12">
        @include('message.message')
    </div>
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <strong><i class="fa fa-user-plus" aria-hidden="true"></i> Create New User</strong>
            </div>

            {!! Form::open(array('url' => '/users/store-new-user','method' => 'patch', 'class' => 'form-horizontal', 'id' => 'create_user_form',
            'enctype' =>'multipart/form-data', 'files' => 'true')) !!}

            <div class="panel-body">
                <div class="col-md-6">
                    <div class="form-group has-feedback {{ $errors->has('user_full_name') ? 'has-error' : ''}}">
                        <label  class="col-md-4 required-star">Name</label>
                        <div class="col-md-7">
                            {!! Form::text('user_full_name', $value = null, $attributes = array('class'=>'form-control required',
                            'data-rule-maxlength'=>'40', 'placeholder'=>'Enter your Name','id'=>"user_full_name")) !!}
                            {!! $errors->first('user_full_name','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    @if($logged_user_type == '1x101') {{-- For System Admin --}}

                    <div class="form-group has-feedback {{ $errors->has('user_type') ? 'has-error' : ''}}">
                        <label  class="col-md-4 required-star">User Type</label>
                        <div class="col-md-7">
                            {!! Form::select('user_type', $user_types, '', $attributes = array('class'=>'form-control required','data-rule-maxlength'=>'40',
                            'placeholder' => 'Select One', 'id'=>"user_type")) !!}
                            {!! $errors->first('user_type','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div style="display: none" class="form-group has-feedback companyUserType{{ $errors->has('company_id') ? 'has-error' : ''}}">
                        <label  class="col-md-4 required-star">Company</label>
                        <div class="col-md-7">
                            {!! Form::select('company_id', $company_list, '', $attributes = array('class'=>'form-control required','data-rule-maxlength'=>'40',
                            'placeholder' => 'Select One', 'id'=>"company_id")) !!}
                            {!! $errors->first('company_id','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    @endif

                    <div class="form-group has-feedback {{$errors->has('user_DOB') ? 'has-error' : ''}}">
                        {!! Form::label('user_DOB','Date of Birth',['class'=>'col-md-4 required-star']) !!}
                        <div class="col-md-7">
                            <div class="datepicker input-group date" data-date="12-03-2015" data-date-format="dd-mm-yyyy">
                                {!! Form::text('user_DOB', '', ['class'=>'form-control required', 'placeholder' => 'Pick from calender']) !!}
                                <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            </div>
                            {!! $errors->first('user_DOB','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group has-feedback {{ $errors->has('user_phone') ? 'has-error' : ''}}">
                        <label  class="col-md-4 required-star">Mobile Number</label>
                        <div class="col-md-7">
                            {!! Form::text('user_phone', $value = null, $attributes = array('class'=>'form-control digits required phone', 'maxlength'=>"20",
                            'minlength'=>"8", 'placeholder'=>'Enter your Mobile Number','id'=>"user_phone")) !!}
                            {!! $errors->first('user_phone','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group has-feedback {{ $errors->has('user_email') ? 'has-error' : ''}}">
                        <label  class="col-md-4 required-star">Email Address</label>
                        <div class="col-md-7">
                            {!! Form::text('user_email', $value = null, $attributes = array('class'=>'form-control email required', 'data-rule-maxlength'=>'40',
                            'placeholder'=>'Enter your Email Address','id'=>"user_email")) !!}
                            {!! $errors->first('user_email','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{--new--}}
                    <div class="form-group has-feedback {{$errors->has('identity_type') ? 'has-error': ''}}">
                        {!! Form::label('identity_type','Identification Type :',['class'=>'text-left col-md-4', 'id' => 'identity_type_label']) !!}
                        <div class="col-md-7">
                            <label class="identity_hover">{!! Form::radio('identity_type', '1', 'false', ['class'=>'identity_type']) !!} Passport</label>
                            &nbsp;&nbsp;
                            <label class="identity_hover">{!! Form::radio('identity_type', '2', 'false', ['class'=>'identity_type']) !!} National ID</label>
                        </div>
                    </div>

                    <div class="form-group has-feedback {{ $errors->has('passport_no') ? 'has-error' : ''}} hidden" id="passport_div">
                        <label  class="col-md-4 text-left required-star">Passport No.</label>
                        <div class="col-md-7">
                            {!! Form::text('passport_no', null, $attributes = array('class'=>'form-control',  'data-rule-maxlength'=>'40',
                            'placeholder'=>'Enter the Passport No. (if any)', 'id'=>"passport_no")) !!}
                            {!! $errors->first('passport_no','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group has-feedback {{ $errors->has('user_nid') ? 'has-error' : ''}} hidden" id="nid_div">
                        <label  class="col-md-4 text-left required-star">National ID No.</label>
                        <div class="col-md-7">
                            {!! Form::text('user_nid', null, $attributes = array('class'=>'form-control onlyNumber',  'data-rule-maxlength'=>'40',
                            'placeholder'=>'Enter the NID (if any)', 'id'=>"user_nid")) !!}
                            {!! $errors->first('user_nid','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group has-feedback {{ $errors->has('user_fax') ? 'has-error' : ''}}">
                        <label  class="col-md-4 text-left">Fax</label>
                        <div class="col-md-7">
                            {!! Form::text('user_fax', null, $attributes = array('class'=>'form-control', 'placeholder'=>'Enter your Fax (If Any)',
                            'data-rule-maxlength'=>'40','id'=>"user_fax")) !!}
                            {!! $errors->first('user_fax','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>


                </div><!--/col-md-6-->

                <div class="col-md-6">

                    <div class="form-group has-feedback {{ $errors->has('country') ? 'has-error' : ''}}">
                        <label  class="col-md-5 required-star">Country </label>
                        <div class="col-md-7">
                            {!! Form::select('country', $countries, null, $attributes = array('class'=>'form-control required', 'data-rule-maxlength'=>'40',
                            'placeholder' => 'Select One', 'id'=>"country")) !!}
                            {!! $errors->first('country','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group has-feedback {{ $errors->has('nationality') ? 'has-error' : ''}}">
                        <label  class="col-md-5 required-star"> Nationality</label>
                        <div class="col-md-7">
                            {!! Form::select('nationality', $nationalities, '', $attributes = array('class'=>'form-control required',  'data-rule-maxlength'=>'40',
                            'placeholder' => 'Select One', 'id'=>"nationality")) !!}
                            {!! $errors->first('nationality','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{--@if($logged_user_type == '1x101') --}}{{-- For System Admin --}}

                    {{--<div class="form-group hidden" id="userDesk">--}}
                        {{--<label  class="col-md-5 required-star text-left"> User's Desk </label>--}}
                        {{--<div class="col-md-7">--}}
                            {{--{!! Form::select('desk_id', $user_desk,'', $attributes = array('class'=>'form-control','data-rule-maxlength'=>'40',--}}
                            {{--'placeholder' => 'Select One','id'=>"desk_id")) !!}--}}
                            {{--{!! $errors->first('desk_id','<span class="help-block">:message</span>') !!}--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--@endif --}}{{-- logged user is system admin --}}

                    <div class="form-group has-feedback {{ $errors->has('division') ? 'has-error' : ''}}" id="division_div">
                        <label  class="col-md-5 required-star">Division </label>
                        <div class="col-md-7">
                            {!! Form::select('division', $divisions, '', $attributes = array('class'=>'form-control','data-rule-maxlength'=>'40',
                            'placeholder' => 'Select One', 'id'=>"division")) !!}
                            {!! $errors->first('division','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group has-feedback hidden {{ $errors->has('state') ? 'has-error' : ''}}" id="state_div">
                        <label  class="col-md-5 required-star"> State </label>
                        <div class="col-md-7">
                            {!! Form::text('state', '', $attributes = array('class'=>'form-control', 'placeholder' => 'Name of your state / division',
                            'data-rule-maxlength'=>'40', 'id'=>"state")) !!}
                            {!! $errors->first('state','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group has-feedback {{ $errors->has('district') ? 'has-error' : ''}}" id="district_div">
                        <label  class="col-md-5 required-star"> District </label>
                        <div class="col-md-7">
                            {!! Form::select('district', $districts, '', $attributes = array('class'=>'form-control', 'placeholder' => 'Select Division First',
                            'data-rule-maxlength'=>'40','id'=>"district")) !!}
                            {!! $errors->first('district','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group has-feedback hidden {{ $errors->has('province') ? 'has-error' : ''}}" id="province_div">
                        <label  class="col-md-5 required-star"> Province </label>
                        <div class="col-md-7">
                            {!! Form::text('province', '', $attributes = array('class'=>'form-control', 'data-rule-maxlength'=>'40',
                            'placeholder' => 'Enter your Province', 'id'=>"province")) !!}
                            {!! $errors->first('province','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group has-feedback {{ $errors->has('road_no') ? 'has-error' : ''}}">
                        <label  class="col-md-5 required-star"> Address Line 1 </label>
                        <div class="col-md-7">
                            {!! Form::text('road_no', '', $attributes = array('class'=>'form-control bnEng required', 'data-rule-maxlength'=>'100',
                            'placeholder' => 'Enter Road / Street Name / No.', 'id'=>"road_no")) !!}
                            {!! $errors->first('road_no','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group has-feedback {{ $errors->has('house_no') ? 'has-error' : ''}}">
                        <label  class="col-md-5"> Address Line 2</label>
                        <div class="col-md-7">
                            {!! Form::text('house_no', '', $attributes = array('class'=>'form-control bnEng', 'data-rule-maxlength'=>'100',
                            'placeholder' => 'Enter House / Flat / Holding No.', 'id'=>"house_no")) !!}
                            {!! $errors->first('house_no','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group has-feedback {{ $errors->has('post_code') ? 'has-error' : ''}}">
                        <label  class="col-md-5 text-left"> Post Code </label>
                        <div class="col-md-7">
                            {!! Form::text('post_code', '', $attributes = array('class'=>'form-control', 'data-rule-maxlength'=>'40',
                            'placeholder' => 'Enter your Post Code ', 'id'=>"post_code")) !!}
                            {!! $errors->first('post_code','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>

                <div class='clearfix'></div>
                <div class="form-group col-md-12">
                    <div class="col-md-2">
                        <a href="{{url('users/lists')}}" class="btn btn-default btn-sm"><i class="fa fa-times"></i> <b>Close</b></a>
                    </div>
                    <div class="col-md-2 col-md-offset-6 pull-right">
                        @if(ACL::getAccsessRight('user','A'))
                            <button type="submit" class="btn btn-block btn-sm btn-primary"><b>Submit</b></button>
                        @endif
                    </div>
                </div>

                <div class="clearfix"></div>
            </div> <!--/panel-body-->
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

        $(function () {
            var _token = $('input[name="_token"]').val();
            $("#create_user_form").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });

        $(document).ready(function () {
            var today = new Date();
            var yyyy = today.getFullYear();
            $('.datepicker').datetimepicker({
                viewMode: 'years',
                format: 'DD-MMM-YYYY',
                maxDate: (new Date()),
                minDate: '01/01/' + (yyyy - 60)
            });

            $('.identity_type').click(function (e) {
                if (this.value == '1') { // 1 is for passport
                    $('#passport_div').removeClass('hidden');
                    $('#passport_no').addClass('required');
                    $('#nid_div').addClass('hidden');
                    $('#user_nid').removeClass('required');
                    $('#user_nid').val('');
                }
                else { // 2 is for NID
                    $('#passport_div').addClass('hidden');
                    $('#passport_no').removeClass('required');
                    $('#passport_no').val('');
                    $('#nid_div').removeClass('hidden');
                    $('#user_nid').addClass('required');
                }
            });
            $('#identity_type').trigger('click');
        });

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
                        var option = '<option value="">Select One</option>';
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

            $('#user_type').change(function () {
                var type = $(this).val();
                if (type == '4x404') { // 4x404 is Applicant
                    $('#userDesk').removeClass('hidden');
                    $('#desk_id').addClass('required');
                }
                else {
                    $('#userDesk').addClass('hidden');
                    $('#desk_id').removeClass('required');
                }
            });
            $('#user_type').trigger('change');

            $("#user_type").change(function () {
                var user_type = $(this).val();
                if (user_type == "5x505") {
                    $(".companyUserType").show();
                } else {
                    $(".companyUserType").hide();
                }
            });

            $("#country").change(function () {
                if (this.value == 'BD') { // 001 is country_code of Bangladesh
                    $('#division_div').removeClass('hidden');
                    $('#division').addClass('required');
                    $('#district_div').removeClass('hidden');
                    $('#district').addClass('required');
                    $('#state_div').addClass('hidden');
                    $('#state').removeClass('required');
                    $('#province_div').addClass('hidden');
                    $('#province').removeClass('required');
                }
                else {
                    $('#state_div').removeClass('hidden');
                    $('#state').addClass('required');
                    $('#province_div').removeClass('hidden');
                    $('#province').addClass('required');
                    $('#division_div').addClass('hidden');
                    $('#division').removeClass('required');
                    $('#district_div').addClass('hidden');
                    $('#district').removeClass('required');
                }
            });
            $('#country').trigger('change');


        });
    </script>
@endsection
