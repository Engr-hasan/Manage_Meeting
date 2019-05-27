@extends('layouts.admin')

@section('page_heading',trans('messages.list_config'))

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
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <div class="table-responsive">
                <table id="list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Caption</th>
                            <th>Value</th>
                            <th>Details</th>
<!--                            <th>Value2</th>
                            <th>value3</th>-->
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach($getList as $row)
                        <tr>
                            <td>{!! $i++ !!}</td>
                            <td>{!! $row->caption !!}</td>
                            <td>{!! $row->value !!}</td>
                            <td>{!!($row->details) !!}</td>
<!--                            <td>{!!($row->value2) !!}</td>
                            <td>{!!($row->value3) !!}</td>-->
                            <td>
                                {{--@if(ACL::isAllowed($accessMode,'E'))--}}
                                @if(ACL::getAccsessRight('settings','E'))
                                <a href="{!! url('settings/edit-config/'. Encryption::encodeId($row->id)) !!}" class="btn btn-xs btn-primary">
                                    <i class="glyphicon glyphicon-edit"></i> Edit
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
