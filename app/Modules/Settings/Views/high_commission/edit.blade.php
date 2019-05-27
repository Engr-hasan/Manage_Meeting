@extends('layouts.admin')

@section('page_heading', 'High Commission Update')

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
            <b>Details of {!! $data->name !!} of {!! $hc_country !!} </b>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            {!! Form::open(array('url' => '/settings/update-high-commission/'.$encrypted_id,'method' => 'patch', 'class' => 'form-horizontal smart-form', 'id' => 'high-commission-form',
            'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}


            <div class="form-group col-md-8 {{$errors->has('country_code') ? 'has-error' : ''}}">
                {!! Form::label('country_code','Country: ',['class'=>'col-md-3  required-star']) !!}
                <div class="col-md-7">
                    {!! Form::select('country_code', $countries, $data->country_id, ['class'=>'form-control required', 'placeholder' => 'Select One', 'data-rule-maxlength'=>'40']) !!}
                    {!! $errors->first('country_code','<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="form-group col-md-8 {{$errors->has('name') ? 'has-error' : ''}}">
                {!! Form::label('name','Name: ',['class'=>'col-md-3  required-star']) !!}
                <div class="col-md-7">
                    {!! Form::text('name', $data->name, ['class'=>'form-control required', 'data-rule-maxlength'=>'40']) !!}
                    {!! $errors->first('name','<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="form-group col-md-8 {{$errors->has('address') ? 'has-error' : ''}}">
                {!! Form::label('address','Address: ',['class'=>'col-md-3  required-star']) !!}
                <div class="col-md-7">
                    {!! Form::textarea('address', $data->address, ['class'=>'form-control required', 'size' => '10x5', 'data-rule-maxlength'=>'255']) !!}
                    {!! $errors->first('address','<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="form-group col-md-8 {{$errors->has('phone') ? 'has-error' : ''}}">
                {!! Form::label('phone','Phone: ', ['class'=>'col-md-3']) !!}
                <div class="col-md-7">
                    {!! Form::text('phone', $data->phone, ['class'=>'phone form-control', 'data-rule-maxlength'=>'64']) !!}
                    {!! $errors->first('phone','<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="form-group col-md-8 {{$errors->has('email') ? 'has-error' : ''}}">
                {!! Form::label('email','Email: ',['class'=>'col-md-3  required-star']) !!}
                <div class="col-md-7">
                    {!! Form::text('email', $data->email, ['class'=>'form-control  email  required', 'data-rule-maxlength'=>'100']) !!}
                    {!! $errors->first('email','<span class="help-block">:message</span>') !!}
                </div>
            </div>
            
                    <div class="form-group col-md-8">
                        {!! Form::label('is_active','Active Status: ',['class'=>'col-md-3 required-star']) !!}
                        <div class="col-md-7 {{$errors->has('is_active') ? 'has-error' : ''}}">
                            <label>{!! Form::radio('is_active', '1', $data->is_active  == '1', ['class'=>'required']) !!} Active</label>
                            <label>{!! Form::radio('is_active', '0', $data->is_active  == '0', ['class'=>' required']) !!} Inactive</label>
                            {!! $errors->first('is_active','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

            <div class="col-md-12">
                <div class="col-md-3">
                    <a href="{{ url('/settings/high-commission') }}">
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

<script>
    var _token = $('input[name="_token"]').val();

    var age = -1;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function () {
        $("#high-commission-form").validate({
            errorPlacement: function () {
                return false;
            }
        });
    });
</script>
@endsection <!--- footer script--->