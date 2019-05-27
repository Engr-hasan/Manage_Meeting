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
            <b>{!! trans('messages.industrial_category_from') !!}</b>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            {!! Form::open(array('url' => '/settings/store-indus-cat','method' => 'post', 'class' => 'form-horizontal smart-form', 'id' => 'entry-form',
            'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

            <div class="col-md-12">

                <div class="row">
                    <div class="col-md-12 form-group">
                        {!! Form::label('name','Industrial Category Name', ['class'=>'col-md-3 required-star']) !!}
                        <div class="col-md-5 {{$errors->has('name') ? 'has-error': ''}}">
                            {!! Form::text('name', null, ['class' => 'col-md-12 input-sm required']) !!}
                            {!! $errors->first('name','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('color_id','Color Name',['class'=>'col-md-3 required-star']) !!}
                        <div class="col-md-5 {{$errors->has('color_id') ? 'has-error' : ''}}">
                            {!! Form::select('color_id', $colors, 'null', ['class' => 'form-control required input-sm', 'placeholder' => 'Select One']) !!}
                        </div>
                    </div>
                </div>

                <div>
                    <a href="{{ url('/settings/indus-cat') }}">
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
        $("#entry-form").validate({
            errorPlacement: function () {
                return false;
            }
        });
    });
</script>
@endsection <!--- footer script--->