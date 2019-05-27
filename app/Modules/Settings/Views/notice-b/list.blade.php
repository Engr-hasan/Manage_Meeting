@extends('layouts.admin')
@section('content')
<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'V'))
    die('no access right!');
?>
<div class="col-lg-12">

    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="pull-left" style="font-size: large;">
                <b>Notice</b>
            </div>
            <div class="pull-right">
                @if(ACL::getAccsessRight('settings','A'))
                <a class="" href="{{ url('/settings/create-notice') }}">
                    {!! Form::button('<i class="fa fa-plus"></i>  Create Notice', array('type' => 'button', 'class' => 'btn btn-info')) !!}
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
                            <th>Date</th>
                            <th>Heading</th>
                            <th>Details</th>
                            <th>Status</th>
                            <th>Importance</th>
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
            aaSorting: [],
            ajax: {
                url: '{{url("settings/get-notice-details-data")}}',
                data: function (d) {
                    d._token = $('input[name="_token"]').val();
                }
            },
            columns: [
                {data: 'update_date', name: 'update_date'},
                {data: 'heading', name: 'heading'},
                {data: 'details', name: 'details'},
                {data: 'status', name: 'status'},
                {data: 'importance', name: 'importance'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
    });
</script>

<style>
    radio .error {
       outline-color: red;
    }
</style>
@endsection
