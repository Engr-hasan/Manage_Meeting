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
                <?php echo  $report_data->report_title . ''; ?>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                @include('Reports::input-form')
            </div><!-- /.box-body -->

        </div>
        <div id="report_list_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
            <div class="row">
                <div class="col-sm-12">
                    <?php
                    $report = new \App\Modules\Reports\Models\ReportHelperModel();
                    $report->report_gen($report_id, $recordSet, $report_data->report_title, '');
                    //\App\Libraries\CommonFunction::report_gen($report_id, $recordSet, $report_data->report_title, '');
                    ?>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-script')
    <script>
        $(function () {
            // $("#report_list").DataTable();
//            $('#report_data').DataTable({
//                "paging": true,
//                "lengthChange": false,
//                "ordering": true,
//                "info": true,
//                "autoWidth": true,
//                "iDisplayLength": 20
//            });
        });
    </script>
@endsection
