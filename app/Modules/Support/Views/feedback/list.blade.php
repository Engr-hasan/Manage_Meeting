@extends('layouts.admin')

@section('page_heading',trans('messages.feedback_list'))

@section('content')

    <div class="col-lg-12">

        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}


        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="">
                    <a class="" href="{{ url('/support/create-feedback') }}">
                        {!! Form::button('<i class="fa fa-plus"></i> '.trans('messages.new_feedback'), array('type' => 'button', 'class' => 'btn btn-info')) !!}
                    </a>
                </div>
            </div>
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class=" {!! (Request::segment(2)=='feedback' OR Request::segment(2)=='')?'active':'' !!}">
                        <a data-toggle="tab" href="#list_1" aria-expanded="true">
                            <b>My Tickets</b>
                        </a>
                    </li>

                    @if(CommonFunction::getUserType() == '1x101' OR CommonFunction::getUserType() == '2x202' OR CommonFunction::getUserType() == '2x203')
                        <li class=" {!! (Request::segment(2)=='#')?'active':'' !!}">
                            <a data-toggle="tab" href="#list_2" aria-expanded="true">
                                <b>Assigned to me</b>
                            </a>
                        </li>
                        @endif

                                <!--2x202 for IT Help Desk, 2x203 for Call center-->
                        @if(CommonFunction::getUserType() == '1x101' OR CommonFunction::getUserType() == '2x202' OR CommonFunction::getUserType() == '2x203')
                            <li class=" {!! (Request::segment(2)=='#')?'active':'' !!}">
                                <a data-toggle="tab" href="#list_3" aria-expanded="true">
                                    <b>Unassigned</b>
                                </a>
                            </li>
                        @endif

                </ul>
                <div class="tab-content">
                    <div id="list_1" class="tab-pane {!! (Request::segment(2)=='feedback' OR Request::segment(2)=='')?'active':'' !!}">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="table_1" class="table table-striped table-bordered  table-responsive dt-responsive" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Topic</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Last Update On</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div><!-- /.table-responsive -->
                        </div><!-- /.panel-body -->
                    </div>

                    @if(CommonFunction::getUserType() == '1x101' OR CommonFunction::getUserType() == '2x202' OR CommonFunction::getUserType() == '2x203')
                        <div id="list_2" class="tab-pane  {!! (Request::segment(2)=='#')?'active':'' !!}">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table id="table_2" class="table table-striped table-bordered  table-responsive dt-responsive" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Topic</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Priority</th>
                                            <th>Last Update On</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div><!-- /.table-responsive -->
                            </div><!-- /.panel-body -->
                        </div>
                    @endif

                    @if(CommonFunction::getUserType() == '1x101' OR CommonFunction::getUserType() == '2x202' OR CommonFunction::getUserType() == '2x203')
                        <div id="list_3" class="tab-pane {!! (Request::segment(2)=='#')?'active':'' !!}">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table id="table_3" class="table table-striped table-bordered table-responsive dt-responsive" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Topic</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Priority</th>
                                            <th>Last Update On</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div><!-- /.table-responsive -->
                            </div><!-- /.panel-body -->
                            <!-- /.table-responsive -->
                        </div>
                    @endif

                </div>
            </div>

        </div><!-- /.col-lg-12 -->
    </div><!-- /.col-lg-12 -->

@endsection

@section('footer-script')

    @include('partials.datatable-scripts')

    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>

    <script>
        $(function () {
            $('#table_1').DataTable({
                processing: true,
                serverSide: true,
                aaSorting: [],
                ajax: {
                    url: '{{url("support/get-feedback-details-data")}}',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
                    {data: 'created', name: 'created'},
                    {data: 'topic_name', name: 'topic_name'},
                    {data: 'description', name: 'description'},
                    {data: 'status', name: 'status'},
                    {data: 'priority', name: 'priority'},
                    {data: 'updated', name: 'updated'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });

            $('#table_2').DataTable({
                processing: true,
                serverSide: true,
                aaSorting: [],
                ajax: {
                        url: '{{url("support/get-uncategorized-feedback-data/submitted_to")}}',
                    method: 'POST',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
                    {data: 'created', name: 'created'},
                    {data: 'topic_name', name: 'topic_name'},
                    {data: 'description', name: 'description'},
                    {data: 'status', name: 'status'},
                    {data: 'priority', name: 'priority'},
                    {data: 'updated', name: 'updated'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
            $('#table_3').DataTable({
                processing: true,
                serverSide: true,
                aaSorting: [],
                ajax: {
                    url: '{{url("support/get-uncategorized-feedback-data/unassigned")}}',
                    method: 'POST',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
                    {data: 'created', name: 'created'},
                    {data: 'topic_name', name: 'topic_name'},
                    {data: 'description', name: 'description'},
                    {data: 'status', name: 'status'},
                    {data: 'priority', name: 'priority'},
                    {data: 'updated', name: 'updated'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        });
    </script>
@endsection
