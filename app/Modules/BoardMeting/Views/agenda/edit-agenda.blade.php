@extends('layouts.admin')

@section('content')

<?php
//$accessMode = ACL::getAccsessRight('BoardMeting');
//if (!ACL::isAllowed($accessMode, 'A')) {
//    die('You have no access right! Please contact with system admin for more information.');
//}
$board_meeting_id = Encryption::encodeId($agendaData->board_meting_id);
?>
@include('BoardMeting::progress-bar')
@include('partials.messages')
<div class="col-lg-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <b>{!! trans('messages.edit_agenda') !!}</b>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            {!! Form::open(array('url' => '/board-meting/agenda/update-agenda/'.$id,'method' => 'patch', 'class' => 'form-horizontal smart-form', 'id' => 'entry-form',
            'enctype' =>'multipart/form-data','role' => 'form')) !!}

            <div class="col-md-12">

                <div class="row">
                    <div class="col-md-12 form-group">
                        {!! Form::label('name',trans('messages.name_of_agenda'), ['class'=>'col-md-3 required-star']) !!}
                        <div class="col-md-5 {{$errors->has('name') ? 'has-error': ''}}">
                            {!! Form::text('name', $agendaData->name, ['class' => ' col-md-12 form-control bnEng input-sm required']) !!}
                            {!! Form::hidden('board_meeting_id',  $board_meeting_id, ['class' => 'col-md-12 form-control input-sm required']) !!}
                            {!! $errors->first('name','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="col-md-12 form-group">
                        {!! Form::label('process_type_id',trans('messages.process_type'), ['class'=>'col-md-3 required-star']) !!}
                        <div class="col-md-5 {{$errors->has('process_type_id') ? 'has-error': ''}}">
                            {!! Form::select('process_type_id', $process_type,$agendaData->process_type_id, ['class' => 'col-md-12 form-control input-sm required']) !!}
                        </div>
                    </div>
                </div>

                <div>
                    <a href="{{ url('/board-meting/lists') }}">
                        {!! Form::button('<i class="fa fa-times"></i> '.trans('messages.close') , array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                    @if(ACL::getAccsessRight('BoardMeting','A')|| (isset($chairmen) && $chairmen->user_email == Auth::user()->user_email))
                    <button type="submit" class="btn btn-primary pull-right">
                        <i class="fa fa-chevron-circle-right"></i> {!! trans('messages.save') !!}</button>
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