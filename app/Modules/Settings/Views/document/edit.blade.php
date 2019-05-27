@extends('layouts.admin')

@section('page_heading','Document Edit')

@section('content')
<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'E')) {
    die('You have no access right! Please contact system admin for more information');
}
?>

@include('partials.messages')

<div class="col-lg-12">

    <div class="panel panel-primary">
        <div class="panel-heading">
            <b>Details of {!! $data->doc_name !!}</b>
        </div>

        <div class="panel-body">
            <div class="col-sm-12">
            {!! Form::open(array('url' => '/settings/update-document/'.$id,'method' => 'patch', 'class' => 'form-horizontal', 'id' => 'info',
            'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

            <div class="form-group {{$errors->has('doc_name') ? 'has-error' : ''}}">
                {!! Form::label('doc_name','Name: ',['class'=>'col-md-3  required-star']) !!}
                <div class="col-md-5">
                    {!! Form::text('doc_name',$data->doc_name, ['class'=>'form-control required input-sm']) !!}
                </div>
            </div>
            <div class="form-group {{$errors->has('service_id') ? 'has-error' : ''}}">
                {!! Form::label('service_id','Process Type',['class'=>'col-md-3 required-star']) !!}
                <div class="col-md-5">
                    {!! Form::select('service_id', $services, $data->process_type_id, ['class' => 'form-control required input-sm', 'placeholder' => 'Select One']) !!}
                </div>
            </div>
            <div class="form-group {{$errors->has('doc_priority') ? 'has-error' : ''}}">
                {!! Form::label('doc_priority','Priority',['class'=>'col-md-3']) !!}
                <div class="col-md-5">
                    {!! Form::select('doc_priority', [''=>'Select One','1'=>'Mandatory','0'=>'Not Mandatory'], $data->doc_priority, ['class' => 'form-control input-sm']) !!}
                </div>
            </div>

            <div class="col-md-12">
                <div class="col-md-2">
                    <a href="{{ url('/settings/document') }}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                </div>
                <div class="col-md-6 col-md-offset-1">
                    {!! CommonFunction::showAuditLog($data->updated_at, $data->updated_by) !!}
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success  pull-right">
                        <i class="fa fa-chevron-circle-right"></i> <b>Save</b></button>
                </div>
            </div><!-- /.col-md-12 -->

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
        $("#info").validate({
            errorPlacement: function () {
                return false;
            }
        });
    });
</script>
@endsection <!--- footer script--->