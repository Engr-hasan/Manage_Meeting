@extends('layouts.admin')

@section('page_heading', 'High Commission Update')

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
            <b>Changing {!! $data->desk_name !!} </b>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            {!! Form::open(array('url' => '/settings/update-user-desk/'.$encrypted_id,'method' => 'patch', 'class' => 'form-horizontal smart-form', 'id' => 'user-desk-form',
            'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
            
            <div class="form-group col-md-8 {{$errors->has('desk_name') ? 'has-error' : ''}}">
                {!! Form::label('desk_name','Desk Name: ',['class'=>'col-md-4  required-star']) !!}
                <div class="col-md-7">
                    {!! Form::text('desk_name', $data->desk_name, ['class'=>'form-control required', 'data-rule-maxlength'=>'40']) !!}
                    {!! $errors->first('desk_name','<span class="help-block">:message</span>') !!}
                </div>
            </div>
            
            <div class="form-group col-md-8 {{$errors->has('desk_status') ? 'has-error' : ''}}">
                {!! Form::label('desk_status','Desk Status: ',['class'=>'col-md-4 required-star']) !!}
                <div class="col-md-7">
                    <label>{!! Form::radio('desk_status', '1',  $data->desk_status  == '1', ['class'=>'required']) !!} Active</label>
                    &nbsp;&nbsp;
                    <label>{!! Form::radio('desk_status', '0',  $data->desk_status  == '0', ['class'=>'required']) !!} Inactive</label>
                </div>
            </div>
            
            <div class="form-group col-md-8 {{$errors->has('delegate_to_desk') ? 'has-error' : ''}}">
                {!! Form::label('delegate_to_desk','Delegation Desk: ',['class'=>'col-md-4']) !!}
                <div class="col-md-7">
                    {!! Form::select('delegate_to_desk', $desks, $data->delegate_to_desk, ['class'=>'form-control', 'data-rule-maxlength'=>'40', 
                    'placeholder' => 'Select One']) !!}
                    {!! $errors->first('delegate_to_desk','<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="col-md-12">
                <div class="col-md-3">
                    <a href="{{ url('/settings/user-desk') }}">
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
        $("#user-desk-form").validate({
            errorPlacement: function () {
                return false;
            }
        });
    });
</script>
@endsection <!--- footer script--->