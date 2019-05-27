@extends('layouts.admin')
@section('content')

<?php
    $accessMode = ACL::getAccsessRight('ExamList');
    if (!ACL::isAllowed($accessMode, 'V'))
        die('no access right!');
?>

<div class="col-md-12">
    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}
    <div class="panel panel-primary">
        <div class="panel-heading" style="padding: 2px 15px;" >
            <span style="font-size: 18px;">{{ trans('messages.exam_list') }}</span>
            <div class="clearfix"></div>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">

            <div class="table-responsive">
                <table id="list" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                    <thead class="">
                        <tr>
                            <th width="4%">#</th>
                            <th width="26%">Exam title</th>
                            <th width="15%">Exam type</th>
                            <th width="15%">Exam date</th>
                            <th width="10%">Status</th>
                            <th width="10%">Number of question</th>
                            <th width="10%">Mark</th>
                            <th width="10%">Action</th>
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

<script>
    $(function () {
        $('#list').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{url("exam/exam-list/get-exam-list")}}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [
                {data: 'serial', name: 'serial'},
                {data: 'exam_title', name: 'exam_title'},
                {data: 'exam_type', name: 'exam_type'},
                {data: 'exam_date', name: 'exam_date'},
                {data: 'remarks', name: 'remarks'},
                {data: 'no_of_question', name: 'no_of_question'},
                {data: 'mark', name: 'mark'},
                {data: 'action', name: 'action', orderable: false, searchable: true}
            ],
            "aaSorting": []
        });

    });
</script>
@endsection
