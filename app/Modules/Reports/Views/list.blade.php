@extends('layouts.admin')


@section('content')
<?php $accessMode=ACL::getAccsessRight('report');
if(!ACL::isAllowed($accessMode,'V')) die('no access right!');
?>
    <div class="col-lg-12">

        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}
        <div class="panel panel-primary">

            <div class="panel-heading">

                <div class="pull-left">
                   <h5><strong><i class="fa fa-list"></i> List of reports</strong></h5>
                </div>
                <div class="pull-right">
                    <!--1x101 is Sys Admin, 7x712 is SB Admin, 11x422 is Bank Admin-->
                    @if(Auth::user()->user_type == '1x101')
                        <div class="">
                            @if(ACL::getAccsessRight('report','A'))
                                <a class="" href="{{ url('/reports/create') }}">
                                    {!! Form::button('<i class="fa fa-plus"></i> <b> Add New Report</b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a data-toggle="tab" href="#list_1" aria-expanded="true">
                                <b>My Favourite</b>
                            </a>
                        </li>
                        <li class="all_reports">
                            <a data-toggle="tab" href="#list_2" aria-expanded="false">
                                <b>All Reports</b>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div id="list_1" class="tab-pane active">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="fav_list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($getFavouriteList['fav_report'] as $row)
                                        <tr>
                                            <td>{!! $row->report_title !!}</td>
                                            <td>{!! $row->status==1? '<span class="text-success">Published</span>':'<span class="text-warning">Un-published</span>' !!}</td>
                                            <td>
                                                @if(\App\Libraries\UtilFunction::isAllowedToViewFvrtReport($row->report_id))
                                                    @if(ACL::getAccsessRight('report','V'))
                                                        <a href="{!! url('reports/view/'. Encryption::encodeId($row->report_id)) !!}" class="btn btn-xs btn-primary">
                                                            <i class="fa fa-folder-open-o"></i> Open
                                                        </a>
                                                    @endif
                                                    @if(ACL::getAccsessRight('report','E'))
                                                        {!! link_to('reports/edit/'. Encryption::encodeId($row->report_id),'Edit',['class' => 'btn btn-default btn-xs']) !!}
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <div id="list_2" class="tab-pane all_reports">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($getList['result'] as $row)
                                        <tr>
                                            <td>{!! $row->report_title !!}</td>
                                            <td>{!! $row->status==1? '<span class="text-success">Published</span>':'<span class="text-warning">Un-published</span>' !!}</td>
                                            <td>
                                                @if(ACL::getAccsessRight('report','V'))
                                                    <a href="{!! url('reports/view/'. Encryption::encodeId($row->report_id)) !!}" class="btn btn-xs btn-primary">
                                                        <i class="fa fa-folder-open-o"></i> Open
                                                    </a>
                                                @endif
                                                @if(ACL::getAccsessRight('report','E'))
                                                    {!! link_to('reports/edit/'. Encryption::encodeId($row->report_id),'Edit',['class' => 'btn btn-default btn-xs']) !!}

                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
            </div>
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->

@endsection
@section('footer-script')
    <script src="{{ asset("assets/scripts/datatable/jquery.dataTables.min.js") }}" src="" type="text/javascript"></script>
    <script src="{{ asset("assets/scripts/datatable/dataTables.bootstrap.min.js") }}" src="" type="text/javascript"></script>
    <script src="{{ asset("assets/scripts/datatable/dataTables.responsive.min.js") }}" src="" type="text/javascript"></script>
    <script src="{{ asset("assets/scripts/datatable/responsive.bootstrap.min.js") }}" src="" type="text/javascript"></script>
    <script>

        $(function () {
            $('#list').DataTable({
                "paging": true,
                "lengthChange": true,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "iDisplayLength":25
            });
        });

        $(function () {
            $('#fav_list').DataTable({
                "paging": true,
                "lengthChange": true,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "iDisplayLength":25
            });
        });

    </script>
@endsection