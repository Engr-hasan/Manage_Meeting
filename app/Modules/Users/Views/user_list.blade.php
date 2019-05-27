@extends('layouts.admin')
@section('content')
<?php use App\Libraries\ACL;$accessMode=ACL::getAccsessRight('user');if(!ACL::isAllowed($accessMode,'V')){die('no access right!');}?>

<div class="col-lg-12">
    @include('message.message')
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="pull-left" style="line-height: 35px;">
                <strong><i class="fa fa-list"></i> {{ trans('messages.user_list') }}</strong>
            </div>
            <div class="pull-right">
                <!--1x101 is Sys Admin, 7x712 is SB Admin, 11x422 is Bank Admin-->
                @if(ACL::getAccsessRight('user','A'))
                    <a class="" href="{{ url('/users/create-new-user') }}">
                        {!! Form::button('<i class="fa fa-plus"></i><b> ' .trans('messages.new_user').'</b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                @endif
            </div>
            <div class="clearfix"></div>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <div class="table-responsive">
                <table id="list" class="table table-striped table-bordered dt-responsive " cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>User Full Name</th>
                            <th>Email Address</th>
                            <th>User Type</th>
                            <th>Company</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Member Since</th>
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

@endsection <!--content section-->
@section('footer-script')
@include('Users::partials.datatable')
<script>
    $(function () {
        $('#list').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{url("users/get-row-details-data")}}',
                method:'post',
                data: function (d) {
                    d._token = $('input[name="_token"]').val();
                }
            },
            columns: [
                {data: 'user_full_name', name: 'user_full_name'},
                {data: 'user_email', name: 'user_email'},
                {data: 'type_name', name: 'type_name'},
                {data: 'company_name', name: 'company_name'},
                {data: 'users_district', name: 'users_district'},
                {data: 'user_status', name: 'user_status'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "aaSorting": []
        });
    });
</script>
@endsection <!--- footer-script--->

