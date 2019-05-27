@extends('layouts.admin')

@section('content')
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
                <h5><strong><i class="fa fa-list"></i> {!! trans('messages.doc_list') !!}</strong></h5>
            </div>
            <div class="pull-right">
                @if(ACL::getAccsessRight('settings','A'))
                    <a class="" href="{{ url('/settings/create-document') }}">
                        {!! Form::button('<i class="fa fa-plus"></i> <b>'.trans('messages.new_document').' </b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                @endif
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="panel-body">
            <div class="table-responsive">

                @include('partials.messages')

                <table id="list" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Document Name</th>
                            <th>Process Name</th>
                            <th>Document Priority</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

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
                    url: '{{url("settings/get-document-data")}}',
                    method: 'POST',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
                    {data: 'doc_name', name: 'doc_name'},
                    {data: 'process_type_id', name: 'process_type_id'},
                    {data: 'doc_priority', name: 'doc_priority'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        });
    </script>
    @endsection
