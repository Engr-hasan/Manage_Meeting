@extends('layouts.admin')

@section('page_heading',trans('messages.bank_edit'))

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
        <div class="panel-heading">
            <b> Change the details of {{$data->name}}  </b>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            {!! Form::open(array('url' => '/settings/update-bank/'.$id,'method' => 'patch', 'class' => 'form-horizontal', 'id' => 'bank-info',
            'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

            <div class="form-group col-md-12 {{$errors->has('name') ? 'has-error' : ''}}">
                {!! Form::label('name','Name: ',['class'=>'col-md-2  required-star']) !!}
                <div class="col-md-6">
                    {!! Form::text('name', $data->name, ['class'=>'form-control input-sm textOnly required', 'data-rule-maxlength'=>'60']) !!}
                </div>
            </div>

            <div class="form-group col-md-12 {{$errors->has('bank_code') ? 'has-error' : ''}}">
                {!! Form::label('bank_code','Code: ',['class'=>'col-md-2  required-star']) !!}
                <div class="col-md-6">
                    {!! Form::text('bank_code', $data->bank_code, ['class'=>'form-control input-sm required', 'data-rule-maxlength'=>'100']) !!}
                </div>
            </div>

            <div class="form-group col-md-12 {{$errors->has('email') ? 'has-error' : ''}}">
                {!! Form::label('email','Email: ',['class'=>'col-md-2  required-star']) !!}
                <div class="col-md-6">
                    {!! Form::text('email', $data->email, ['class'=>'form-control input-sm email required', 'data-rule-maxlength'=>'40']) !!}
                </div>
            </div>

            <div class="form-group col-md-12 {{$errors->has('phone') ? 'has-error' : ''}}">
                {!! Form::label('phone','Phone: ',['class'=>'col-md-2  required-star']) !!}
                <div class="col-md-6">
                    {!! Form::text('phone', $data->phone, ['class'=>'form-control input-sm required mobile_number_validation', 'data-rule-maxlength'=>'50', 'maxlength'=>"50"]) !!}
                </div>
            </div>

            <div class="form-group col-md-12 {{$errors->has('website') ? 'has-error' : ''}}">
                {!! Form::label('website','Website: ',['class'=>'col-md-2']) !!}
                <div class="col-md-6">
                    {!! Form::text('website', $data->website, ['class'=>'form-control input-sm bnEng', 'data-rule-maxlength'=>'64']) !!}
                </div>
            </div>

            <div class="form-group col-md-12 {{$errors->has('location') ? 'has-error' : ''}}">
                {!! Form::label('location','Location: ',['class'=>'col-md-2  required-star']) !!}
                <div class="col-md-6">
                    {!! Form::text('location', $data->location, ['class'=>'form-control input-sm required bnEng', 'data-rule-maxlength'=>'60']) !!}
                </div>
            </div>

            <div class="form-group col-md-12 {{$errors->has('address') ? 'has-error' : ''}}">
                {!! Form::label('address','Address: ',['class'=>'col-md-2']) !!}
                <div class="col-md-6">
                    {!! Form::text('address', $data->address, ['class'=>'form-control input-sm  bnEng', 'data-rule-maxlength'=>'100']) !!}
                </div>
            </div>

            <div class="form-group col-md-12">
                {!! Form::label('is_active','Active Status: ',['class'=>'col-md-2 required-star']) !!}
                <div class="col-md-6 {{$errors->has('is_active') ? 'has-error' : ''}}">
                    <label>{!! Form::radio('is_active', '1', $data->is_active  == '1', ['class'=>'required']) !!} Active</label>
                    <label>{!! Form::radio('is_active', '0', $data->is_active  == '0', ['class'=>' required']) !!} Inactive</label>
                    {!! $errors->first('is_active','<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="col-md-12">
                <div class="col-md-2">
                    <a href="{{ url('/settings/bank-list') }}">
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

<script>
    var _token = $('input[name="_token"]').val();

    var age = -1;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function () {
        $("#bank-info").validate({
            errorPlacement: function () {
                return false;
            }
        });
    });
</script>
@endsection <!--- footer script--->