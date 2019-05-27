@extends('layouts.admin')
@section('content')
<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'A'))
    die('no access right!');
?> 
<div class="col-lg-12">

    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

    <div class="panel panel-primary">
        <div class="panel-heading" style="font-size: large;">
            <b> Home Page Slider </b>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            {!! Form::open(array('url' => '/settings/store-home-page-slider','method' => 'post', 'class' => 'form-horizontal smart-form', 'id' => 'notice-info',
            'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
            <br>

            <div class="form-group col-md-8 {{$errors->has('slider_image') ? 'has-error' : ''}}">
                {!! Form::label('image','Slider Image: ',['class'=>'col-md-3  required-star']) !!}
                <div class="col-md-7">
                    <span id="company_logo_err" class="text-danger" style="font-size: 10px;"></span>
                    {!! Form::file('slider_image', ['class'=> !empty($logoInfo->logo)?'':'required',
                    'data-rule-maxlength'=>'40','id' => 'slider_image', 'onchange'=>'sliderImage(this)'])!!}
                    <span class="text-danger" style="font-size: 9px; font-weight: bold">[File Format: *.jpg/ .jpeg ]</span><br/>
                    <div style="position:relative;">
                        <img id="companyLogoViewer" style="width:110px;height:70px; position:absolute;top:-56px;right:0px;border:1px solid #ddd;padding:2px;background:#a1a1a1;"
                             src="{{ (!empty($logoInfo->logo)? url($logoInfo->logo) : '') }}" alt="">
                    </div>
                </div>
            </div>

            <div class="form-group col-md-12 {{$errors->has('slider_title') ? 'has-error' : ''}}">
                {!! Form::label('slider_title','Title: ',['class'=>'col-md-2']) !!}
                <div class="col-md-9">
                    {!! Form::text('slider_title', '', ['class'=>'form-control bnEng', 'size' => "10x5"]) !!}
                    {!! $errors->first('slider_title','<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="form-group col-md-12 {{$errors->has('slider_url') ? 'has-error' : ''}}">
                {!! Form::label('slider_url','URL: ',['class'=>'col-md-2']) !!}
                <div class="col-md-9">
                    {!! Form::text('slider_url', '', ['class'=>'form-control bnEng']) !!}
                    {!! $errors->first('slider_url','<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group col-md-12 {{$errors->has('slider_type') ? 'has-error' : ''}}">

                {!! Form::label('slidr_type','Slider Type :',['class'=>'col-md-2']) !!}
                <div class="col-md-9">
                    {!! Form::select('slider_type', ['0' => 'image', '1' => 'DOD'], '',['class' => 'form-control input-sm','placeholder'=>'Select One','id'=>'attachment_type']) !!}
                    {!! $errors->first('type_of_company','<span class="help-block">:message</span>') !!}
                </div>
            </div>

            {{--<div class="form-group col-md-12 {{$errors->has('slider_interval') ? 'has-error' : ''}}">--}}
                {{--{!! Form::label('slider_interval','Slider Interval: ',['class'=>'col-md-2']) !!}--}}
                {{--<div class="col-md-9">--}}
                    {{--{!! Form::number('slider_interval', '', ['class'=>'form-control bnEng']) !!}--}}
                    {{--{!! $errors->first('slider_interval','<span class="help-block">:message</span>') !!}--}}
                {{--</div>--}}
            {{--</div>--}}

            <div class="form-group col-md-12 {{$errors->has('status') ? 'has-error' : ''}}">
                {!! Form::label('status','Status: ',['class'=>'col-md-2  required-star']) !!}
                <div class="col-md-10">
                    <label>{!! Form::radio('status', '1', ['class'=>'cursor form-control required']) !!} Active</label>
                    &nbsp;&nbsp;
                    <label>{!! Form::radio('status', '0', ['class'=>'cursor form-control required']) !!} Inactive</label>
                    &nbsp;&nbsp;
                    {!! $errors->first('status','<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="col-md-12">
                <a href="{{ url('/settings/home-page-slider') }}">
                    {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                </a>
                @if(ACL::getAccsessRight('settings','A'))
                <button type="submit" class="btn btn-primary pull-right">
                    <i class="fa fa-chevron-circle-right"></i> Save</button>
                @endif
            </div><!-- /.box-footer -->

            {!! Form::close() !!}<!-- /.form end -->

            <div class="overlay" style="display: none;">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div><!-- /.box -->
    </div>
</div>

@endsection


@section('footer-script')
    <script src="//cdn.tinymce.com/4.5/tinymce.min.js"></script>
<script>
    var _token = $('input[name="_token"]').val();

    var age = -1;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function () {
        $("#notice-info").validate({
            errorPlacement: function () {
                return false;
            }
        });
    });
    function sliderImage(input) {
        if (input.files && input.files[0]) {
            $("#company_logo_err").html('');
            var mime_type = input.files[0].type;
            if(!(mime_type=='image/jpeg' || mime_type=='image/jpg' || mime_type=='image/png')){
                $("#company_logo_err").html("Image format is not valid. Only PNG or JPEG or JPG type images are allowed.");
                alert("Image format is not valid");
                $("#slider_image").val('');
                return false;
            }
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#companyLogoViewer').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    //   tinymce.init({ selector:'#description_editor' });
    tinymce.init({
        selector: '#description_editor',
        height: 150,
        theme: 'modern',
        plugins: [
            'autosave advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools'
        ],
        toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        toolbar2: 'print preview media | forecolor backcolor emoticons',
        image_advtab: true,
        content_css: [
            // '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
            '//www.tinymce.com/css/codepen.min.css'
        ]
    });
</script>

@endsection <!--- footer script--->