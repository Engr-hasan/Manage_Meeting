@extends('layouts.admin')

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
            <b>Details of the new port</b>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            {!! Form::open(array('url' => '/settings/store-port','method' => 'post', 'class' => 'form-horizontal smart-form', 'id' => 'port-form',
            'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

            <div class="col-md-12">

                <div class="row">
                    <div class="col-md-12 form-group">
                        {!! Form::label('country_iso','Country', ['class'=>'col-md-3 required-star']) !!}
                        <div class="col-md-4 {{$errors->has('country_iso') ? 'has-error': ''}}">
                            {!! Form::select('country_iso', $countries, null, ['class' => 'col-md-12 input-sm required', 'placeholder' => 'Select One']) !!}
                            {!! $errors->first('country_iso','<span class="help-block">:message</span>') !!}                               
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

                <div>
                    <a href="{{ url('/settings/ports') }}">
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
        $("#port-form").validate({
            errorPlacement: function () {
                return false;
            }
        });
    });
</script>
@endsection <!--- footer script--->