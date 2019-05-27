@extends('layouts.admin')

@section('page_heading',trans('messages.profile'))

@section('content')


    @if(Session::has('checkProfile'))
        <div class="col-sm-12">
            <div class="alert alert-warning">
                <strong>Dear user</strong><br><br>
                <p>We noticed that your profile setting does not complete yet 100%.<br>Please upload your <b>profile picture</b>,<b>signature</b> And other required information <br>Without required filed you can't apply for any kind of Registration.<br><br>Thanks<br>OSS Authority </p>
            </div>
        </div>
    @endif

    <div class="col-md-12">
        @if(Session::has('success'))
            <div class="alert alert-success alert-dismissible" >
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="icon fa fa-check"></i>{{ Session::get('success') }}
            </div>
        @endif
        @if(Session::has('message'))
            <div class="alert alert-warning">
                {{session('message')}}
            </div>
        @endif
        @if(Session::has('error'))
            <div class="alert alert-warning">
                {{ Session::get('error') }}

            </div>
    @endif
    {{--@if (count($errors) > 0)--}}
    {{--<div class="alert alert-danger">--}}
    {{--<ul>--}}
    {{--@foreach ($errors->all() as $error)--}}
    {{--<li>{{ $error }}</li>--}}
    {{--@endforeach--}}
    {{--</ul>--}}
    {{--</div>--}}
    {{--@endif--}}
    <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <div class="panel with-nav-tabs panel-info">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false"><strong>Profile</strong></a></li>
                        {{--                @if($users->social_login != 1)--}}
                        <li class=""><a href="#tab_2" data-id="{{$users->social_login}}" data-toggle="tab" class="checkGoogleLogin" aria-expanded="false"><strong>Change Password</strong></a></li>
                        {{--@endif--}}
                        {{--@if($users->social_login != 1 && Auth::user()->user_type != '1x101' &&  Auth::user()->user_type != '5x505')--}}
                            {{--<li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">Delegation</a></li>--}}
                        {{--@endif--}}

                        <li class=""><a href="#tab_5" data-toggle="tab" aria-expanded="false"><b>Access Log</b></a></li>
                        <li class=""><a href="#tab_6" data-toggle="tab" aria-expanded="false"><b>Access Log Failed</b></a></li>
                        <li class=""><a href="#tab_7" data-toggle="tab" aria-expanded="false"><b>Last 50 Action</b></a></li>
                        <li class=""><a href="#tab_8" data-toggle="tab" aria-expanded="false" class="server_date_time"><b>Server Time </b></a></li>
                    </ul>
                </div>

                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div class="">
                                <div class="col-md-6 col-sm-6">
                                    {!! Form::open(array('url' => '/users/profile_update','method' =>'patch','id'=>'update_form', 'class' => 'form-horizontal',
                                            'enctype'=>'multipart/form-data')) !!}
                                    <fieldset>
                                        {!! Form::hidden('Uid', $id) !!}
                                        <div class="row">
                                            <div class="progress hidden pull-right" id="upload_progress" style="width: 50%;">
                                                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                                </div>
                                            </div>
                                        </div>

                                        @if($users->user_status == "rejected")
                                            <div class="form-group has-feedback {{ $errors->has('group_id') ? 'has-error' : ''}}">
                                                <label  class="col-lg-4 text-left">Agency</label>
                                                <div class="col-lg-8">
                                                    {!! Form::text('agency_id', $value = null, $attributes = array('class'=>'form-control bnEng required agency',
                                                    'placeholder'=>'Enter the Name of the Agency','id'=>"group_name",'onblur'=>'checkAutoComplete(this,"agency")')) !!}
                                                    {!! Form::hidden('agency_id','',array('class'=>'group_name_hidden','id'=>'group_id')) !!}
                                                    @if($errors->first('group_id'))
                                                        <span  class="control-label">
                                                    <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('group_id','') }}</em>
                                                    </span>
                                                    @endif
                                                    <p class="empty-message"></p>
                                                </div>
                                            </div>
                                        @else
                                            <div class="form-group has-feedback">
                                                <label  class="col-lg-4 text-left">User Type</label>
                                                <div class="col-lg-8">
                                                    {{ $user_type_info->type_name }}
                                                </div>
                                            </div>
                                            @if($users->desk_id = '0')
                                                <div class="form-group has-feedback">
                                                    <label class="col-lg-4 text-left">User Desk Name</label>
                                                    <div class="col-lg-7">
                                                        {{$user_desk->desk_name}}
                                                    </div>
                                                </div>
                                            @endif

                                        @endif

                                        <div class="form-group has-feedback">
                                            <label  class="col-lg-4 text-left">Email Address</label>
                                            <div class="col-lg-8">
                                                {{ $users->user_email }}
                                            </div>

                                        </div>

                                        <div class="form-group has-feedback {{ $errors->has('user_full_name') ? 'has-error' : ''}}">
                                            <label  class="col-lg-4 text-left required-star">User’s full name</label>
                                            <div class="col-lg-8">
                                                {!! Form::text('user_full_name',$users->user_full_name, $attributes = array('class'=>'form-control bnEng required',
                                                'placeholder'=>'Enter your Name','id'=>"user_full_name", 'data-rule-maxlength'=>'100')) !!}
                                                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                                {!! $errors->first('user_full_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group has-feedback {{ $errors->has('designation') ? 'has-error' : '' }}">
                                            <label class="col-lg-4 text-left required-star">Designation</label>
                                            <div class="col-lg-8">
                                                {!! Form::text('designation',$users->designation, ['class'=>'form-control bnEng required','data-rule-maxlength'=>'100',
                                                'placeholder'=>'Enter your Designation']) !!}
                                                {!! $errors->first('designation','<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label  class="col-lg-4 text-left required-star">Date of Birth</label>
                                            <div class="col-lg-8">
                                                <div class="datepicker input-group date" data-date-format="yyyy-mm-dd">
                                                    @if($users->user_DOB)
                                                        <?php $dob = App\Libraries\CommonFunction::changeDateFormat($users->user_DOB) ?>
                                                    @else
                                                        <?php $dob = '' ?>
                                                    @endif
                                                    {!! Form::text('user_DOB', $dob, ['class'=>'form-control required']) !!}
                                                    <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                                    {!! $errors->first('user_DOB','<span class="help-block">:message</span>') !!}


                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group has-feedback {{ $errors->has('user_phone') ? 'has-error' : ''}}">
                                            <label  class="col-lg-4 text-left required-star">Mobile Number  </label>
                                            <div class="col-lg-8">
                                                {!! Form::text('user_phone',$users->user_phone, $attributes = array('class'=>'form-control required mobile_number_validation',
                                                'placeholder'=>'Enter your Mobile Number','id'=>"user_phone", 'data-rule-maxlength'=>'16')) !!}
                                                <span class="text-danger mobile_number_error"></span>
                                                <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                                                {!! $errors->first('user_phone','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        @if($users->passport_no !="")
                                            <div class="form-group has-feedback {{ $errors->has('passport_no') ? 'has-error' : ''}}">
                                                <label  class="col-lg-4 text-left required-star">Passport No  </label>
                                                <div class="col-lg-8">
                                                    {!! Form::text('passport_no',$users->passport_no, ['class'=>'form-control required','data-rule-maxlength'=>'40',
                                                   'placeholder'=>'Enter your Passport (if any)','id'=>"passport_no"]) !!}
                                                    <span class="glyphicon glyphicon-book form-control-feedback"></span>
                                                    {!! $errors->first('passport_no','<span class="help-block">:message</span>')
                                                    !!}
                                                </div>
                                            </div>
                                        @elseif($users->user_nid !="")

                                            <div class="form-group has-feedback {{ $errors->has('user_nid') ? 'has-error' : '' }}">
                                                <label class="col-lg-4 text-left required-star">National ID No.</label>
                                                <div class="col-lg-8">
                                                    {!! Form::text('user_nid',$users->user_nid, ['class'=>'form-control required','data-rule-maxlength'=>'40',
                                                    'placeholder'=>'Enter your NID (if any)','id'=>"user_nid"]) !!}
                                                    <span class="glyphicon glyphicon-credit-card form-control-feedback"></span>
                                                    {!! $errors->first('user_nid','<span class="help-block">:message</span>')
                                                    !!}
                                                </div>
                                            </div>
                                        @endif

                                        {{--<div class="form-group has-feedback {{ $errors->has('passport_no') ? 'has-error' : ''}}">--}}
                                        {{--<label  class="col-lg-4 text-left">Passport No  </label>--}}
                                        {{--<div class="col-lg-8">--}}
                                        {{--{!! Form::text('passport_no',$users->passport_no, $attributes = array('class'=>'form-control',--}}
                                        {{--'placeholder'=>'Enter your Passport No','data-rule-maxlength'=>'16')) !!}--}}
                                        {{--<span class="text-danger"></span>--}}
                                        {{--{!! $errors->first('passport_no','<span class="help-block">:message</span>') !!}--}}
                                        {{--</div>--}}
                                        {{--</div>--}}

                                        {{--<div class="form-group has-feedback {{ $errors->has('user_nid') ? 'has-error' : ''}}">--}}
                                        {{--<label  class="col-lg-4 text-left">National Id  </label>--}}
                                        {{--<div class="col-lg-8">--}}
                                        {{--{!! Form::text('user_nid',$users->user_nid, $attributes = array('class'=>'form-control onlyNumber',--}}
                                        {{--'placeholder'=>'Enter your NID No')) !!}--}}
                                        {{--<span class="text-danger"></span>--}}
                                        {{--{!! $errors->first('user_nid','<span class="help-block">:message</span>') !!}--}}
                                        {{--</div>--}}
                                        {{--</div>--}}

                                        <div class="form-group has-feedback {{ $errors->has('road_no') ? 'has-error' : ''}}">
                                            <label  class="col-lg-4 text-left">Address Line 1  </label>
                                            <div class="col-lg-8">
                                                {!! Form::text('road_no',$users->road_no, $attributes = array('class'=>'form-control bnEng',
                                                'placeholder'=>'Road No/ Address Line 1')) !!}
                                                <span class="text-danger"></span>
                                                {!! $errors->first('road_no','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group has-feedback {{ $errors->has('house_no') ? 'has-error' : ''}}">
                                            <label  class="col-lg-4 text-left">Address Line 2  </label>
                                            <div class="col-lg-8">
                                                {!! Form::text('house_no',$users->house_no, $attributes = array('class'=>'form-control bnEng',
                                                'placeholder'=>'Enter your NID No','data-rule-maxlength'=>'16')) !!}
                                                <span class="text-danger"></span>
                                                {!! $errors->first('house_no','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group has-feedback {{ $errors->has('district') ? 'has-error' : ''}}">
                                            <label  class="col-lg-4 text-left required-star">{!! trans('messages.district') !!}</label>
                                            <div class="col-lg-8">
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
                                            <label  class="col-lg-4 text-left required-star">{!! trans('messages.thana') !!}</label>
                                            <div class="col-lg-8">
                                                {!! Form::select('thana', [], $users->thana, $attributes = array('class'=>'form-control required',
                                                'placeholder' => 'Select One', 'id'=>"thana")) !!}
                                                @if($errors->first('thana'))
                                                    <span class="control-label">
                                                    <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('thana','') }}</em>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group has-feedback">
                                            <label  class="col-lg-4 text-left">User Status</label>
                                            <div class="col-lg-8">
                                                {{ $users->user_status }}
                                            </div>
                                        </div>

                                        @if($users->user_status == "rejected")
                                            <div class="form-group has-feedback">
                                                <label  class="col-lg-4 text-left">Reject Reason</label>
                                                <div class="col-lg-8">
                                                    {{ $users->user_status_comment }}
                                                </div>
                                            </div>
                                        @endif

                                        @if($users->user_status == "inactive")
                                            <div class="form-group has-feedback {{ $errors->has('authorization_file') ? 'has-error' : ''}}">
                                                <label  class="col-lg-4 text-left  required-star">Authorization Letter<br/>
                                                    <span style="font-size: 9px; color: grey">
                                                {!! $doc_config !!}
                                            </span>
                                                </label>
                                                <div class="col-lg-8">
                                                    {!! '<img src="' . $auth_file . '" class="profile-user-img img-responsive"  alt="Authorization File" id="authorized_file"  width="350" />' !!}
                                                    <br/>
                                                    <div id="auth_file_err" style="color: red;">

                                                    </div>
                                                    <input type="hidden" class="upload_flags" value="0">
                                                    {!! Form::file('authorization_file',['onchange'=>'readAuthFile(this)','data-ref'=>''.Encryption::encodeId(Auth::user()->id).'','data-type'=>'auth_file']) !!}
                                                    <button class="btn btn-xs btn-primary hidden change_btn" type="button">Change</button>
                                                    {!! $errors->first('authorization_file','<span class="help-block">:message</span>') !!}
                                                </div>


                                            </div>
                                        @endif
                                        <div class="form-group">
                                            <div  class="col-lg-4"></div>
                                            <div class="col-lg-8">
                                                <?php
                                                $checked = '';
                                                if ($users->auth_token_allow == '1')
                                                    $checked = "checked='checked'";
                                                ?>
                                                @if($user_type_info->auth_token_type=='optional')
                                                    <input type="checkbox" name="auth_token_allow" value="1" {{$checked}} id="all_second_step">
                                                    <label for="all_second_step">Allow two step verification</label>
                                                @endif
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <div class="col-md-9"></div>
                                            <div class="col-md-3">
                                                <button type="submit" class="btn btn-primary btn-block" id='update_info_btn'><b>Save</b></button>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-lg-12">
                                                {!! App\Libraries\CommonFunction::showAuditLog($users->updated_at, $users->updated_by) !!}
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-1 col-sm-1"></div>
                                <div class="col-md-5 col-sm-5 col-sm-offset-1">
                                    <div class="well well-sm">
                                        <div class="row">
                                            <div class="col-sm-6 col-md-4">
                                                <?php
                                                if (!empty($users->user_pic)) {
                                                    $userPic = url() . '/users/upload/' . $users->user_pic;
                                                } else {
                                                    $userPic = URL::to('/assets/images/avatar5.png');
                                                }
                                                ?>
                                                <img src="{{ $userPic }}" class="profile-user-img img-responsive img-circle"
                                                     alt="Profile Picture" id="user_pic" width="200"/>

                                            </div>
                                            <div class="col-sm-6 col-md-8">
                                                <h4 class="required-star">Profile Image</h4>
                                                <cite title="">
                                                    <label class="col-lg-8 text-left">
                                                            <span style="font-size: 9px; color: grey">
                                                            [File Format: *.jpg / *.png, File size('3-100')KB,Dimension: 300x300 pixel]
                                                            </span>
                                                    </label>
                                                </cite>
                                                <div class="clearfix"><br/></div>
                                                <small>
                                                    <cite title="">
                                                        <label class="col-lg-8 text-left">
                                                            <span id="user_err" class="text-danger" style="font-size: 10px;">{!! $errors->first('profile_image','<span class="help-block" style="color:#C32C2C">:message</span>') !!}</span>
                                                        </label>
                                                    </cite>
                                                </small>
                                                <div class="clearfix"><br/></div>
                                                <label class="btn btn-primary btn-file">
                                                    <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                    Browse <input type="file" onchange="readURLUser(this);" name="profile_image" data-type="user"
                                                                  data-ref="{{Encryption::encodeId(Auth::user()->id)}}" style="display: none;">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-5 col-sm-5 col-sm-offset-1">
                                    <div class="well well-sm">
                                        <div class="row">
                                            <div class="col-sm-6 col-md-4">
                                                <?php
                                                if (!empty($users->signature)) {
                                                    $signature = url() . '/users/signature/' . $users->signature;
                                                } else {
                                                    $signature = URL::to('/assets/images/no_image.png');
                                                }
                                                ?>
                                                <img src="{{ $signature }}" class="signature-user-img img-responsive img-rounded user_signature"
                                                     alt="User Signature" id="user_signature" width="200"/>

                                            </div>
                                            <div class="col-sm-6 col-md-8">
                                                <h4 class="required-star">Signature</h4>
                                                <small>
                                                    <cite title="{!! $image_config !!}">
                                                        <label class="col-lg-8 text-left">
                                                            <span style="font-size: 9px; color: grey">
                                                           [File Format: *.jpg / *.png, File size('3-100')KB,Dimension: 300x80 pixel]</span>
                                                        </label>
                                                    </cite>
                                                </small>
                                                <div class="clearfix"><br/></div>
                                                <small>
                                                    <cite title="{!! $image_config !!}">
                                                        <label class="col-lg-8 text-left">
                                                            <span id="signature_error" class="text-danger" style="font-size: 10px;">{!! $errors->first('signature','<span class="help-block" style="color:#C32C2C">:message</span>') !!}</span>
                                                        </label>
                                                    </cite>
                                                </small>
                                                <div class="clearfix"><br/></div>
                                                <label class=" btn btn-primary btn-file {{ $errors->has('signature')?'has-error':'' }}">
                                                    <i class="fa fa-picture-o" aria-hidden="true"> </i> Browse
                                                    <input @if(!isset($users->signature)) class="required" @endif
                                                    type="file" onchange="readURLSignature(this);" name="signature" data-type="signature"
                                                           data-ref="{{Encryption::encodeId(Auth::user()->id)}}" style="display: none">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{--<div class="col-md-5 col-sm-5"><br/>--}}
                                {{--{!! '<img src="' . $profile_pic . '" class="profile-user-img img-responsive"  alt="Profile Picture" id="uploaded_pic"  width="200" />' !!}--}}

                                {{--<div class="clearfix"><br/></div>--}}
                                {{--<div class="form-group has-feedback">--}}
                                {{--<label  class="col-lg-4 text-left">Profile Image <br/>--}}


                                {{--</label>--}}
                                {{--<div class="col-lg-8">--}}
                                {{--<div id="user_err" style="color: red;"></div>--}}
                                {{--<input type='file' onchange="readURL(this);" name="image"  data-type="user" data-ref="{{App\Libraries\Encryption::encodeId(Auth::user()->id)}}"/>--}}
                                {{--<button class="btn btn-xs btn-primary hidden change_btn" onclick="return confirm('Are you sure?')" type="button">Upload this Image</button>--}}
                                {{--</div>--}}
                                {{--<div class="col-lg-2"></div>--}}
                                {{--<div class="col-md-12" style="font-size: 12px; color: grey">--}}
                                {{--{!! $image_config !!}--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {!! Form::close() !!}
                            </div>
                            <div class="clearfix"></div>
                        </div><!-- /.tab-pane -->

                        <div class="tab-pane" id="tab_2">
                            <div class="col-sm-10">
                                {!! Form::open(array('url' => '/users/update-password-from-profile','method' => 'patch', 'class' => 'form-horizontal',
                                'id'=> 'password_change_form')) !!}
                                {{--<fieldset>--}}
                                <div class="clearfix"><br/><br/></div>
                                {!! Form::hidden('Uid', $id) !!}

                                <div class="form-group has-feedback {{ $errors->has('user_old_password') ? 'has-error' : ''}}">
                                    <label  class="col-lg-4 text-left">Old Password</label>
                                    <div class="col-lg-4">
                                        {!! Form::password('user_old_password', $attributes = array('class'=>'form-control required',
                                        'placeholder'=>'Enter your Old password','id'=>"user_old_password", 'data-rule-maxlength'=>'120')) !!}
                                        <span class="glyphicon glyphicon-check form-control-feedback"></span>
                                        {!! $errors->first('user_old_password','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>

                                <div class="form-group has-feedback {{ $errors->has('user_new_password') ? 'has-error' : ''}}">
                                    <label  class="col-lg-4 text-left">New Password</label>
                                    <div class="col-lg-4">
                                        {!! Form::password('user_new_password', $attributes = array('class'=>'form-control required',  'minlength' => "6",
                                        'placeholder'=>'Enter your New password','id'=>"user_new_password", 'data-rule-maxlength'=>'120')) !!}
                                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                        {!! $errors->first('user_new_password','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-lg-4">
                                        <code>[*Minimum 6 characters at least 1 Alphabet, 1 Number and 1 Special Character]</code>
                                    </div>
                                </div>

                                <div class="form-group has-feedback {{ $errors->has('user_confirm_password') ? 'has-error' : ''}}">
                                    <label  class="col-lg-4 text-left">Confirm New Password</label>
                                    <div class="col-lg-4">
                                        {!! Form::password('user_confirm_password', $attributes = array('class'=>'form-control required', 'minlength' => "6",
                                        'placeholder'=>'Confirm your New password','id'=>"user_confirm_password", 'data-rule-maxlength'=>'120')) !!}
                                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                        {!! $errors->first('user_confirm_password','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="col-lg-2 col-lg-offset-6">
                                        <div class="clearfix"><br></div>
                                        <button type="submit" class="btn btn-block btn-primary" id="update_pass_btn"><b>Save</b></button>
                                    </div>
                                    <div class="col-lg-4"></div>
                                </div>

                                <div class="form-group has-feedback">
                                    <div  class="col-lg-1"></div>
                                    <div class="col-lg-5">
                                        {!! App\Libraries\CommonFunction::showAuditLog($users->updated_at, $users->updated_by) !!}
                                    </div>
                                </div>
                                {{--</fieldset>--}}
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                {!! Form::close() !!}
                            </div>

                        </div><!-- /.tab-pane -->

                        @if(Auth::user()->user_type != '1x101')
                            <div class="tab-pane table-responsive" id="tab_3">
                                <br>
                                {!! Form::open(array('url' => '/users/process-deligation','method' =>
                                'patch','id'=>'deligation', 'class' => '','enctype'
                                =>'multipart/form-data')) !!}
                                <div class="form-group col-lg-8">
                                    <div class="col-lg-3"><label class="required-star">User Type</label></div>
                                    <div class="col-lg-6">
                                        <?php $desig = ($delegate_to_types ? $delegate_to_types : '') ?>
                                        {!! Form::select('designation', $desig, '', $attributes =
                                        array('class'=>'form-control required', 'onchange'=>'getUserDeligate()',
                                        'placeholder' => 'Select Type', 'id'=>"designation_2")) !!}
                                    </div>
                                </div>

                                <div class="form-group  col-lg-8">
                                    <div class="col-lg-3"><label class="required-star">Delegated User</label> </div>
                                    <div class="col-lg-6">
                                        {!! Form::select('delegated_user', [] , '', $attributes =
                                        array('class'=>'form-control required',
                                        'placeholder' => 'Select User', 'id'=>"delegated_user")) !!}
                                    </div>
                                </div>

                                <div class="form-group  col-lg-8">
                                    <div class="col-lg-3"><label>Remarks</label></div>
                                    <div class="col-lg-6">
                                        {!! Form::text('remarks','', $attributes = array('class'=>'form-control',
                                        'placeholder'=>'Enter your Remarks','id'=>"remarks")) !!}
                                    </div>
                                </div>


                                <div class="form-group  col-lg-8">
                                    <div class="col-lg-6 col-lg-offset-3">
                                        <button type="submit" class="btn btn-primary" id='update_info_btn'><b>Deligate</b></button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div><!-- /.tab-pane -->
                        @endif



                        <div class="tab-pane" id="tab_5">
                            <table id="accessList" class="table table-striped table-responsive table-bordered dt-responsive" width="100%" cellspacing="0" style="font-size: 14px;">
                                <thead>
                                <tr>
                                    <th>Remote Address</th>
                                    <th>Log in time</th>
                                    <th>Log out time</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div><!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_6">
                            <table id="accessLogFailed" class="table table-striped table-responsive table-bordered dt-responsive" width="100%" cellspacing="0" style="font-size: 14px;">
                                <thead>
                                <tr>
                                    <th>Remote Address</th>
                                    <th>Failed Login Time</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div><!-- /.tab-pane -->

                        <div class="tab-pane" id="tab_7">
                            <table id="last50action" class="table table-striped table-responsive table-bordered dt-responsive" width="100%" cellspacing="0" style="font-size: 14px;">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Action Taken</th>
                                    <th>IP</th>
                                    <th>Date & Time</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div><!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_8">
                            <div class="form-group has-feedback" id="serverTime">
                                <div class="col-lg-12">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Application Time</legend>
                                        <div class="control-group">
                                            <strong>Date : </strong> <span id="app_date">{{ date('d-M-Y') }}</span> <br/>
                                            <strong>Time : </strong> <span id="app_time">{{ date('g:i:s A') }}</span>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="form-group has-feedback">
                                <div class="col-lg-12">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Database Time</legend>
                                        <div class="control-group">
                                            <strong>Date : </strong> <span id="db_date">{{ date('d-M-Y') }}</span> <br/>
                                            <strong>Time : </strong> <span id="db_time">{{ date('g:i:s A') }}</span>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div><!-- /.tab-pane --><!-- /.tab-pane -->

                    </div><!-- /.tab-content -->
                </div>
            </div>
        </div><!-- nav-tabs-custom -->
    </div>

    <div class="clearfix"></div>

@endsection

@section('footer-script')

    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.devbridge-autocomplete/1.2.24/jquery.autocomplete.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    @include('partials.datatable-scripts')

    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>
    <script>
        var url = document.location.toString();


        if (url.match('#')) {
            var googleSignUpId = $('.checkGoogleLogin').attr("data-id");
            if(googleSignUpId==1) {
                alert("You Have sign up by google.no need to change password");
                // return false;
            }else {
                $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
            }
        }
        function getUserDeligate() {
            var _token = $('input[name="_token"]').val();
            var designation = $('#designation_2').val();
            $.ajax({
                url: '{{url("users/get-delegate-userinfo")}}',
                type: 'post',
                data: {
                    _token: _token,
                    designation: designation
                },
                dataType: 'json',
                success: function (response) {
                    html = '<option value="">Select User</option>';
                    $.each(response, function (index, value) {
                        html += '<option value="' + value.id + '" >' + value.user_full_name + '</option>';
                    });
                    $('#delegated_user').html(html);
                },
                beforeSend: function (xhr) {
                    console.log('before send');
                },
                complete: function () {
                    //completed
                }
            });
        }
        $(function () {
            var _token = $('input[name="_token"]').val();
            $("#vreg_form").validate({
                errorPlacement: function () {
                    return false;
                }
            });
            $(".agency").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "{{url('users/get-agency')}}",
                        dataType: "json",
                        data: {
                            q: request.term
                        },
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                response: function (event, ui) {
                    if (ui.content.length === 0) {
                        $(".empty-message").text("No results found");
                    } else {
                        $(".empty-message").empty();
                    }
                },
                select: function (event, data) {
                    $('.group_name_hidden').val(data.item.id);
                }
            });
        });


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
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
                                    if (id == '{{$users->thana}}'){
                                        option += '<option value="'+ id + '" selected>' + value + '</option>';
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

            $('.checkGoogleLogin').click(function () {
                var googleSignUpId = $(this).attr("data-id");
                if(googleSignUpId==1) {
                    alert("You Have sign up by google.no need to change password");
                    return false;
                }
            });
        });

        $(function () {
            $('.datepicker').datetimepicker({
                viewMode: 'years',
                format: 'DD-MMM-YYYY',
                maxDate: (new Date()),
                minDate: '01/01/1916'
            });
        });

        $(function () {

            $('#accessList').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{url("users/get-access-log-data-for-self")}}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'ip_address', name: 'ip_address'},
                    {data: 'login_dt', name: 'login_dt'},
                    {data: 'logout_dt', name: 'logout_dt'},

                ],
                "aaSorting": []
            });



            $('#accessLogFailed').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{url("users/get-access-log-failed")}}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'remote_address', name: 'remote_address'},
                    {data: 'created_at', name: 'created_at'}

                ],
                "aaSorting": []
            });



            $('#last50action').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{url("users/get-last-50-action")}}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'rownum', name: 'rownum'},
                    {data: 'action', name: 'action'},
                    {data: 'ip_address', name: 'ip_address'},
                    {data: 'created_at', name: 'created_at'}

                ],
                "aaSorting": []
            });

            $('.server_date_time').on('click',function(){
                setInterval(function() {
                    $.ajax({
                        type: 'POST',
                        url: '{{url("users/get-server-time")}}',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(data){

                            $('#db_date').html(data.db_date);
                            $('#db_time').html(data.db_time);
                            $('#app_date').html(data.app_date);
                            $('#app_time').html(data.app_time);

                        }
                    });
                }, 1000);
            });

        });


        $('#password_change_form').validate({
            rules: {
                user_confirm_password: {
                    equalTo: "#user_new_password"
                }
            },
            errorPlacement: function () {
                return false;
            }
        });

        $(document).ready(
                function () {
                    $("#profile-form").validate({
                        errorPlacement: function () {
                            return false;
                        }
                    });
                });
        $("#deligation").validate({
            errorPlacement: function () {
                return false;
            }

        });
        $("#update_form").validate({
            errorPlacement: function () {
                return false;
            }
        });
        function readURLUser(input) {
            if (input.files && input.files[0]) {
                $("#user_err").html('');
                var mime_type = input.files[0].type;
                if(!(mime_type=='image/jpeg' || mime_type=='image/jpg' || mime_type=='image/png')){
                    $("#user_err").html("Image format is not valid. Only PNG or JPEG or JPG type images are allowed.");
                    return false;
                }
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#user_pic').attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        function readURLSignature(input) {
            if (input.files && input.files[0]) {
                $("#signature_error").html('');
                var mime_type = input.files[0].type;
                if(!(mime_type=='image/jpeg' || mime_type=='image/jpg' || mime_type=='image/png')){
                    $("#signature_error").html("Image format is not valid. Only PNG or JPEG or JPG type images are allowed.");
                    return false;
                }
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#user_signature').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <style>
        #accessList{
            height: 100px !important;
            overflow: scroll;
        }
        .dataTables_scrollHeadInner{width:100% !important;}
        .profileinfo-table{width:100% !important;}
    </style>
@endsection
