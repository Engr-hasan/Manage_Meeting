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
                   <h5><strong> <?php echo  $report_data->report_title . ''; ?></strong></h5>
                </div>
                <div class="pull-right">
                    @if($fav_report_info)
                        @if($fav_report_info->status == 1)
                            <a href="{{ url('reports/remove-from-favourite/'.$report_id) }}" class="btn btn-info">
                                <b>Remove From Favourite</b>
                                &nbsp;<i class="fa fa-remove"></i>
                            </a>
                        @elseif($fav_report_info->status == 0)
                            <a href="{{ url('reports/add-to-favourite/'.$report_id) }}" class="btn btn-default">
                                <b>Add to Favourite</b>
                                &nbsp;<i class="fa fa-check-square-o"></i>
                            </a>
                        @endif
                    @else
                        <a href="{{ url('reports/add-to-favourite/'.$report_id) }}" class="btn btn-default">
                            <b>Add to Favourite</b>
                            &nbsp;<i class="fa fa-check-square-o"></i>
                        </a>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                @include('Reports::input-form')
            </div><!-- /.box-body -->
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

//        $("#rpt_date").datepicker({
//            maxDate: "+20Y",
//            //showOn: "button",
//            //buttonText: "Select date",
//            buttonText: "Select date",
//            changeMonth: true,
//            changeYear: true,
//            dateFormat: 'yy-mm-dd',
//            showAnim: 'scale',
//            yearRange: "-100:+40",
//            minDate: "-200Y",
//        });

        });
    </script>
@endsection
