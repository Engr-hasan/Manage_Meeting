@extends('layouts.admin')
@section('content')
    <?php $accessMode = ACL::getAccsessRight('BoardMeting');
    if (!ACL::isAllowed($accessMode, 'V')) {
        die('no access right!');
    }
    ?>
    @include('BoardMeting::progress-bar')
    <div class="col-lg-12">
        @include('message.message')

        {{--board meeting info--}}
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="row">
                    <div class="panel-heading">
                        <div class="pull-left" style="line-height: 35px;">
                            <h5>
                                &nbsp; <i class="fa fa-list"></i>
                                <b>
                                   {{ trans('messages.titleboardmeeting') }}

                                </b>
                            </h5>

                        </div>
                        <div class="pull-right">

                            @if( (\App\Libraries\CommonFunction::getUserType() == '13x303'))
                                <a href="{{url('board-meting/edit/'.Encryption::encodeId($board_meeting_data->id))}}" class="btn btn-md btn-default open" ><i class="fa fa-edit"></i>    {{ trans('messages.editbm') }}</a>&nbsp;
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            {{--board meeting info Start--}}
            @include('BoardMeting::board-meeting-info')
            {{--board meeting info end--}}
            <br>

            <div class="panel panel-info">


                <div class="panel-heading">
                    <div class="pull-left" style="line-height: 35px;">
                        <strong><i class="fa fa-list"></i> {{ trans('messages.committeemembers') }}</strong>
                    </div>
                    <div class="pull-right">
                        <!--1x101 is Sys Admin, 7x712 is SB Admin, 11x422 is Bank Admin-->
                        @if(!in_array($board_meeting_data->status,[5,10]))
                            @if(ACL::getAccsessRight('BoardMeting','A'))
                                <a class="" href="{{ url('/board-meting/committee/'.$board_meeting_id) }}">
                                    {!! Form::button('<i class="fa fa-plus"></i> <b> ' .trans('messages.new_committee').'</b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                                </a>
                            @endif
                        @endif
                    </div>
                    <div class="clearfix"></div>


                </div>

                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="committeeList" class="table table-striped table-bordered dt-responsive "
                               cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>{!! trans('messages.meeting_member_name') !!}</th>
                                <th>{!! trans('messages.member_designation') !!}</th>
                                <th>{!! trans('messages.member_email') !!}</th>
                                <th>{!! trans('messages.member_mobile') !!}</th>
                                <th>{!! trans('messages.member_type') !!}</th>
                                <th>{!! trans('messages.created_at') !!}</th>
                                <th>{!! trans('messages.action') !!}</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div><!-- /.table-responsive -->
                </div><!-- /.panel-body -->

            </div><!--/col-md-12-->
            {{--list of committee end--}}
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="pull-left" style="line-height: 35px;">
                        <strong><i class="fa fa-list"></i> {{ trans('messages.agenda_list') }}</strong>
                    </div>
                    <div class="pull-right">


                        <!--1x101 is Sys Admin, 7x712 is SB Admin, 11x422 is Bank Admin-->


                        @if(!in_array($board_meeting_data->status,[5,10]))
                            @if(ACL::getAccsessRight('BoardMeting','A') || (isset($chairmen) && $chairmen->user_email == Auth::user()->user_email))
                                <a class="" href="{{ url('/board-meting/agenda/create-new-agenda/'.$board_meeting_id) }}">
                                    {!! Form::button('<i class="fa fa-plus"></i><b> ' .trans('messages.new_agenda').'</b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                                </a>
                            @endif
                        @endif
                        @if(in_array($board_meeting_data->status,[5,10,11]))

                            @if($board_meeting_data->meeting_agenda_path == null)
                                <a class="" href="{{ url('/board-meting/agenda/download/'.$board_meeting_id) }}">
                                    {!! Form::button('<i class="fa fa-plus"></i><b> ' .trans('messages.agenda_download').'</b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                                </a>
                            @else
                                <a  href="{{url($board_meeting_data->meeting_agenda_path)}}" download="">
                                    {!! Form::button('<i class="fa fa-download"></i><b> ' .trans('messages.agenda_download_pdf').'</b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                                </a>
                                <a href="{{ url('/board-meting/agenda/doc-download/'.$board_meeting_id) }}">
                                    {!! Form::button('<i class="fa fa-download"></i><b> ' .trans('messages.agenda_download_doc').'</b>', array('type' => 'button', 'class' => 'btn btn-warning')) !!}
                                </a>
                            @endif

                        @endif
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
            {{--<div class="panel panel-info">--}}
                {{--<div class="panel-heading">--}}
                    {{--<div class="pull-left" style="line-height: 35px;">--}}
                        {{--<strong><i class="fa fa-list"></i> {{ trans('messages.file') }}</strong>--}}
                    {{--</div>--}}
                    {{--<div class="clearfix"></div>--}}
                {{--</div>--}}
                {{--<!-- /.panel-heading -->--}}
                {{--<div class="panel-body">--}}
                    {{--@include('BoardMeting::agenda.doc-tab')--}}

                {{--</div><!-- /.panel -->--}}
            {{--</div>--}}

            @if( (isset($chairmen) && $chairmen->user_email == Auth::user()->user_email) && (!in_array($board_meeting_data->status,[5,10])) ) <!-- 5= fixed status 10=complete -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <button class="btn btn-info center-block fixed_meeting"  onclick="confirmFixed()">Fixed</button>
                </div>
            </div>
            @endif
            @if(!empty($chairmen->user_email))
            @if((Auth::user()->user_email == $chairmen->user_email) && $board_meeting_data->status == 5 && $pendingAgendaCount==0) <!-- 5= fixed status -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Open modal for @mdo</button>--}}

                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Reference No</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="form-group">
                                            <label for="ref_no" class="col-form-label">Reference No.:</label>
                                            <input type="text" class="form-control" name="ref_no" id="ref_no">
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary CompleteMeeting" >Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button   data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo" class="btn btn-info center-block">Accomplished</button>

                </div>
            </div>
            @endif
            @endif

        </div>
        <div class="col-md-12"><br><br></div>

        @endsection <!--content section-->
        @section('footer-script')
            @include('Users::partials.datatable')
            <script>

            $(document).ready(function(){

                $(".CompleteMeeting").click(function () {

                                        var _token = $('input[name="_token"]').val();
                                        var board_meeting_id = '{{$board_meeting_id}}';
                                        var ref_no = $('#ref_no').val();
                                        if(ref_no == ''){
                                            alert("Please Enter A Reference No. ");
                                            return false;
                                        }

                                        $.ajax({
                                            type: "get",
                                            url: "<?php echo url(); ?>/board-meting/complete-meeting",
                                            data: {
                                                _token: _token,
                                                board_meeting_id: board_meeting_id,
                                                ref_no: ref_no
                                            },
                                            success: function (response) {
                                                if (response.responseCode == 1) {
                                                    toastr.success('Meeting Complete successfully!!');
                                                    window.setTimeout(function () {
                                                        location.reload();
                                                    }, 800);

                                                }
                                            }
                                        });
                                    });
            });
                function confirmFixed() {
                    toastr.error("<br /><br /><button type='button' style='color:black' id='confirmationRevertYes' class='btn clear'>Yes</button> <button style='margin-left: 120px;color: black' class='btn clear' type='button'>No</button>", 'Are you sure you want to be fixed the meeting?',
                            {
                                closeButton: true,
                                allowHtml: true,
                                timeOut: 0,
                                extendedTimeOut: 0,
                                positionClass: "toast-top-center",
                                onShown: function (toast) {
                                    $("#confirmationRevertYes").click(function () {

                                        var _token = $('input[name="_token"]').val();
                                        var board_meeting_id = '{{$board_meeting_id}}';
                                        btn = $('.fixed_meeting');
                                        btn_content = btn.html();
                                        btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;'+btn_content);

                                        $.ajax({
                                            type: "get",
                                            url: "<?php echo url(); ?>/board-meting/fixed-meeting",
                                            data: {
                                                _token: _token,
                                                board_meeting_id: board_meeting_id
                                            },
                                            success: function (response) {
                                                if (response.responseCode == 1) {
                                                    btn.html(btn_content);
                                                    toastr.success('Meeting fixed successfully!!');
                                                    window.setTimeout(function () {
                                                        location.reload();
                                                    }, 800);

                                                }
                                            }
                                        });
                                    });
                                }
                            });
                }
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
            </script>

            <script>
                $(function () {
                    var board_id = '{{$board_meeting_id}}';
                    committeeList = $('#committeeList').DataTable({
                        processing: true,
                        serverSide: true,

                        ajax: {
                            url: '{{url("board-meting/committee/get-data")}}',
                            method: 'post',
                            data: function (d) {
                                d.board_meting_id = board_id;
                                d._token = $('input[name="_token"]').val();

                            }
                        },
                        columns: [
                            {data: 'user_name', name: 'user_name'},
                            {data: 'designation', name: 'designation'},
                            {data: 'user_email', name: 'user_email'},
                            {data: 'user_mobile', name: 'user_mobile'},
                            {data: 'type', name: 'type'},
                            {data: 'created_at', name: 'created_at'},
                            {data: 'action', name: 'action', orderable: false, searchable: false}
                        ],
                        "aaSorting": []
                    });
                });

                function deleteMember(member_id) {
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
                                            url: "<?php echo url(); ?>/board-meting/committee/deleteMember",
                                            data: {
                                                _token: _token,
                                                member_id: member_id
                                            },
                                            success: function (response) {
                                                if (response.responseCode == 1) {
                                                    committeeList.ajax.reload();
                                                }
                                            }
                                        });
                                    });
                                }
                            });
                }
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
    @endsection <!--- footer-script--->

