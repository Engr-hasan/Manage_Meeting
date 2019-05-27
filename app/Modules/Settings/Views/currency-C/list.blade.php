@extends('layouts.admin')

@section('page_heading', 'High Commissions')

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
                <a class="" href="{{ url('/settings/create-currency') }}">
                    {!! Form::button('<i class="fa fa-plus"></i> <b>New Currency </b>', array('type' => 'button', 'class' => 'btn btn-success btn-sm')) !!}
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
                            <th>#</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>USD Value</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                           <?php $i = 1; ?>
                        @foreach($rows as $row)
                             <tr>
                            <td>{!! $i++ !!}</td>
                            <td>{!! $row->code !!}</td>
                            <td>{!! $row->name !!}</td>
                            <td>{!! $row->usd_value !!}</td>
                            <td>
                                @if(ACL::getAccsessRight('settings','E'))
                                <a href="{!! url('settings/edit-currency/'. Encryption::encodeId($row->id)) !!}" class="btn btn-xs btn-primary">
                                    <i class="fa fa-folder-open-o"></i> Open
                                </a>
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
            "iDisplayLength": 20
        });
    });
</script>
@endsection
