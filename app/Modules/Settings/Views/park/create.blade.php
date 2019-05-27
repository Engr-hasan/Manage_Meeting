@extends('layouts.admin')

@section('content')
<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'A')) {
    die('You have no access right! Please contact system admin for more information');
}
?>

@include('partials.messages')

<div class="col-lg-12">

    <div class="panel panel-primary">
        <div class="panel-heading">
            <b>New Park</b>
        </div>

        <div class="panel-body">
            {!! Form::open(array('url' => '/settings/store-eco-zone','method' => 'post', 'class' => 'form-horizontal', 'id' => 'formId',
            'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
            <div class="col-md-12">
                <div class="form-group {{$errors->has('name') ? 'has-error' : ''}}">
                    {!! Form::label('name','Name of the Park : ',['class'=>'col-md-3  required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::text('name', null, ['class'=>'form-control required input-sm', 'placeholder' => 'e.g. Meghna EZ']) !!}
                    </div>
                </div>
                <div class="form-group {{$errors->has('district') ? 'has-error' : ''}}">
                    {!! Form::label('district','District',['class'=>'col-md-3 required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::select('district', $districts, null, ['class' => 'form-control required input-sm', 'placeholder' => 'Select One']) !!}
                    </div>
                </div>
                <div class="form-group {{$errors->has('upazilla') ? 'has-error' : ''}}">
                    {!! Form::label('upazilla','Upazila : ',['class'=>'col-md-3  required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::text('upazilla', null, ['class'=>'form-control required input-sm', 'placeholder' => 'e.g. Sonargaon']) !!}
                    </div>
                </div>
                <div class="form-group {{$errors->has('area') ? 'has-error' : ''}}">
                    {!! Form::label('area','Area (in square-meter) : ',['class'=>'col-md-3  required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::text('area', null, ['class'=>'form-control required input-sm onlyNumber', 'placeholder' => 'e.g. 100.45']) !!}
                    </div>
                </div>
                <div class="form-group {{$errors->has('remarks') ? 'has-error' : ''}}">
                    {!! Form::label('remarks','remarks (if any) : ',['class'=>'col-md-3']) !!}
                    <div class="col-md-5">
                        {!! Form::textarea('remarks', null, ['class'=>'form-control input-sm', 'placeholder' => 'e.g. For usage inside Bangladesh', 'size' => '5x2']) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <a href="{{ url('/settings/park-info') }}">
                    {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                </a>
                @if(ACL::getAccsessRight('settings','A'))
                <button type="submit" class="btn btn-success pull-right">
                    <i class="fa fa-chevron-circle-right"></i> <b>Save</b></button>
                @endif
            </div>

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
        $("#formId").validate({
            errorPlacement: function () {
                return false;
            }
        });
    });
</script>
@endsection <!--- footer script--->