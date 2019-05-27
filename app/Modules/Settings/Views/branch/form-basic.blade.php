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
            <b> {!! trans('messages.branch') !!}</b>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <div class="col-md-9">
                {!! Form::open(array('url' => '/settings/store-branch','method' => 'patch', 'class' => 'form-horizontal', 'id' => 'bank-info',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}


                <div class="form-group col-md-12 {{$errors->has('bank_id') ? 'has-error' : ''}}">
                    {!! Form::label('bank_id','Bank Name: ',['class'=>'col-md-2  required-star']) !!}
                    <div class="col-md-8">
                        {!! Form::select('bank_id',$banks,'',['class'=>'form-control input-sm required','readonly'=>'readonly']) !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('branch_name') ? 'has-error' : ''}}">
                    {!! Form::label('branch_name','Branch Name: ',['class'=>'col-md-2  required-star']) !!}
                    <div class="col-md-8">
                        {!! Form::text('branch_name', '', ['class'=>'form-control textOnly input-sm required']) !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('branch_code') ? 'has-error' : ''}}">
                    {!! Form::label('branch_code','Branch Code: ',['class'=>'col-md-2  required-star']) !!}
                    <div class="col-md-8">
                        {!! Form::text('branch_code', '', ['class'=>'form-control onlyNumber input-sm required', 'maxlength'=>'6']) !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('address') ? 'has-error' : ''}}">
                    {!! Form::label('address','Address: ',['class'=>'col-md-2 required-star']) !!}
                    <div class="col-md-8">
                        {!! Form::textarea('address', '', ['class'=>'form-control input-sm required','rows'=>'4']) !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('manager_info') ? 'has-error' : ''}}">
                    {!! Form::label('manager_info','Manager Info: ',['class'=>'col-md-2']) !!}
                    <div class="col-md-8">
                        {!! Form::textarea('manager_info', '', ['class'=>'form-control input-sm','rows'=>'4']) !!}
                        {!! $errors->first('manager_info','<span class="help-block">:message</span>') !!}
                        <span class="text-danger"></span>
                    </div>
                </div>

                <div class="col-md-12">
                    <a href="{{ url('/settings/branch-list') }}">
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