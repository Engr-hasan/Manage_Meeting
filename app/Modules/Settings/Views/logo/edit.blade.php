@extends('layouts.admin')

<?php $accessMode=ACL::getAccsessRight('settings');
if(!ACL::isAllowed($accessMode,'E')) die('no access right!');
?>
@section('content')
    @include('partials.messages')
    <section class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5>
                    <strong>{!! trans('messages.title_logo') !!}</strong>
                </h5>
            </div>
            <div class="panel-body">
                {!! Form::open(array('url' => 'settings/update-logo/','method' => 'patch', 'class' => 'form-horizontal', 'id' => 'logo-info','files'=>true)) !!}
                <br>
                <div class="form-group col-md-8 {{$errors->has('logo') ? 'has-error' : ''}}">
                    {!! Form::label('logo','Logo: ',['class'=>'col-md-4 control-label required-star']) !!}
                    <div class="col-md-6">
                        <span id="company_logo_err" class="text-danger" style="font-size: 10px;"></span>
                        {!! Form::file('company_logo', ['class'=> !empty($logoInfo->logo)?'':'required',
                        'data-rule-maxlength'=>'40','onchange'=>'companyLogo(this)'])!!}
                        <span class="text-danger" style="font-size: 9px; font-weight: bold">[File Format: *.jpg/ .jpeg | File size within 200 KB]</span><br/>
                        <div style="position:relative;">
                            <img id="companyLogoViewer" style="width:110px;height:70px; position:absolute;top:-56px;right:0px;border:1px solid #ddd;padding:2px;background:#a1a1a1;"
                                 src="{{ (!empty($logoInfo->logo)? url($logoInfo->logo) : '') }}" alt="">
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-8 {{$errors->has('tittle') ? 'has-error' : ''}}">
                    {!! Form::label('title','Title: ',['class'=>'col-md-4 control-label required-star']) !!}
                    <div class="col-md-6">
                        {!! Form::text('title', (isset($logoInfo->title) ? $logoInfo->title : '')  ,['class'=>'form-control required','placeholder'=>'Enter Tittle ']) !!}
                        {!! $errors->first('title','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group col-md-8 {{$errors->has('menage_by') ? 'has-error' : ''}}">
                    {!! Form::label('menage_by','Manage By: ',['class'=>'col-md-4 control-label required-star']) !!}
                    <div class="col-md-6">
                        {!! Form::text('manage_by', (isset($logoInfo->manage_by) ? $logoInfo->manage_by : ''),['class'=>'form-control required bnEng ','placeholder'=>'Enter Manage by ','data-rule-maxlength'=>'150']) !!}
                        {!! $errors->first('manage_by','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group col-md-8 {{$errors->has('help_link') ? 'has-error' : ''}}">
                    {!! Form::label('help_link','Help Link: ',['class'=>'col-md-4 control-label']) !!}
                    <div class="col-md-6">
                        {!! Form::url('help_link', (isset($logoInfo->help_link) ? $logoInfo->help_link : ''),['class'=>'form-control','placeholder'=>'Enter Manage by ']) !!}
                        {!! $errors->first('help_link','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                {{--<div class="form-group col-md-8 {{$errors->has('active_status') ? 'has-error' : ''}}">--}}
                {{--{!! Form::label('active_status','Status: ',['class'=>'col-md-4  control-label required-star']) !!}--}}
                {{--<div class="col-md-6">--}}
                {{--{!! Form::radio('active_status', 'yes', ['class'=>'form-control']) !!} Active--}}
                {{--&nbsp;&nbsp;--}}
                {{--{!! Form::radio('active_status', 'no', ['class'=>'form-control']) !!} In-Active--}}
                {{--{!! $errors->first('active_status','<span class="help-block">:message</span>') !!}--}}
                {{--</div>--}}
                {{--</div>--}}
                <div class="form-group col-md-8 {{$errors->has('caption') ? 'has-error' : ''}}">
                    <div class="col-md-7 col-sm-offset-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-chevron-circle-right"></i> Save </button>
                    </div>
                </div>

            {!! Form::close() !!}<!-- /.form end -->
            </div>
        </div>



    </section>

@endsection

@section('footer-script')
    <script>
        $(document).ready(function () {
            $("#logo-info").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });
        function companyLogo(input) {
            if (input.files && input.files[0]) {
                $("#company_logo_err").html('');
                var mime_type = input.files[0].type;
                if(!(mime_type=='image/jpeg' || mime_type=='image/jpg' || mime_type=='image/png')){
                    $("#company_logo_err").html("Image format is not valid. Only PNG or JPEG or JPG type images are allowed.");
                    return false;
                }
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#companyLogoViewer').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

@endsection
