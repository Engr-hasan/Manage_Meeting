@extends('layouts.admin')

@section('page_heading', 'High Commission Creation')

@section('content')

<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'A')) {
    die('You have no access right! Please contact with system admin for more information.');
}
?>

<div class="col-lg-12">

    @include('partials.messages')

    <div class="panel panel-primary">
        <div class="panel-heading">
            <b>Details of new Currency</b>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            {!! Form::open(array('url' => '/settings/store-currency','method' => 'post', 'class' => 'form-horizontal smart-form', 'id' => 'currency-form',
            'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

            <div class="col-md-12">

                <div class="row">
                    <div class="col-md-12 form-group">
                        {!! Form::label('code','Code', ['class'=>'col-md-3 required-star']) !!}
                        <div class="col-md-4 {{$errors->has('code') ? 'has-error': ''}}">
                            {!! Form::text('code', null, ['class' => 'col-md-12 input-sm required']) !!}
                            {!! $errors->first('code','<span class="help-block">:message</span>') !!}                               
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        {!! Form::label('name','Name', ['class'=>'col-md-3 required-star']) !!}
                        <div class="col-md-4 {{$errors->has('name') ? 'has-error': ''}}">
                            {!! Form::text('name', null, ['class' => 'col-md-12 input-sm required']) !!}
                            {!! $errors->first('name','<span class="help-block">:message</span>') !!}                               
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        {!! Form::label('usd_value','USD($) Value', ['class'=>'col-md-3']) !!}
                        <div class="col-md-4 {{$errors->has('code') ? 'has-error': ''}}">
                            {!! Form::text('usd_value', null, ['class' => 'col-md-12 input-sm']) !!}
                            {!! $errors->first('usd_value','<span class="help-block">:message</span>') !!}                               
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        {!! Form::label('bdt_value','BDT Value', ['class'=>'col-md-3']) !!}
                        <div class="col-md-4 {{$errors->has('code') ? 'has-error': ''}}">
                            {!! Form::text('bdt_value', null, ['class' => 'col-md-12 input-sm']) !!}
                            {!! $errors->first('bdt_value','<span class="help-block">:message</span>') !!}                               
                        </div>
                    </div>
                </div>

                <div>
                    <a href="{{ url('/settings/currency') }}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                    @if(ACL::getAccsessRight('settings','A'))
                    <button type="submit" class="btn btn-primary pull-right">
                        <i class="fa fa-chevron-circle-right"></i> Save</button>
                    @endif
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