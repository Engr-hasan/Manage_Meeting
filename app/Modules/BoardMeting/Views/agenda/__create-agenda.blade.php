@extends('layouts.admin')

@section('content')

<?php
//$accessMode = ACL::getAccsessRight('BoardMeting');
//if (!ACL::isAllowed($accessMode, 'A')) {
//    die('You have no access right! Please contact with system admin for more information.');
//}
$board_meeting_id =  Request::segment(4);
?>
@include('BoardMeting::progress-bar')
@include('partials.messages')
<div class="col-lg-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <b>{!! trans('messages.list_agenda') !!}</b>
        </div>
        @include('BoardMeting::board-meeting-info')
        <!-- /.panel-heading -->
        <div class="panel-body">
            {!! Form::open(array('url' => '/board-meting/agenda/store-agenda','method' => 'post', 'class' => 'form-horizontal smart-form', 'id' => 'entry-form',
            'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

            <div class="col-md-12">

                <div class="row">
                    <div class="col-md-12 form-group">
                        {!! Form::label('name',trans('messages.name_of_agenda'), ['class'=>'col-md-3 required-star']) !!}
                        <div class="col-md-5 {{$errors->has('name') ? 'has-error': ''}}">
                            {!! Form::text('name', null, ['class' => 'col-md-12 bnEng form-control input-sm required']) !!}
                            {!! Form::hidden('board_meting_id',$board_meeting_id, ['class' => 'col-md-12 form-control input-sm required']) !!}
                            {!! $errors->first('name','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    {{--<div class="col-md-12 form-group">--}}
                        {{--{!! Form::label('name',trans('messages.description'), ['class'=>'col-md-3 required-star']) !!}--}}
                        {{--<div class="col-md-5 {{$errors->has('name') ? 'has-error': ''}}">--}}
                            {{--{!! Form::textarea('description', null, ['class' => 'col-md-12 form-control input-sm required']) !!}--}}

                            {{--{!! $errors->first('description','<span class="help-block">:message</span>') !!}--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-md-12 form-group">--}}
                        {{--{!! Form::label('agenda_file',trans('messages.agenda_file'), ['class'=>'col-md-3 required-star']) !!}--}}
                        {{--<div class="col-md-5 {{$errors->has('agenda_file') ? 'has-error': ''}}">--}}
                            {{--<input type="file" name="agenda_file[]" class="required form-control">--}}
                            {{--<span style="font-size: 11px;color: #8e8989;">[File Type Must be pdf,xls,xlsx,ppt,pptx,docx,doc Max size: 3MP ]</span>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="col-md-12 form-group">
                        {!! Form::label('process_type_id',trans('messages.process_type'), ['class'=>'col-md-3']) !!}
                        <div class="col-md-5 {{$errors->has('process_type_id') ? 'has-error': ''}}">
                            {!! Form::select('process_type_id', $process_type,'', ['class' => 'col-md-12 form-control input-sm required']) !!}
                        </div>
                    </div>
                    {{--<div class="col-md-12 form-group">--}}
                        {{--{!! Form::label('is_active','Status', ['class'=>'col-md-3 required-star']) !!}--}}
                        {{--<div class="col-md-5 {{$errors->has('is_active') ? 'has-error': ''}}">--}}
                            {{--{!! Form::select('is_active', [''=>'Select One', '1'=>'Active', '0'=>'Inactive'],'', ['class' => 'col-md-12 form-control input-sm required']) !!}--}}
                            {{--{!! $errors->first('is_active','<span class="help-block">:message</span>') !!}--}}
                        {{--</div>--}}
                    {{--</div>--}}
                </div>

                <div>
                    <a href="{{ url('/board-meting/lists') }}">
                        {!! Form::button('<i class="fa fa-times"></i>'. trans('messages.close'), array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                    @if(ACL::getAccsessRight('BoardMeting','A') || (isset($chairmen) && $chairmen->user_email == Auth::user()->user_email))
                    <button type="submit" class="btn btn-primary tostar pull-right">
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
            },
            submitHandler: function() {
               $('.tostar').prop('disabled',true);
                this.form.submit();
            },

        });

        // $("#submitbutton").click(
        //     function() {
        //         alert("Sending...");
        //         window.location.replace("path to url");
        //     }
        // );
    });
</script>
@endsection <!--- footer script--->