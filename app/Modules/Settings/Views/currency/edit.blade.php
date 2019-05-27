@extends('layouts.admin')

@section('page_heading', 'High Commission Update')

@section('content')

@include('partials.messages')

<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'A')) {
    die('You have no access right! Please contact with system admin for more information.');
}
?>

<div class="col-lg-12">

    <div class="panel panel-primary">
        <div class="panel-heading">
            <b>{!! trans('messages.new_currency_form_title') !!} {!! $data->name !!} </b>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            {!! Form::open(array('url' => '/settings/update-currency/'.$encrypted_id,'method' => 'patch', 'class' => 'form-horizontal smart-form', 
            'id' => 'currency-form','enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 form-group">
                        {!! Form::label('code','Code', ['class'=>'col-md-3 required-star']) !!}
                        <div class="col-md-4 {{$errors->has('code') ? 'has-error': ''}}">
                            {!! Form::text('code', $data->code, ['class' => 'col-md-12 input-sm required']) !!}
                            {!! $errors->first('code','<span class="help-block">:message</span>') !!}                               
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        {!! Form::label('name','Name', ['class'=>'col-md-3 required-star']) !!}
                        <div class="col-md-4 {{$errors->has('name') ? 'has-error': ''}}">
                            {!! Form::text('name', $data->name, ['class' => 'col-md-12 input-sm required']) !!}
                            {!! $errors->first('name','<span class="help-block">:message</span>') !!}                               
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        {!! Form::label('usd_value','USD($) Value', ['class'=>'col-md-3']) !!}
                        <div class="col-md-4 {{$errors->has('usd_value') ? 'has-error': ''}}">
                            {!! Form::text('usd_value', $data->usd_value, ['class' => 'col-md-12 input-sm']) !!}
                            {!! $errors->first('usd_value','<span class="help-block">:message</span>') !!}                               
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        {!! Form::label('bdt_value','BDT Value', ['class'=>'col-md-3']) !!}
                        <div class="col-md-4 {{$errors->has('bdt_value') ? 'has-error': ''}}">
                            {!! Form::text('bdt_value', $data->bdt_value, ['class' => 'col-md-12 input-sm']) !!}
                            {!! $errors->first('bdt_value','<span class="help-block">:message</span>') !!}                               
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-12">
                        {!! Form::label('is_active','Active Status: ',['class'=>'col-md-3 required-star']) !!}
                        <div class="col-md-4 {{$errors->has('is_active') ? 'has-error' : ''}}">
                            <label>{!! Form::radio('is_active', '1', $data->is_active  == '1', ['class'=>'required']) !!} Active</label>
                            <label>{!! Form::radio('is_active', '0', $data->is_active  == '0', ['class'=>' required']) !!} Inactive</label>
                            {!! $errors->first('is_active','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-3">
                        <a href="{{ url('/settings/currency') }}">
                            {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                        </a>
                    </div>
                    <div class="col-md-6 text-center">
                        {!! CommonFunction::showAuditLog($data->updated_at, $data->updated_by) !!}
                    </div>
                    <div class="col-md-3">
                        @if(ACL::getAccsessRight('settings','A'))
                        <button type="submit" class="btn btn-primary pull-right">
                            <i class="fa fa-chevron-circle-right"></i> Save</button>
                        @endif
                    </div>
                </div>

            </div><!--/col-md-12-->

            {!! Form::close() !!}<!-- /.form end -->

            <div class="overlay" style="display: none;">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div><!-- /.box -->
    </div>
</div>

@endsection


@section('footer-script')

<script>
    var _token = $('input[name="_token"]').val();

    var age = -1;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function () {
        $("#currency-form").validate({
            errorPlacement: function () {
                return false;
            }
        });
    });
</script>
@endsection <!--- footer script--->