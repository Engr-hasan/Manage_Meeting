@extends('layouts.admin')

@section('content')

<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'A')) {
    die('You have no access right! Please contact with system admin for more information.');
}
?>


@include('partials.messages')

<div class="col-lg-12">

    <div class="panel panel-primary">
        <div class="panel-heading">
            <b>Changing details of {!! $data->hs_code !!} </b>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            {!! Form::open(array('url' => '/settings/update-hs-code/'.$encrypted_id,'method' => 'patch', 'class' => 'form-horizontal smart-form', 'id' => 'edit-form',
            'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}


            <div class="col-md-12">

                <div class="row">
                    <div class="col-md-12 form-group">
                        {!! Form::label('hs_code','HS CODE', ['class'=>'col-md-3 required-star']) !!}
                        <div class="col-md-4 {{$errors->has('hs_code') ? 'has-error': ''}}">
                            {!! Form::text('hs_code', $data->hs_code, ['class' => 'col-md-12 input-sm required']) !!}
                            {!! $errors->first('hs_code','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-md-12 form-group">
                        {!! Form::label('product_name','Product Name', ['class'=>'col-md-3 required-star']) !!}
                        <div class="col-md-4 {{$errors->has('product_name') ? 'has-error': ''}}">
                            {!! Form::text('product_name', $data->product_name, ['class' => 'col-md-12 input-sm required']) !!}
                            {!! $errors->first('product_name','<span class="help-block">:message</span>') !!}
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
                        <a href="{{ url('/settings/hs-codes') }}">
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
        $("#edit-form").validate({
            errorPlacement: function () {
                return false;
            }
        });
    });
</script>
@endsection <!--- footer script--->