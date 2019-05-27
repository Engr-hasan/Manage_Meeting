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
            <strong>{!! trans('messages.document_create') !!}</strong>
        </div>

        <div class="panel-body">
            <div class="col-sm-10">
            {!! Form::open(array('url' => '/settings/store-document','method' => 'post', 'class' => 'form-horizontal', 'id' => 'formId',
            'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

            <div class="form-group {{$errors->has('doc_name') ? 'has-error' : ''}}">
                {!! Form::label('doc_name','Name: ',['class'=>'col-md-3  required-star']) !!}
                <div class="col-md-5">
                    {!! Form::text('doc_name', null, ['class'=>'form-control required input-sm']) !!}
                </div>
            </div>
            <div class="form-group {{$errors->has('service_id') ? 'has-error' : ''}}">
                {!! Form::label('service_id','Process Type',['class'=>'col-md-3 required-star']) !!}
                <div class="col-md-5">
                    {!! Form::select('service_id', $services, null, ['class' => 'form-control required input-sm', 'placeholder' => 'Select One']) !!}
                </div>
            </div>
            <div class="form-group {{$errors->has('doc_priority') ? 'has-error' : ''}}">
                {!! Form::label('doc_priority','Priority',['class'=>'col-md-3']) !!}
                <div class="col-md-5">
                    {!! Form::select('doc_priority', [''=>'Select One','1'=>'Mandatory','0'=>'Not Mandatory'], null, ['class' => 'form-control input-sm']) !!}
                </div>
            </div>

            <div class="col-md-12">
                <a href="{{ url('/settings/document') }}">
                    {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                </a>
                <button type="submit" class="btn btn-success pull-right">
                    <i class="fa fa-chevron-circle-right"></i> <b>Save</b></button>
            </div>

            {!! Form::close() !!}<!-- /.form end -->

            <div class="overlay" style="display: none;">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
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