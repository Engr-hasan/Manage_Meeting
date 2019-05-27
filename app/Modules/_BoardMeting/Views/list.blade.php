@extends('layouts.admin')
@section('content')
    <?php $accessMode = ACL::getAccsessRight('BoardMeting');if (!ACL::isAllowed($accessMode, 'V')) {
        die('no access right!');
    }?>

    <style>
        .panel {
            margin: 0px;
        }

        /*.hover-item{*/
        /*hover-*/
        /*}*/
        .hover-item:hover {
            background: #ddd;
        }
    </style>
    <div class="col-lg-12">
        @include('message.message')
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="pull-left" style="line-height: 35px;">
                    <strong><i class="fa fa-list"></i> {{ trans('messages.board_meting_list') }}</strong>
                </div>
                <div class="pull-right">
                    @if(ACL::getAccsessRight('BoardMeting','A'))
                        <a class="" href="{{ url('/board-meting/new-board-meting') }}">
                            {!! Form::button('<i class="fa fa-plus"></i><b> ' .trans('messages.new_boardmeeting').'</b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                        </a>
                    @endif

                </div>
                <div class="clearfix"></div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                    <span class="col-md-3 col-md-offset-5"
                          style="color: gray; font-weight: bold">Today's Date: {{date("d-M-Y")}}</span>
                </div>

                <div class="table-responsive">
                    <table id="list" class="table table-striped table-bordered dt-responsive " cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th style="font-size: 13px">{!! trans('messages.basic_list_of_meeting') !!}</th>
                            <th style="font-size: 13px;width: 30%;">{!! trans('messages.agenda') !!}</th>
                            <th style="font-size: 13px">{!! trans('messages.status') !!}</th>
                            <th style="font-size: 13px">{!! trans('messages.created_at') !!}</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div><!-- /.table-responsive -->
            </div>
        </div>
        <div class="thumbnail" style="background: #d9edf7">
            <div class="panel-heading">
                <div class="pull-right">
                    {{--<a class="" href="{{ url('/board-meting/new-board-meting') }}">--}}
                    {{--{!! Form::button('<i class="fa fa-plus"></i><b> ' .trans('messages.new_boardmeeting').'</b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}--}}
                    {{--</a>--}}
                    <button type="button" class="btn btn-default processList processListUp">
                        <strong><i class="fa fa-arrow-down"
                                   aria-hidden="true"></i> {!! trans('messages.complete_list') !!} </strong>
                    </button>

                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body"></div>
        </div>
        <div class="panel-panel-info">
            <div id="listOfProcess"
                 style="display:none;" class="panel panel-info">
                <div class="panel-heading"><i class="fa fa-list"> </i> <b>{!! trans('messages.complete') !!} <span
                                id="m"></span></b></div>


                <div class="nav-tabs-custom"
                     style="margin-top: 15px;padding: 0px 5px;">
                    <div class="tab-content">
                        <div id="list_desk" class="tab-pane active " style="margin-top: 20px;">

                            <div class="table-responsive">
                                <table id="complete_list" class="table table-striped display"
                                       style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th style="font-size: 13px">{!! trans('messages.basic_list_of_meeting') !!}</th>
                                        <th style="font-size: 13px;width: 30%;">{!! trans('messages.agenda') !!}</th>
                                        <th style="font-size: 13px">{!! trans('messages.status') !!}</th>
                                        <th style="font-size: 13px">{!! trans('messages.created_at') !!}</th>
                                        <th style="font-size: 13px">{!! trans('messages.action') !!}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>


                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="panel panel-info">
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="col-md-6">

                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="pull-left" style="line-height: 35px;">
                                <strong><img
                                            src="{{URL::to('/assets/images/open-book.png')}}"> {{ trans('messages.share_document') }}
                                </strong>
                            </div>
                            <div class="pull-right">
                                <a class="" href="{{ url('/board-meting/create-share-document') }}">
                                    {!! Form::button('<i class="fa fa-plus"></i><b> ' .trans('messages.new_document').'</b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                                </a>

                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body" style=" max-height:200px; font-size: 15px ;overflow-y: scroll">
                            @foreach($shareDoc as $doc)
                                @if($doc->tag == 3)
                                    <?php $color = "danger";
                                    $button = "High"
                                    ?>
                                @elseif($doc->tag == 2)
                                    <?php $color = "info";
                                    $button = "Moderate"?>
                                @elseif($doc->tag == 1)
                                    <?php $color = "success";
                                    $button = "Normal";?>
                                @endif
                                <a class="hover-item" style="text-decoration: none; " target="_blank"
                                   href="{{url('board-meting/view-share-document/'.\App\Libraries\Encryption::encodeId($doc->id))}}">
                                    <div class="panel panel-default hover-item"
                                         style="margin-top: 2px; border: 1px solid #86bb86">
                                        <div>
                                            <div class="pull-right" style="margin: 8px 30px 0px 0px;">
                                                <button class="btn btn-{{$color}} btn-xs">{{$button}} <span><i
                                                                class="fa fa-chevron-right"
                                                                aria-hidden="true"></i></span></button>
                                                {{--<div id="pointer_shape" style="{{$color}}">--}}
                                                {{--</div>--}}
                                                {{----}}

                                            </div>
                                            <div class="panel-heading" style="border-left: 5px solid #31708f;">
                                                <div style="">{{$doc->doc_name}}
                                                    <br>{{date("d M Y", strtotime($doc->created_at))}}</div>
                                            </div>

                                        </div>

                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-6">

                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="pull-left" style="line-height: 35px;">
                                <strong><i class="fa fa-newspaper-o"
                                           aria-hidden="true"></i></i> {{ trans('messages.news') }}</strong>
                            </div>
                            <div class="pull-right">
                                <a class="" href="{{ url('/settings/create-notice/board-meeting') }}">
                                    {!! Form::button('<i class="fa fa-plus"></i><b> ' .trans('messages.new_news').'</b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                                </a>

                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <!-- /.panel-heading -->

                        <div class="panel-body" style=" max-height:200px; overflow-y: scroll">
                            @foreach($notice as $boardNotice)
                                <a target="_blank"
                                   href="{{url('board-meting/view-news/'.\App\Libraries\Encryption::encodeId($boardNotice->id))}}"
                                   class="hover-item" style="text-decoration: none">
                                    <div class="panel panel-default hover-item"
                                         style="margin-top: 2px; border: 1px solid #86bb86">
                                        <div>
                                            <div class="pull-right" style="margin: 8px 30px 0px 0px;">
                                                <button class="btn btn-{{$boardNotice->importance}} btn-xs">{{$boardNotice->importance}}
                                                    <span><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
                                                </button>
                                            </div>

                                            {{--<div class="pull-right" style="margin: 15px 15px 0px 0px;"><i class="fa fa-chevron-right"></i></div>--}}
                                            <div class="panel-heading" style="border-left: 5px solid #31708f">
                                                <div>{{$boardNotice->heading}}
                                                    <br>{{date("d M Y", strtotime($boardNotice->created_at))}}</div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div><!-- /.panel -->
        <div class="col-md-12"><br></div>
        <div class="col-md-12"><br></div>
        <div class="col-md-12"><br></div>
        <div class="col-md-12"><br></div>
    </div><!-- /.col-lg-12 -->

@endsection <!--content section-->
@section('footer-script')
    @include('Users::partials.datatable')
    <script>
        $(document).ready(function () {
            $('.processList').on('click', function (e) {
                if ($('#listOfProcess').is(":visible")) {

                    $('.processList').find('i').removeClass("fa-arrow-up fa");
                    $('.processList').find('i').addClass("fa fa-arrow-down");
                    $(".processList").css("background-color", "");
                    $(".processList").css("color", "");
                } else {
                    $(this).find('i').removeClass("fa fa-arrow-down");
                    $(this).find('i').addClass("fa fa-arrow-up");
                    $(".processList").css("background-color", "#1abc9c");
                    $(".processList").css("color", "white");
                }
                $('#listOfProcess').slideToggle();
            });


            $('body').on('click', '.publish_complete_meeting', function () {

                var board_meeting_id = $(this).val();
                var _token = $('input[name="_token"]').val();

                btn = $(this);

                toastr.error("<br /><br /><button type='button' style='color:black' id='confirmationRevertYes' class='btn clear'>Yes</button> <button style='margin-left: 120px;color: black' class='btn clear' type='button'>No</button>", 'Are you sure you want to published?',
                    {
                        closeButton: true,
                        allowHtml: true,
                        timeOut: 0,
                        extendedTimeOut: 0,
                        positionClass: "toast-top-center",
                        onShown: function (toast) {
                            $("#confirmationRevertYes").click(function () {
                                var _token = $('input[name="_token"]').val();
                                btn_content = btn.html();
                                btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;' + btn_content);
                                $.ajax({
                                    type: "POST",
                                    url: "<?php echo url(); ?>/board-meting/complete-meeting/publish",
                                    data: {
                                        _token: _token,
                                        board_meeting_id: board_meeting_id,
                                    },
                                    success: function (response) {
                                        complete_list.ajax.reload();
                                        toastr.error('Publish successfully!!');
                                    }
                                });
                            });
                            $(self).next().hide();
                        }
                    });
            });

        });
        $(function () {

            $('#list').DataTable({
                iDisplayLength: 5,
                processing: true,
                serverSide: true,
                searching: true,
                "aLengthMenu": [[5, 10, 25, 50], [5, 10, 25, 50]],
//            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],

                ajax: {
                    url: '{{url("board-meting/get-row-details-data")}}',
                    method: 'post',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
                    {data: 'meting_date', name: 'meting_date'},
                    {data: 'agenda_info', name: 'agenda_info'},
                    {data: 'status', name: 'is_active'},
                    {data: 'created_at', name: 'created_at'}
                ],
                "aaSorting": []
            });

            complete_list = $('#complete_list').DataTable({
                iDisplayLength: 5,
                processing: true,
                serverSide: true,
                searching: true,
                "aLengthMenu": [[5, 10, 25, 50], [5, 10, 25, 50]],

                ajax: {
                    url: '{{url("board-meting/get-complete-row-details-data")}}',
                    method: 'post',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
                    {data: 'meting_date', name: 'meting_date'},
                    {data: 'agenda_info', name: 'agenda_info'},
                    {data: 'status', name: 'is_active'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action'}
                ],
                "aaSorting": []
            });

            $('#shareDocument').DataTable({
                iDisplayLength: 5,
                processing: true,
                serverSide: true,
                searching: true,
                "aLengthMenu": [[5, 10, 25, 50], [5, 10, 25, 50]],
//            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],

                ajax: {
                    url: '{{url("board-meting/share-document/get-share-document-data")}}',
                    method: 'post',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
                    {data: 'doc_name', name: 'doc_name'},
                ],
                "aaSorting": []
            });
        });
    </script>

    <style>
        div.dataTables_wrapper div.dataTables_length select {
            width: 60px;
        }

        #pointer_shape {
            width: 30px;
            height: 14px;
            position: relative;
            background: #000;
            margin: 10px 0;
        }

        #pointer_shape:before {
            content: "";
            position: absolute;
            right: -20px;
            bottom: 0px;
            width: 0;
            height: 0;
            border-top: 6px solid transparent;
            border-left: 22px solid #000;
            border-bottom: 8px solid transparent;
        }
    </style>
@endsection <!--- footer-script--->

