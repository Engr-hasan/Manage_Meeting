@extends('layouts.admin')

@section('page_heading',trans('messages.doc_list'))

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
                    <h5><i class="fa fa-list"></i> <strong>List of Parks</strong></h5>
                </div>
                <div class="pull-right">
                    @if(ACL::getAccsessRight('settings','A'))
                        <a class="" href="{{ url('/settings/create-park-info') }}">
                            {!! Form::button('<i class="fa fa-plus"></i> <b>New Park </b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
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
                            <th>Name</th>
                            <th>Upazilla</th>
                            <th>District</th>
                            <th>Area</th>
                            <th>Active Status</th>
                            <th width="9%">Action</th>
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
                        iDisplayLength: 50,
                        ajax: {
                            url: '{{url("settings/get-eco-park-data")}}',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        },
                        columns: [
                            {data: 'park_name', name: 'park_name'},
                            {data: 'upazilla_name', name: 'upazilla_name'},
                            {data: 'district_name', name: 'district_name'},
                            {data: 'park_area', name: 'park_area'},
                            {data: 'status', name: 'status'},
                            {data: 'action', name: 'action', orderable: false, searchable: false}
                        ]
                    });
                });

                function redirect_to(id){
                    var url = '<?php echo url();?>';
                    window.location=(url+"/settings/delete/park-info/"+id);
                }
                function ConfirmDelete(id) {
                    var sure_del = confirm("Are you sure you want to delete this item?");
                    if (sure_del) {
                        redirect_to(id);
                    }else {
                        return false;
                    }
                }
            </script>
@endsection
