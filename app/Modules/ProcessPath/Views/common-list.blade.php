@extends('layouts.admin')
@section('content')
    <?php
    $moduleName = Request::segment(1);
    $user_type = CommonFunction::getUserType();
    $desk_id_array = explode(',', \Session::get('user_desk_ids'));
    $accessMode = "V";
    if (!ACL::isAllowed($accessMode, 'V'))
        die('no access right!');

    ?>
    <section class="content">

        <div class="box">
            <div class="box-body">
                <div class="col-lg-12">
                    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
                    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

                </div>

                @if(empty($delegated_desk))
                    <div class="modal fade" id="ProjectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content" id="frmAddProject"></div>
                        </div>
                    </div>
                @endif
                <div class="col-lg-12">
                    <div class="panel panel-info" style="">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h5><i class="fa fa-list"></i>  <b>Application list <span class="list_name"></span> @if(isset($process_info->name)) for ({{$process_info->name}})</b> @endif</h5>
                                </div>
                                <div class="col-lg-6">
                                    @if(ACL::getAccsessRight('spaceAllocation','A'))
                                        <a href="{{URL::to($process_info->form_url.'/add')}}" class="pull-right">
                                            {!! Form::button('<i class="fa fa-plus"></i> <b>New Application</b>', array('type' => 'button', 'class' => 'btn btn-info')) !!}
                                        </a>
                                    @endif
                                    {{--@if($user_type=="4x404" && Auth::user()->desk_id ==1)--}}
                                        {{--<a href="{{url('loan-locator/add')}}" class="pull-right">--}}
                                            {{--{!! Form::button('<i class="fa fa-plus"></i> <b>Loan Initiation</b>', array('type' => 'button', 'class' => 'btn btn-info')) !!}--}}
                                        {{--</a>--}}
                                     {{--@endif--}}
                                        @if($user_type=="4x404")
                                            <a href="{{URL::to($process_info->form_url.'/add')}}" class="pull-right">
                                        {!! Form::button('<i class="fa fa-plus"></i> <b>'.trans("messages.new_application").'</b>', array('type' => 'button', 'class' => 'btn btn-info')) !!}
                                        </a>
                                        @endif
                                </div>
                            </div>
                        </div>

                        <div class="panel-body">
                            <div class="clearfix">
                                @if(!empty($desk_id_array[0]) || $user_type=="1x101" || $user_type=="4x404")
                                    <div class="" id="statuswiseAppsDiv">
                                        @include('ProcessPath::statuswiseApp')
                                    </div>
                                @endif
                            </div>

                            <div class="nav-tabs-custom" style="margin-top: 15px;padding: 0px 5px;">
                                <ul  class="nav nav-tabs">

                                    @if($user_type != '1x101' && $user_type != '5x505')
                                        @if($user_type == "4x404" && Auth::user()->desk_id == 1)
                                            {{--<li id="tab4" class="active">--}}
                                                {{--<a data-toggle="tab" href="#desk_user_application" class="deskUserApplication" aria-expanded="true">--}}
                                                    {{--<b>My application</b>--}}
                                                {{--</a>--}}
                                            {{--</li>--}}
                                        @endif

                                        <li id="tab1"  class="active">
                                            <a data-toggle="tab" href="#list_desk" class="mydesk" aria-expanded="true">
                                                <b>My Desk</b>
                                            </a>
                                        </li>



                                        {{--<li id="tab2" class="">--}}
                                            {{--<a data-toggle="tab" href="#list_delg_desk" aria-expanded="false" class="delgDesk" >--}}
                                                {{--<b>Delegation Desk</b>--}}
                                            {{--</a>--}}
                                        {{--</li>--}}
                                    @else
                                        <li id="tab1" class="active">
                                            <a data-toggle="tab" href="#list_desk" class="mydesk" aria-expanded="true">
                                                <b>List</b>
                                            </a>
                                        </li>
                                    @endif

                                    <li id="tab3" class="">
                                        <a data-toggle="tab" href="#list_search" aria-expanded="false">
                                            <b>Search</b>
                                        </a>
                                    </li>

                                    <li class="pull-right process_type_tab">
                                        {!! Form::select('ProcessType', ['0' => 'All'] + $ProcessType, $process_type_id, ['class' => 'form-control ProcessType']) !!}
                                    </li>
                                </ul>

                                <div id="reyad" class="tab-content">
                                    <div id="list_desk" class="tab-pane active" style="margin-top: 20px">
                                        <div class="table-responsive">
                                            <table id="table_desk" class="table table-striped display" style="width: 100%">
                                                <thead>
                                                <tr>
                                                    <th>Current Desk</th>
                                                    <th>Tracking No</th>
                                                    <th>Process Type</th>
                                                    <th style="width: 35%">Reference Data</th>
                                                    <th>Status</th>
                                                    <th>Modified</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div id="list_search" class="tab-pane" style="margin-top: 20px">
                                        @include('ProcessPath::search')
                                    </div>

                                    <div id="list_delg_desk" class="tab-pane" style="margin-top: 20px">
                                        <div class="table-responsive">
                                            <table id="table_delg_desk" class="table table-striped" style="width: 100%">
                                                <thead>
                                                <tr>
                                                    <th>Current Desk</th>
                                                    <th>Tracking No</th>
                                                    <th>Process Type</th>
                                                    <th style="width: 35%">Reference Data</th>
                                                    <th>Status</th>
                                                    <th>Modified</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div id="desk_user_application" class="tab-pane" style="margin-top: 20px">
                                        <div class="table-responsive">
                                            <table id="table_desk_user_application" class="table table-striped" style="width: 100%">
                                                <thead>
                                                <tr>
                                                    <th>Current Desk</th>
                                                    <th>Tracking No</th>
                                                    <th>Process Type</th>
                                                    <th style="width: 35%">Reference Data</th>
                                                    <th>Status</th>
                                                    <th>Modified</th>
                                                    <th>Action</th>
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
                </div>

            </div>
        </div>
    </section>

