@extends('layouts.admin')

@section('content')
<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'E')) {
    die('You have no access right! Please contact system admin for more information');
}
?>

@include('partials.messages')

<div class="col-lg-12">

    <div class="panel panel-primary">
        <div class="panel-heading">
            <b>Updating details of {!! $data->park_name !!}</b>
        </div>

        <div class="panel-body">

            {!! Form::open(array('url' => '/settings/update-park/'.$id,'method' => 'patch', 'class' => 'form-horizontal', 'id' => 'info',
            'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

            <div class="form-group col-md-12">
                {!! Form::label('name','Name of the Economic Zone : ',['class'=>'col-md-3  required-star']) !!}
                <div class="col-md-5 {{$errors->has('name') ? 'has-error' : ''}}">
                    {!! Form::text('name', $data->park_name, ['class'=>'form-control required input-sm', 'placeholder' => 'e.g. Meghna EZ']) !!}
                </div>
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('district','District',['class'=>'col-md-3 required-star']) !!}
                <div class="col-md-5 {{$errors->has('district') ? 'has-error' : ''}}">
                    {!! Form::select('district', $districts, $data->district_name, ['class' => 'form-control required input-sm', 'placeholder' => 'Select One']) !!}
                </div>
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('upazilla','Upazila : ',['class'=>'col-md-3  required-star']) !!}
                <div class="col-md-5 {{$errors->has('upazilla') ? 'has-error' : ''}}">
                    {!! Form::text('upazilla', $data->upazilla_name, ['class'=>'form-control required input-sm', 'placeholder' => 'e.g. Sonargaon']) !!}
                </div>
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('area','Area (in square-meter) : ',['class'=>'col-md-3  required-star']) !!}
                <div class="col-md-5 {{$errors->has('area') ? 'has-error' : ''}}">
                    {!! Form::text('area', $data->park_area, ['class'=>'form-control required input-sm onlyNumber', 'placeholder' => 'e.g. 100.45']) !!}
                </div>
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('remarks','remarks (if any) : ',['class'=>'col-md-3']) !!}
                <div class="col-md-5 {{$errors->has('remarks') ? 'has-error' : ''}}">
                    {!! Form::textarea('remarks', $data->remarks, ['class'=>'form-control input-sm', 'placeholder' => 'e.g. For usage inside Bangladesh',
                    'size' => '5x2']) !!}
                </div>
            </div>

            <div class="form-group col-md-12">
                {!! Form::label('is_active','Active Status: ',['class'=>'col-md-3 required-star']) !!}
                <div class="col-md-5 {{$errors->has('is_active') ? 'has-error' : ''}}">
                    <label>{!! Form::radio('is_active', '1', $data->status  == '1', ['class'=>'required']) !!} Active</label>
                    <label>{!! Form::radio('is_active', '0', $data->status  == '0', ['class'=>' required']) !!} Inactive</label>
                    {!! $errors->first('is_active','<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="col-md-12">
                <div class="col-md-3">
                    <a href="{{ url('settings/park-info') }}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                </div>
                <div class="col-md-6 text-center">
                    {!! CommonFunction::showAuditLog($data->updated_at, $data->updated_by) !!}
                </div>
                <div class="col-md-3">
                    @if(ACL::getAccsessRight('settings','E'))
                    <button type="submit" class="btn btn-success  pull-right">
                        <i class="fa fa-chevron-circle-right"></i> <b>Save</b></button>
                    @endif
                </div>
            </div><!-- /.col-md-12 -->

            {!! Form::close() !!}<!-- /.form end -->

            <div class="overlay" style="display: none;">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div>
    </div>

</div>
@endsection

@section('footer-script')
<script>
    var _token = $('input[name="_token"]').val();

    $(document).ready(function () {
        $("#info").validate({
            errorPlacement: function () {
                return false;
            }
        });
    });
</script>
@endsection <!--- footer script--->