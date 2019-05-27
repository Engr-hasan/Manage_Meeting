@extends('layouts.admin')

@section('page_heading', 'User Desks')

@section('content')
<?php $accessMode=ACL::getAccsessRight('settings');
if(!ACL::isAllowed($accessMode,'V')) {
    die('You have no access right! Please contact with system admin for more information.');
}
?>
<div class="col-lg-12">
    @include('partials.messages')
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="">
                @if(ACL::getAccsessRight('settings','A'))
                <a class="" href="{{ url('/settings/create-user-desk') }}">
                    {!! Form::button('<i class="fa fa-plus"></i> <b>New User Desk </b>', array('type' => 'button', 'class' => 'btn btn-info')) !!}
                </a>
                @endif
            </div>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <div class="table-responsive">
                <table id="list" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Desk</th>
                            <th>Status</th>
                            <th>Action</th>
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
            ajax: {
                url: '{{url("settings/get-user-desk-data")}}',
                data: function (d) {
                    d._token = $('input[name="_token"]').val();
                }
            },
            columns: [
                {data: 'desk_name', name: 'desk_name'},
                {data: 'desk_status', name: 'desk_status'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
    });
</script>
@endsection
