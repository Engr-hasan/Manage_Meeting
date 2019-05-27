@extends('layouts.admin')

@section('page_heading','Notice')

@section('content')

@include('partials.messages')

<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'V')) {
    die('You have no access right! Please contact system admin for more information');
}
?>

<div class="col-lg-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
                <div class="pull-left">
                    <h5><i class="fa fa-list"></i> <strong>{!! trans('messages.notice_list_title') !!}</strong></h5>
                </div>
                <div class="pull-right">
                    @if(ACL::getAccsessRight('settings','A'))
                    <a class="" href="{{ url('/settings/create-notice') }}">
                        {!! Form::button('<i class="fa fa-plus"></i> <b>'.trans('messages.new_notice_btn'). '</b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                    @endif
                </div>
            <div class="clearfix"></div>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <div class="table-responsive">
                <table id="list" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Heading</th>
                            {{--<th>Details</th>--}}
                            <th>Status</th>
                            <th>Importance</th>
                            <th>Active Status</th>
                            <th width="9%">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div><!-- /.table-responsive -->
        </div><!-- /.panel-body -->
    </div><!-- /.panel -->
</div><!-- /.col-lg-12 -->

@endsection

@section('footer-script')

@include('partials.datatable-scripts')

<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>

<script>
    $(function () {
        $('#list').DataTable({
            processing: true,
            serverSide: true,
            iDisplayLength: 50,
            aaSorting: [],
            ajax: {
                url: '{{url("settings/get-notice-details-data")}}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [
                {data: 'update_date', name: 'update_date'},
                {data: 'heading', name: 'heading'},
//                {data: 'details', name: 'details'},
                {data: 'status', name: 'status'},
                {data: 'importance', name: 'importance'},
                {data: 'is_active', name: 'is_active'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
    });

    function ConfirmDelete(id) {
        var sure_del = confirm("Are you sure you want to delete this item?");
        if (sure_del) {
            var url = '<?php echo url();?>';
            window.location=(url+"/settings/delete/Notice/"+id);
        }else {
            return false;
        }
    }
</script>

<style>
    radio .error {
        outline-color: red;
    }
</style>
@endsection
