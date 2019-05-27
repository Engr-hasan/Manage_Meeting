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
    <div class="panel panel-info">
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
                        {!! Form::button('<i class="fa fa-times"></i> '. trans('messages.close'), array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                    @if(ACL::getAccsessRight('BoardMeting','A') || (isset($chairmen) && $chairmen->user_email == Auth::user()->user_email))
                        <a href="{{ url('board-meting/committee/'.$board_meeting_id) }}" class="btn btn-info  pull-right">
                            <i class="fa fa-chevron-circle-right"></i> {!! trans('messages.next') !!}
                        </a>
                    <button style="margin-right: 2px;" type="submit" class="btn btn-primary tostar pull-right">
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

    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="pull-left" style="line-height: 35px;">
                <strong><i class="fa fa-list"></i> {{ trans('messages.agenda_list') }}</strong>
            </div>
            <div class="clearfix"></div>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <div class="table-responsive">
                <table id="list" class="table table-striped table-bordered dt-responsive " cellspacing="0"
                       width="100%">
                    <thead>
                    <tr>
                        <th>{!! trans('messages.name_of_agenda') !!}</th>
                        <th>{!! trans('messages.description') !!}</th>
                        <th>{!! trans('messages.process_type') !!}</th>
                        <th>{!! trans('messages.status') !!}</th>
                        <th>{!! trans('messages.created_at') !!}</th>
                        <th>{!! trans('messages.action') !!}</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div><!-- /.table-responsive -->

        </div><!-- /.panel -->
    </div>
</div>




@endsection


@section('footer-script')
@include('Users::partials.datatable')
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

    $(function () {
        var board_id = '{{$board_meeting_id}}';
        agendaList = $('#list').DataTable({
            processing: true,
            serverSide: true,

            ajax: {
                url: '{{url("board-meting/agenda/get-agenda-data")}}',
                method: 'post',
                data: function (d) {
                    d.board_meting_id = board_id;
                    d._token = $('input[name="_token"]').val();

                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'description', name: 'description'},
                {data: 'process_type_name', name: 'process_type_name'},
                {data: 'is_active', name: 'is_active'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "aaSorting": []
        });
    });

    function deleteAgenda(agenda_id) {
        toastr.error("<br /><br /><button type='button' style='color:black' id='confirmationRevertYes' class='btn clear'>Yes</button> <button style='margin-left: 120px;color: black' class='btn clear' type='button'>No</button>", 'Are you sure you want to delete?',
            {
                closeButton: true,
                allowHtml: true,
                timeOut: 0,
                extendedTimeOut: 0,
                positionClass: "toast-top-center",
                onShown: function (toast) {
                    $("#confirmationRevertYes").click(function () {
                        var _token = $('input[name="_token"]').val();
                        $.ajax({
                            type: "post",
                            url: "<?php echo url(); ?>/board-meting/agenda/deleteAgenda",
                            data: {
                                _token: _token,
                                agenda_id: agenda_id
                            },
                            success: function (response) {
                                if (response.responseCode == 1) {
                                    agendaList.ajax.reload();
                                }
                            }
                        });
                    });
                }
            });
    }
</script>
@endsection <!--- footer script--->