@extends('layouts.admin')

@section('content')

    @include('partials.messages')
    <?php
    $accessMode = ACL::getAccsessRight('settings');
    if (!ACL::isAllowed($accessMode, 'V')) {
        die('You have no access right! Please contact with system admin for more information.');
    }
    ?>
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                    <div class="pull-left">
                        <h5><strong><i class="fa fa-list"></i> <strong>List of Companies</strong></strong></h5>
                    </div>
                    <div class="pull-right">
                        @if(ACL::getAccsessRight('settings','A'))
                            <a class="" href="{{ url('/settings/create-company') }}">
                                {!! Form::button('<i class="fa fa-plus"></i><b> New Company</b>', array('type' => 'button',
                                'class' => 'btn btn-default')) !!}
                            </a>
                        @endif
                    </div>
                <div class="clearfix"></div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Company Name</th>
                            <th>Approval Status</th>
                            <th>Created At</th>
                            {{--<th>Last updated</th>--}}
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div><!-- /.table-responsive -->
            </div><!-- /.panel-body -->
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
                iDisplayLength:50,
                ajax: {
                    url: '{{url("settings/get-company-data")}}',
                    method: 'post',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
                    {data: 'company_name', name: 'company_name'},
                    {data: 'is_approved', name: 'is_approved'},
                    {data: 'created_at'},
                    {data:'action', name: 'created_at', orderable: false, searchable: false}
                ],
                "aaSorting": []
            });
        });
    </script>
@endsection
