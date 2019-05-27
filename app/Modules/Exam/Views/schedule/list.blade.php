@extends('layouts.admin')

<?php
$accessMode = ACL::getAccsessRight('Scheduling');
if (!ACL::isAllowed($accessMode, 'V'))
    die('no access right!');
?>

@section('content')
<div class="col-md-12">

    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

    <!-- Modal -->
    <div class="modal fade" id="ScheduleModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="frmAddSchedule"></div>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading" style="padding: 3px 15px !important;" >
            <div class="pull-left">
                <h6><span style="font-size: 18px;">{{ trans('messages.schedule_list') }}</span></h6>
            </div>
            <a class="pull-right btn btn-default addScheduleModal" data-toggle="modal" data-target="#ScheduleModal" 
               onclick="openModal('.addScheduleModal', 'frmAddSchedule')" href="{{ url('/exam/schedule/create') }}">
                <i class="fa fa-plus"></i> {{ trans('messages.create_schedule') }}
            </a>

            <div class="clearfix"></div>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">

            <div class="table-responsive">
                <table id="list" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                    <thead class="">
                        <tr class="">
                            <th>#</th>
                            <th width="40%">Question title</th>
                            <th>Question type</th>
                            <th>Last updated on</th>
                            <th>Status</th>
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
                url: '{{url("exam/schedule/get-list")}}',
                method: 'post',
                data: function (d) {
                    d._token = $('input[name="_token"]').val();
                }
            },
            columns: [
                {data: 'serial', name: 'serial'},
                {data: 'question_title', name: 'question_title'},
                {data: 'exam_name', name: 'exam_name'},
                {data: 'last_update', name: 'last_update'},
                {data: 'schedule_status', name: 'schedule_status'},
                {data: 'action', name: 'action', orderable: false, searchable: true}
            ],
            "aaSorting": []
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
@endsection
