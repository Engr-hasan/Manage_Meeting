@extends('layouts.admin')

@section('page_heading',trans('messages.bank_list'))

@section('content')
    <div class="col-md-12 col-lg-12">
        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}
    </div>
<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'V')) {
    die('You have no access right! Please contact with system admin for more information.');
}
?>
<div class="col-lg-12 col-md-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
                <div class="pull-left">
                    <h5><i class="fa fa-list"></i> <strong>{!! trans('messages.branch_form') !!}</strong></h5>
                </div>
                <div class="pull-right">
                    @if(ACL::getAccsessRight('settings','A'))
                    <a class="" href="{{ url('/settings/create-branch') }}">
                        {!! Form::button('<i class="fa fa-plus"></i><b>'.trans('messages.new_branch').'</b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
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
                            <th>Branch Name</th>
                            <th>Branch Code</th>
                            <th>Address</th>
                            <th>Manager Info</th>
                            <th>Activation Status</th>
                            <th width="10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach($getList as $row)
                        <tr>
                            <td>{!! $i++ !!}</td>
                            <td>{!! $row->branch_name !!}</td>
                            <td>{!! $row->branch_code !!}</td>
                            <td>{!! $row->address !!}</td>
                            <td>{!! $row->manager_info !!}</td>
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
                                @if(ACL::getAccsessRight('settings','V'))
                                <a href="{!! url('settings/view-branch/'. Encryption::encodeId($row->id)) !!}" class="btn btn-xs btn-primary">
                                    <i class="fa fa-folder-open-o"></i> Open
                                </a>
                                <a href="javascript:void(0)"
                                   class="btn btn-xs btn-danger" onclick="ConfirmDelete('{{Encryption::encodeId($row->id)}}')">
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
            window.location=(url+"/settings/delete/Branch/"+id);
        }else {
            return false;
        }
    }
</script>
@endsection
