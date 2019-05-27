@extends('layouts.admin')

@section('page_heading', 'High Commissions')

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
                    <h5><strong><i class="fa fa-list"></i> <strong>List of High Commissions</strong></strong></h5>
                </div>
                <div class="pull-right">
                    @if(ACL::getAccsessRight('settings','A'))
                        <a class="" href="{{ url('/settings/create-high-commission') }}">
                            {!! Form::button('<i class="fa fa-plus"></i> <b>New High Commission </b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
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
                            <th>Country</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Email</th>
                            <th>Active Status</th>
                            <th width="10%">Action</th>
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
                ajax: {
                    url: '{{url("settings/get-high-commission-data")}}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'country', name: 'country'},
                    {data: 'name', name: 'name'},
                    {data: 'address', name: 'address'},
                    {data: 'email', name: 'email'},
                    {data: 'is_active', name: 'is_active'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        });
        function redirect_to(id){
            var url = '<?php echo url();?>';
            window.location=(url+"/settings/delete/HighCommissions/"+id);
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
