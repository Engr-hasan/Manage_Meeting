@extends('layouts.admin')

@section('page_heading',trans('messages.list_notification'))

@section('content')
<?php $accessMode=ACL::getAccsessRight('settings');
if(!ACL::isAllowed($accessMode,'V')) die('no access right!');
?>
<div class="col-lg-12">

    @include('partials.messages')

    <div class="panel panel-primary">
         <div class="panel-heading">
            <div class="">
                &nbsp;
<!--                <a class="" href="{{ url('/settings/create-bank') }}">
                    {!! Form::button('<i class="fa fa-plus"></i><b> ' .trans('messages.config').'</b>', array('type' => 'button', 'class' => 'btn btn-info')) !!}
                </a>-->
            </div>
        </div> <!-- /.panel-heading -->
        <div class="panel-body">
            <div class="table-responsive">
                <table id="list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Source</th>
                            <th>Referral ID</th>
                            <th>destination</th>
                            <th>MSG Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach($getList as $row)
                        <tr>
                            <td>{!! $i++ !!}</td>
                            <td>{!! substr($row->source, 0, 25) !!}...</td>
                            <td>{!! $row->ref_id !!}</td>
                            <td>{!!($row->destination) !!}</td>
                            <td>{!!($row->msg_type) !!}</td>
                            <td>
                                @if(ACL::getAccsessRight('settings','V'))
                                <a href="{!! url('settings/view-notify/'. Encryption::encodeId($row->id)) !!}" class="btn btn-xs btn-primary">
                                    <i class="glyphicon glyphicon-list"></i> Open
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
