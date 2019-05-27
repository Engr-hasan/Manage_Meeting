@extends('layouts.admin')

@section('page_heading','List of CSV')

@section('content')
<div class="col-lg-12">
    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

    <!-- Modal -->
    <div class="modal fade" id="popUp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content" id="popUpId"></div>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="clearfix">
                <div class="pull-left" style="font-size: large">List</div>
                <div class="pull-right">
                   <a class="btn btn-default btn-sm addRequest" data-toggle="modal" data-target="#popUp"
                      href="{!! url('/csv-upload/import/') !!}">
                       <i class="fa fa-plus"></i> <strong> Upload CSV </strong> </a>
                </div>
            </div>
        </div><!-- /.panel-heading -->

        <div class="panel-body">

            <div class="table-responsive">
                <table id="list" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                    <thead class="alert alert-info">
                        <tr>
                            <th>#</th>
                            <th>File name</th>
                            <th>Uploaded By</th>
                            <th>Date</th>
                            <th width="7%" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div> <!-- /.table-responsive -->

        </div> <!-- /.panel-body -->
    </div><!-- /.panel -->
        
{{--    @include("CsvUploadDownload::newfields")--}}
    {{--@include("CsvUploadDownload::newTableForm")--}}
    
</div><!-- /.col-lg-12 -->

@endsection

@section('footer-script')
@include('partials.datatable-scripts')
<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
<script>
    $(function () {
        $('#list').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{url("csv-upload/list/get-list")}}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [
                {data: 'serial', name: 'serial'},
                {data: 'file_name', name: 'file_name'},
                {data: 'uploaded_by', name: 'uploaded_by'},
                {data: 'upload_date', name: 'upload_date'},
                {data: 'action', name: 'action', orderable: false, searchable: true}
            ],
            "aaSorting": []
        });

    });
</script>
@endsection

