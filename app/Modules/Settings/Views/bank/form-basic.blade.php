@extends('layouts.admin')

@section('page_heading',trans('messages.bank_form'))

@section('content')

<div class="col-lg-12">

    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}
    <?php
    $accessMode = ACL::getAccsessRight('settings');
    if (!ACL::isAllowed($accessMode, 'A'))
        die('no access right!');
    ?>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <b> {!! trans('messages.new_bank') !!}</b>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <div class="col-md-9">
                {!! Form::open(array('url' => '/settings/store-bank','method' => 'patch', 'class' => 'form-horizontal', 'id' => 'bank-info',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}


                <div class="form-group col-md-12 {{$errors->has('name') ? 'has-error' : ''}}">
                    {!! Form::label('name','Name: ',['class'=>'col-md-2  required-star']) !!}
                    <div class="col-md-8">
                        {!! Form::text('name', '', ['class'=>'form-control input-sm textOnly required', 'data-rule-maxlength'=>'50']) !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('bank_code') ? 'has-error' : ''}}">
                    {!! Form::label('bank_code','Code: ',['class'=>'col-md-2  required-star']) !!}
                    <div class="col-md-8">
                        {!! Form::text('bank_code', '', ['class'=>'form-control input-sm required', 'data-rule-maxlength'=>'100']) !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('email') ? 'has-error' : ''}}">
                    {!! Form::label('email','Email: ',['class'=>'col-md-2  required-star']) !!}
                    <div class="col-md-8">
                        {!! Form::text('email', '', ['class'=>'form-control input-sm email required', 'data-rule-maxlength'=>'60']) !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('phone') ? 'has-error' : ''}}">
                    {!! Form::label('phone','Phone: ',['class'=>'col-md-2  required-star']) !!}
                    <div class="col-md-8">
                        {!! Form::text('phone', '', ['class'=>'form-control input-sm required mobile_number_validation', 'data-rule-maxlength'=>'50', 'maxlength'=>"50"]) !!}
                        {!! $errors->first('phone','<span class="help-block">:message</span>') !!}
                        <span class="text-danger mobile_number_error"></span>
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('website') ? 'has-error' : ''}}">
                    {!! Form::label('website','Website: ',['class'=>'col-md-2']) !!}
                    <div class="col-md-8">
                        {!! Form::text('website', '', ['class'=>'form-control input-sm bnEng', 'data-rule-maxlength'=>'64']) !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('location') ? 'has-error' : ''}}">
                    {!! Form::label('location','Location: ',['class'=>'col-md-2  required-star']) !!}
                    <div class="col-md-8">
                        {!! Form::text('location', '', ['class'=>'form-control input-sm bnEng required', 'data-rule-maxlength'=>'60']) !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('address') ? 'has-error' : ''}}">
                    {!! Form::label('address','Address: ',['class'=>'col-md-2']) !!}
                    <div class="col-md-8">
                        {!! Form::text('address', '', ['class'=>'form-control input-sm bnEng', 'data-rule-maxlength'=>'100']) !!}
                    </div>
                </div>

                <div class="col-md-12">
                    <a href="{{ url('/settings/bank-list') }}">
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