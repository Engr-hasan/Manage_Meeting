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
                <b>Home Page Slider</b>
            </div>
            <div class="pull-right">
                @if(ACL::getAccsessRight('settings','A'))
                <a class="" href="{{ url('/settings/create-home-page-slider') }}">
                    {!! Form::button('<i class="fa fa-plus"></i>  Create Home Page Slider ', array('type' => 'button', 'class' => 'btn btn-info')) !!}
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
                            <th>Slider Image</th>
                            <th>Title</th>
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
            aaSorting: [],
            ajax: {
                url: '{{url("settings/get-home-page-slider-details-data")}}',
                data: function (d) {
                    d._token = $('input[name="_token"]').val();
                }
            },
            columns: [
                {data: 'slider_image', name: 'slider_image'},
                {data: 'slider_title', name: 'slider_title'},
                {data: 'status', name: 'status'},
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
