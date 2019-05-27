@extends('layouts.admin')

<?php
$accessMode = ACL::getAccsessRight('Scheduling');
if (!ACL::isAllowed($accessMode, 'V'))
    die('no access right!');
?>

@section('content')

<div class="col-lg-12">
    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

    <div class="errorMsg alert alert-danger alert-dismissible hidden">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button></div>
    <div class="successMsg alert alert-success alert-dismissible hidden">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button></div>

    <!-- Modal -->
    <div class="modal fade" id="ScheduleModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="frmAddSchedule"></div>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading" style="padding: 3px 15px !important;">
            <div class="pull-left">
                <h6><span style="font-size: 16px;"> <b> Schedule details</b></span></h6>
            </div>
            <div class="pull-right">
                @if($data->schedule_status == 'Initiated')
                <a class="btn btn-warning addScheduleModal show-in-view"
                    href="{{ url('exam/schedule/publish/'.$_id) }}">
                    <i class="fa fa-paper-plane"></i> <b> Publish Schedule</b>
                </a>
                <a class="btn btn-info addScheduleModal show-in-view" data-toggle="modal" data-target="#ScheduleModal"
                   onclick="openModal('.addScheduleModal', 'frmAddSchedule')" href="{{ url('exam/schedule/edit/'.$_id) }}">
                    <i class="fa fa-edit"></i> <b> Edit </b>
                </a>
                @endif

                @if($data->schedule_status == 'Schedule Published')
                <a class="btn btn-success"
                   href="{{ url('exam/schedule/exam-taken/'.$_id) }}">
                    <i class="fa fa-check-square" aria-hidden="true"></i> <b> Exam Taken</b>
                </a>
                @endif
            </div>
            <div class="clearfix"></div>
        </div> <!-- /.panel-heading -->

        <div class="panel-body">
            {!! Form::open(array('url' => 'exam/schedule/update/'.$_id,'method' => 'patch', 'class' => 'form-horizontal',
            'id' => 'ExamScheduleForm','enctype' =>'multipart/form-data', 'files' => 'true')) !!}

            @include('Exam::schedule.edit-form')

            {!! Form::close() !!}<!-- /.form end -->

        </div><!--/panel-body-->
        <div class="panel-footer">
            <div class="row">
                <div class="col-md-1">
                    <a href="{{ url('exam/schedule/list') }}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default btn-sm show-in-view')) !!}
                    </a>
                </div>
                <div class="col-md-11">
                    <div class="col-md-6 text-left">
                        {!! CommonFunction::showCreateLog($data->created_at, $data->created_by) !!}
                    </div>
                    <div class="col-md-6 text-right">
                        {!! CommonFunction::showAuditLog($data->updated_at, $data->updated_by) !!}
                    </div>
                </div>
            </div>
        </div>

        {!! Form::close() !!}<!-- /.form end -->

    </div>

    <div class="panel panel-info">
        <div class="panel-heading"><b>Included questions and users</b></div>  <!--/.panel-heading-->
        <div class="panel-body">
            {!! Session::has('successDown') ? '<div class="alert alert-success alert-dismissible">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("successDown") .'</div>' : '' !!}
            {!! Session::has('errorDown') ? '<div class="alert alert-danger alert-dismissible">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("errorDown") .'</div>' : '' !!}
            <ul class="nav nav-tabs">
                <li class="active">
                    <a data-toggle="tab" href="#tab_1" aria-expanded="true"><i class="fa fa-list-alt fa-2x"></i> Included Questions </a>
                </li>
                <li class="results_tab">
                    <a data-toggle="tab" href="#tab_2" aria-expanded="false"><i class="fa fa-users fa-2x"></i> Selected Users</a>
                </li>
            </ul>

            <div class="tab-content">

                <div id="tab_1" class="tab-pane active">
                    <div class="col-md-12 clearfix"><br/></div>
                    <div class="col-md-12">
                        <div class="col-md-12">
                            @if($data->schedule_status == 'Initiated')
                            <a class="btn btn-info btn-md pull-right addScheduleModal show-in-view" data-toggle="modal" data-target="#ScheduleModal"
                               onclick="openModal('.addScheduleModal', 'frmAddSchedule')" href="{{ url('exam/schedule/question-list/'.$_id) }}">
                                <i class="fa fa-plus"></i> <b> Add Questions </b>
                            </a>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12 clearfix"><br/></div>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="QuestionList" class="table table-responsive table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                                <thead class="alert alert-info">
                                    <tr class="text-center">
                                        <th class="text-center" width="5%">#</th>
                                        <th class="text-center" width="">Question Title</th>
                                        <th class="text-center" width="15%">Question Type</th>
                                        <th class="text-center" width="7%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div> <!-- /.table-responsive -->
                    </div>
                </div><!-- /.tab-pane -->

                <div id="tab_2" class="tab-pane">
                    <div class="col-md-12 clearfix"><br/></div>
                    <div class="col-md-12">
                        <div class="col-md-12">
                            @if($data->schedule_status == 'Initiated')
                            <a class="btn btn-success btn-md pull-right addScheduleModal show-in-view" data-toggle="modal" data-target="#ScheduleModal"
                               onclick="openModal('.addScheduleModal', 'frmAddSchedule')" href="{{ url('exam/schedule/users-list/'.$_id) }}">
                                <i class="fa fa-plus"></i> <b> Add Users </b>
                            </a>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12 clearfix"><br/></div>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="UserList" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                                <thead class="alert alert-success">
                                    <tr class="text-center">
                                        <th class="text-center" width="5%">#</th>
                                        <th class="text-center" width="">Name</th>
                                        <th class="text-center" width="">Email</th>
                                        <th class="text-center" width="">User Type</th>
                                        <th class="text-center" width="7%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div><!-- /.table-responsive -->
                    </div>
                </div><!-- /.tab-pane -->

            </div><!-- /.tab-content -->
        </div><!--/panel-body-->
    </div> <!--/panel-->

</div> <!--/col-lg-12-->
@endsection


@section('footer-script')
@include('partials.datatable-scripts')
<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
<script>
    $(document).ready(function () {
        $('#ExamScheduleForm').find('input:not( [type=checkbox],[type=from], [type=hidden] )').each(function () {
            $(this).replaceWith("<span>" + this.value + "</span>");
        });
        $('#ExamScheduleForm').find('textarea').each(function () {
            $(this).replaceWith("<span class=\"col-md-3\">" + this.value + "</span>");
        });
        $("#ExamScheduleForm").find('select').replaceWith(function () {
            var selectedText = $(this).find('option:selected').text();
            var selectedTextBold = "" + selectedText + "";
            return selectedTextBold;
        });

        $('#ExamScheduleForm .btn').not('.show-in-view').each(function () {
            $(this).replaceWith("");
        });

        $(function () {
            $('#QuestionList').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{url("exam/schedule/get-added-questions/".$_id)}}',
                    method: 'post',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
                    {data: 'serial', name: 'serial'},
                    {data: 'question_name', name: 'question_name'},
                    {data: 'exam_name', name: 'exam_name'},
                    {data: 'remove', name: 'remove', orderable: false, searchable: true}
                ],
                "aaSorting": []
            });
        });

        $(function () {
            $('#UserList').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{url("exam/schedule/get-selected-users/".$_id)}}',
                    method: 'post',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
                    {data: 'serial', name: 'serial'},
                    {data: 'user_full_name', name: 'user_full_name'},
                    {data: 'user_email', name: 'user_email'},
                    {data: 'type_name', name: 'type_name'},
                    {data: 'remove', name: 'remove', orderable: false}
                ],
                "aaSorting": []
            });
        });

    });

    function openModal(link, div) {
        $(link).on('click', function (e) {
            e.preventDefault();
            $('#' + div).html('<div style="text-align:center;"><h3 class="text-primary">Loading Form...</h3></div>');
            $('#' + div).load(
                    $(this).attr('href'),
                    function (response, status, xhr) {
                        if (status === 'error') {
                            alert('error');
                            $(div).html('<p>Sorry, but there was an error:' + xhr.status + ' ' + xhr.statusText + '</p>');
                        }
                        return this;
                    }
            );
        });
    }
</script>

@endsection <!--- footer script--->