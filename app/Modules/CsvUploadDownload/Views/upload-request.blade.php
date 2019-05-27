@extends('layouts.admin')

@section('page_heading','Data Preview')

@section('content')
    <div class="col-lg-12">
        <div class="hidden" >
            {!! Session::has('hiddenMsg') ? Session::get("hiddenMsg") : '' !!}
        </div>
        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

        <div class="panel panel-info">
            <div class="panel-heading">
                <strong>Preview of data before saving [{{ $tableName }}]</strong>
            </div><!-- /.panel-heading -->

            <div class="panel-body">
                {!! Form::open(array('url' => '/do-request/save-data/','method' => 'post', 'class' => 'form-horizontal', 'id' => 'doRequestsForm',
                'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                <input type="hidden" name="table_name" value="{{ $tableName }}">

                <div class="errormsg"></div>

                <div class="table-responsive" style="clear:both">
                    <table id="list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <?php
                        $keys = array_keys($excelData[0]);
                        ?>

                        <thead>
                        <tr>
                            @foreach ($keys as $value)
                                <input type="hidden" name="table_field[]" value="{{ $value }}">
                                <th>  {{$value}}  </th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($excelData as $excelRow)
                            <?php
                            $class = 'text-success';
                            ?>
                            <tr>
                                @foreach ($excelRow as $key => $value)
                                    <input type="hidden" name="{{ $key.'[]' }}" value="{{ $value }}">
                                    <td>  {{$value}}  </td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @if($alterStatus == 'off')
                <div class="clearfix"><br/></div>
                <div class="col-md-12 clearfix">
                    <div class="pull-left">
                        <a href="{{URL::to('/csv-upload/list')}}" class="btn btn-sm btn-default"><i class="fa fa-times" aria-hidden="true"></i>
                            <strong>Close</strong></a>
                    </div>
                    <div class="pull-right">
                        <button type="submit" id="saveCsvData" class="btn btn-sm btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i>
                            <strong>Save Data </strong> </button>
                    </div>
                </div>
                @endif
            {!! Form::close() !!}<!-- /.form end -->

            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->

        @if($alterStatus == 'create')
            @include("CsvUploadDownload::newTableForm")
        @endif

        @if($alterStatus == 'edit')
            @include("CsvUploadDownload::newfields")
        @endif
    </div>
    <!-- /.col-lg-12 -->

@endsection

@section('footer-script')
    <script>
        $(document).ready(function () {
//        $("#saveCsvData").on('click', function () {
//            var request_title = $('#request_title').val();
//            if (request_title.trim() == '') {
//                alert("Please select a request title");
//                $('#request_title').addClass('error');
//                return false;
//            } else {
//                $('#request_title').removeClass('error');
//            }
//        });
        });
    </script>
@endsection