@endsection
@section('footer-script')
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>

    @include('partials.datatable-scripts')
    <script language="javascript">

        $(function () {
            @if($user_type == "4x404" && Auth::user()->desk_id == 1)
                // $('#list_desk').removeClass('active');
                // $('#tab2').removeClass('active');
                // $('#desk_user_application').addClass('active');
            @endif

            var table = [];


            /**
             * set selected ProcessType in session
             * load data by ProcessType, on change ProcessType select box
             * @type {jQuery}
             */
            $('.ProcessType').change(function () {
                $.get('{{route("process.setProcessType")}}',
                    {
                        _token: $('input[name="_token"]').val(),
                        data: $(this).val()
                    }, function(data) {
                        if(data == 'success'){
                            table_desk.ajax.reload();
                            // It seems unnecessary, need to check
                            var len = table.length;
                            for (var i = 0; i < len; i++) {
                                table[i].ajax.reload();
                            }
                        }
                    });
            });
            $('.ProcessType').trigger('change');
            /**
             * on click My Desk tab reload table with application list of current desk
             * @type {jQuery}
             */
            $('.mydesk').click(function () {
                table_desk.ajax.reload();
                board_meting.ajax.reload();
            });

            $('.deskUserApplication').click(function () {
                table_desk_user_application.ajax.reload();
            });
            /**
             * on click Delegation Desk load table with delegated application list
             * @type {jQuery}
             */
            $('.delgDesk').click(function () {
                table_delg_desk.ajax.reload();
            });


            /**
             * table desk script
             * @type {jQuery}
             */
            table_desk = $('#table_desk').DataTable({
                iDisplayLength: 25,
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url:  '{{route("process.getList",['-1000', 'my-desk'])}}',
                    method:'get',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
                    {data: 'desk', name: 'desk'},
                    {data: 'tracking_no', name: 'tracking_no',searchable: false},
                    {data: 'process_name', name: 'process_name',searchable: false},
                    {data: 'json_object', name: 'json_object'},
                    {data: 'status_name', name: 'status_name', searchable: false},
                    {data: 'updated_at', name: 'updated_at', searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "aaSorting": []
            });


            /**
             * delegated application list table script
             * @type {jQuery}
             */
            table_delg_desk = $('#table_delg_desk').DataTable({
                iDisplayLength: 25,
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url:  '{{route("process.getList",['-1000','my-delg-desk'])}}',
                    method:'get',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
                    {data: 'desk', name: 'desk'},
                    {data: 'tracking_no', name: 'tracking_no',searchable: false},
                    {data: 'process_name', name: 'process_name',searchable: false},
                    {data: 'json_object', name: 'json_object'},
                    {data: 'status_name', name: 'status_name', searchable: false},
                    {data: 'updated_at', name: 'updated_at', searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "aaSorting": []
            });
            table_desk_user_application = $('#table_desk_user_application').DataTable({
                iDisplayLength: 25,
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url:  '{{route("process.getList",['-1000','desk_user_application'])}}',
                    method:'get',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
                    {data: 'desk', name: 'desk'},
                    {data: 'tracking_no', name: 'tracking_no',searchable: false},
                    {data: 'process_name', name: 'process_name',searchable: false},
                    {data: 'json_object', name: 'json_object'},
                    {data: 'status_name', name: 'status_name', searchable: false},
                    {data: 'updated_at', name: 'updated_at', searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "aaSorting": []
            });


        });


    </script>
    <style>
        *{
            font-weight: normal;
        }
    </style>
    @yield('footer-script2')
@endsection