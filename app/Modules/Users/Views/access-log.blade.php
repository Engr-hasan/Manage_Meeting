
@extends('layouts.admin')
@section('content')
    <?php
    $accessMode=ACL::getAccsessRight('user');
    if(!ACL::isAllowed($accessMode,'V')){die('no access right!');}?>

    <div class="col-lg-12">
        @include('message.message')
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="pull-left">
                    <strong>Access Log History ({!! $user_name!!}, {!! $email !!}, {!! $user_phone !!})</strong>
                </div>
                <div class="pull-right">
                    <a href="{{ url('/users/failedLogin-history/' . Encryption::encodeId($email)) }} .
                            '" class="btn btn-xs btn-danger" ><i class="fa fa-exclamation-circle"></i> Failed Login History</a>
                </div>
                <div class="clearfix"></div>
            </div>

            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="accessList" class="table table-striped table-responsive table-bordered dt-responsive" width="100%" cellspacing="0" style="font-size: 14px;">
                        <thead>
                        <tr>
                            <th>Remote Address</th>
                            <th>Log in time</th>
                            <th>Log out time</th>
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
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>--}}
    @include('Users::partials.datatable')
    <script>
        $(function () {
            $('#accessList').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{url("users/get-access-log-data/".$userId)}}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'ip_address', name: 'ip_address'},
                    {data: 'login_dt', name: 'login_dt'},
                    {data: 'logout_dt', name: 'logout_dt'},

                ],
                "aaSorting": []
            });
        });

    </script>
@endsection <!--- footer-script--->

