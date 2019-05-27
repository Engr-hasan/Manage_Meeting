@extends('layouts.admin')

<?php
$accessMode = ACL::getAccsessRight('ResultProcess');
if (!ACL::isAllowed($accessMode, 'V'))
    die('no access right!');
?>

@section('content')


    <div class="col-md-12">
        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}
        <div class="panel panel-primary">
            <div class="panel-heading" style="padding: 2px 15px;" >
                <span style="font-size: 18px;">{{ trans('messages.result_list') }}</span>
                <div class="clearfix"></div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="table-responsive">
                    <table id="list" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                        <thead class="">
                        <tr>
                            <th>#</th>
                            <th width="30%">Exam title</th>
                            <th>Exam type</th>
                            <th>Exam mark</th>
                            <th>Exam date</th>
                            <th>Exam status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div> <!-- /.table-responsive -->
            </div> <!-- /.panel-body -->
        </div><!-- /.panel -->
    </div><!-- /.col-lg-12 -->

@endsection

@section('footer-script')
    @include('partials.datatable-scripts')

    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <script>
        $(function () {
            $('#list').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{url("exam/result/get-result-list")}}',
                    method: 'post',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
                    {data: 'serial', name: 'serial'},
                    {data: 'exam_title', name: 'exam_title'},
                    {data: 'exam_type', name: 'exam_type'},
                    {data: 'mark', name: 'mark'},
                    {data: 'exam_date', name: 'exam_date'},
                    {data: 'schedule_status', name: 'schedule_status'},
                    {data: 'action', name: 'action', orderable: false, searchable: true}
                ],
                "aaSorting": []
            });

        });
    </script>
@endsection
