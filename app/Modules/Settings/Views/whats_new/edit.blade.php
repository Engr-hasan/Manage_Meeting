@extends('layouts.admin')

@section('content')
<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'E'))
    die('no access right!');
?>
<div class="col-lg-12">  

    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

    <div class="panel panel-primary">
        <div class="panel-heading" style="font-size: large">
            {{--{!! trans('messages.notice_edit') !!}--}}
            Edit Whats New
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            {!! Form::open(array('url' => '/settings/update-whats-new/'.$encrypted_id,'method' => 'patch', 'class' => 'form-horizontal smart-form', 'id' => 'notice-info',
            'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

            <div class="form-group col-md-8 {{$errors->has('title') ? 'has-error' : ''}}">
                {!! Form::label('title','Title: ',['class'=>'col-md-3  required-star']) !!}
                <div class="col-md-7">
                    {!! Form::text('title',  $data->title, ['class'=>'form-control bnEng required', 'size' => "10x5"]) !!}
                    {!! $errors->first('title','<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="form-group col-md-12 {{$errors->has('description') ? 'has-error' : ''}}">
                {!! Form::label('description','Description: ',['class'=>'col-md-2  required-star']) !!}
                <div class="col-md-9">
                    {!! Form::textarea('description',  $data->description, ['class'=>'form-control bnEng required', 'id'=>'description_editor']) !!}
                    {!! $errors->first('description','<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group col-md-8 {{$errors->has('image') ? 'has-error' : ''}}">
                {!! Form::label('image','Image: ',['class'=>'col-md-3  required-star']) !!}
                <div class="col-md-7">
                    <span id="company_logo_err" class="text-danger" style="font-size: 10px;"></span>
                    {!! Form::file('image', ['class'=> !empty($data->image)?'':'required',
                    'data-rule-maxlength'=>'40','onchange'=>'companyLogo(this)'])!!}
                    <input type="hidden" name="exist_image" value="{{(!empty($data->image)? $data->image : '')}}">
                    <span class="text-danger" style="font-size: 9px; font-weight: bold">[File Format: *.jpg/ .jpeg | File size within 200 KB]</span><br/>
                    <div style="position:relative;">
                        <img id="companyLogoViewer" style="width:110px;height:70px; position:absolute;top:-56px;right:0px;border:1px solid #ddd;padding:2px;background:#a1a1a1;"
                             src="{{ (!empty($data->image)? url($data->image) : '') }}" alt="">
                    </div>
                </div>
            </div>



            <div class="form-group col-md-12 {{$errors->has('is_active') ? 'has-error' : ''}}">
                {!! Form::label('is_active','Status: ',['class'=>'col-md-2 required-star']) !!}
                <div class="col-md-7">
                    
                    @if(ACL::getAccsessRight('settings','E'))
                    &nbsp;&nbsp;
                    <label>{!! Form::radio('is_active', '1', $data->is_active  == '1', ['class'=>' required']) !!} Active</label>
                    &nbsp;&nbsp;
                    <label>{!! Form::radio('is_active', '0', $data->is_active == '0', ['class'=>'required']) !!} Inactive</label>
                    @endif
                    {!! $errors->first('status','<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="col-md-12">
                <div class="col-md-2">
                    <a href="{{ url('/settings/whats-new') }}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                </div>
                <div class="col-md-6 col-md-offset-1">
                    {!! CommonFunction::showAuditLog($data->updated_at, $data->updated_by) !!}
                </div>
                <div class="col-md-2">
                    @if(ACL::getAccsessRight('settings','E'))
                    <button type="submit" class="btn btn-primary pull-right">
                        <i class="fa fa-chevron-circle-right"></i> Save</button>
                    @endif
                </div>
            </div>

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
    $(document).ready(function () {
        $("#notice-info").validate({
            errorPlacement: function () {
                return false;
            }
        });
    });
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