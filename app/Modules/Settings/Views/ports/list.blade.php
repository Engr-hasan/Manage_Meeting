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
                    <h5><i class="fa fa-list"></i> <strong>List of Port</strong></h5>
                </div>
                <div class="pull-right">
                    @if(ACL::getAccsessRight('settings','A'))
                        <a class="" href="{{ url('/settings/create-port') }}">
                            {!! Form::button('<i class="fa fa-plus"></i> <b>New Port </b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
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
                            <th>#</th>
                            <th>Country</th>
                            <th>Port Name</th>
                            <th>Active Status</th>
                            <th width="10%">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1;?>
                        @foreach($rows as $row)
                            <tr>
                                <td>{!! $i++ !!}</td>
                                <td>{!! $row->country !!}</td>
                                <td>{!! $row->port_name !!}</td>
                                <td>
                                    <?php
                                    if ($row->is_active == 1) {
                                        $class = 'text-success';
                                        $status = 'Active';
                                    } else {
                                        $class = 'text-danger';
                                        $status = 'Inactive';
                                    }
                                    ?>
                                    <span class="{{ $class }}"><b>{{  $status }}</b></span>
                                </td>
                                <td>
                                    @if(ACL::getAccsessRight('settings','E'))
                                        <a href="{!! url('settings/edit-port/'. Encryption::encodeId($row->port_id)) !!}" class="btn btn-xs btn-primary">
                                            <i class="fa fa-folder-open-o"></i> Open
                                        </a>
                                        <a href="javascript:void(0)"
                                           class="btn btn-xs btn-danger" onclick="ConfirmDelete('{{Encryption::encodeId($row->port_id)}}')">
                                            <i class="fa fa-times"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div><!-- /.table-responsive -->
            </div><!-- /.panel-body -->
        </div><!-- /.panel -->
    </div><!-- /.col-lg-12 -->

@endsection

@section('footer-script')

    @include('partials.datatable-scripts')
    <script>
        $(function () {
            $('#list').DataTable({
                "paging": true,
                "lengthChange": true,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "iDisplayLength": 50
            });
        });
        function ConfirmDelete(id) {
            var sure_del = confirm("Are you sure you want to delete this item?");
            if (sure_del) {
                var url = '<?php echo url();?>';
                window.location=(url+"/settings/delete/Port/"+id);
            }else {
                return false;
            }
        }
    </script>
@endsection
