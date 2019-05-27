@extends('layouts.admin')

@section('content')
    <?php
    $accessMode = ACL::getAccsessRight('BoardMeting');
    if (!ACL::isAllowed($accessMode, 'A')) {
        die('You have no access right! Please contact with system admin for more information.');
    }
    ?>
    @include('BoardMeting::progress-bar')
    @include('partials.messages')
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>
                    {{trans('messages.committeePrecedent')}}
                </b>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="committeeList" class="table table-striped table-bordered dt-responsive " cellspacing="0"
                           width="100%">
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

                <div class="col-md-3 col-md-offset-5">
                    <button style="margin-left: 2px;" class="btn btn-primary confirm_chairman"><i class="fa fa-save"></i> {!! trans('messages.confirm') !!}</button>
                    <a href="{{ url('board-meting/committee/notice-generate/'.$board_meeting_id) }}" class="btn btn-info ">
                        {!! trans('messages.next') !!} <i class="fa fa-chevron-right"></i>
                    </a>
                </div>
            </div><!-- /.panel-body -->
            <div class="col-md-12"><br></div>
            <div class="col-md-12"><br></div>
            <div class="col-md-12"><br></div>
        </div><!--/col-md-12-->
        @endsection


        @section('footer-script')
            @include('Users::partials.datatable')
            <script>

                var _token = $('input[name="_token"]').val();
                $(document).ready(function () {
                    $("#entry-form").validate({
                        errorPlacement: function () {
                            return false;
                        }
                    });
                    $("#entry-form1").validate({
                        errorPlacement: function () {
                            return false;
                        }
                    });

                    $('body').on('click', '.chairman_selected', function () {
                        $(".chairman_selected").prop('checked', false);
                        $(this).prop('checked', true);
                    });

                    $('.confirm_chairman').on('click', function () {
                        if ($('.chairman_selected').is(":checked"))
                        {
                            var committee_id =   $( "input:checked" ).val();
                        }else{
                            toastr.error('Please select a user.');
                            return false;
                        }

                        $(this).after('<span class="loading_data">Loading...</span>');
                        var self = $(this);
                        $.ajax({
                            type: "post",
                            url: "/board-meting/committee/save-chairperson-choice",
                            data: {
                                _token: _token,
                                board_meeting_id: '{{$board_meeting_id}}',
                                committee_id: committee_id
                            },
                            success: function (response) {
                                if (response.responseCode == 1) {
                                    toastr.success('Chairperson selected successfully!!.');
                                    committeeList.ajax.reload();
                                }
                                $(self).next().hide();
                            }
                        });

                    })
                });
            </script>

            <script>
                $(function () {
                    var board_id = '{{$board_meeting_id}}';
                    committeeList = $('#committeeList').DataTable({
                        processing: true,
                        serverSide: true,

                        ajax: {
                            url: '{{url("board-meting/committee/get-data-for-chairman")}}',
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

            </script>
    @endsection <!--- footer script--->