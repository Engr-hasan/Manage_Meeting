@extends('layouts.admin')

@section('content')

    <style>
        table.dataTable thead th {
          max-width: 10px !important;
        }
    </style>

    <?php
    $accessMode = ACL::getAccsessRight('Training');
//    if (!ACL::isAllowed($accessMode, 'A'))
//        die('no access right!');
//    ?>

    <div class="col-lg-12">

        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}
        <div class="panel panel-primary">

            <div class="panel-heading">

                <div class="pull-left" style="font-size: large">
                    {{trans('messages.training_schedule')}}
                </div>
                <div class="pull-right">
                    <div class="">
                        {{--if(ACL::getAccsessRight('passport-management','RC'))--}}
                        <a class="" href="{{ url('training/create-schedule') }}">
                            {!! Form::button('<i class="fa fa-plus"></i> <strong>'.trans('messages.new_training_schedule').' </strong>', array('type' => 'button', 'class' => 'btn btn-info')) !!}
                        </a>
                        {{--endif--}}
                    </div>
                </div>

                <div class="clearfix"></div>


            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="tab-content">
                    <div class="table-responsive">
                        <table id="training_list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead class="alert alert-info">
                                <tr>
                                    <th>User Type</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Training Title</th>
                                    <th>Venue</th>
                                    <th>Total Seat</th>
                                    <th>Trainer</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div><!-- /.table-responsive -->
            </div><!-- /.panel-body -->
        </div><!-- /.panel -->
    </div><!-- /.col-lg-12 -->
@endsection

@section('footer-script')
    @include('partials.datatable-scripts')
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <input type="hidden" name="pdfurl" value="<?php echo env('PDF_API_BASE_URL')?>">
    
    <script>

        $(function () {

            $('#training_list').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{url("training/get-training-schedule-data")}}',
                    method: 'post',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
                    {data: 'user_type', name: 'user_type'},
                    {data: 'date', name: 'date'},
                    {data: 'time', name: 'time'},
                    {data: 'training_title', name: 'training_title'},
                    {data: 'venue_name', name: 'venue_name'},
                    {data: 'total_seats', name: 'total_seats'},
                    {data: 'trainer_name', name: 'trainer_name'},
                    // {data: 'time', name: 'end_time'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                
                "aaSorting": []
            });


        });

        $(document).on('click','.get_crt',function(e){
            btn = $(this);
            btn_content = btn.html();
            btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;'+btn_content);
            btn.prop('disabled', true);

            training_schedule_id = $(this).attr('id');
            pdfurl = $('input[name="pdfurl"]').val();



            $.ajax({
                url: '/training/ajax-tr-certificate-letter',
                type: 'post',
                data: {
                    _token: $('input[name="_token"]').val(),
                    pdfurl: pdfurl,
                    training_schedule_id: training_schedule_id
                },
                success: function (response) {

                    if(response.responseCode == 1)
                    {
                        checkgenerator(training_schedule_id);
                    }
                    //btn.html(btn_content);
                }
            });
        });


        function checkgenerator(training_schedule_id)
        {
            pdfurl = $('input[name="pdfurl"]').val();

            $.ajax({
                url: '/training/ajax-tr-certificate-feedback',
                type: 'POST',
                data: {
                    training_schedule_id: training_schedule_id,
                    pdfurl: pdfurl,
                    _token: $('input[name="_token"]').val()},
                    dataType: 'json',
                    success: function (response) {
                        console.log(response);
                        if (response.responseCode == 1)
                        {
                            if(response.flag == 1)
                            {
                                // Need to show download & regenerate link
                                showDownloadPanel(response.id,response.certificate);
                            }
                            else if (response.flag == -1) {
                                $('.msg').html('Whoops there was some problem please contact with system admin.');
                            }
                            else if (response.flag == 2) {
                                myVar = setTimeout(checkgenerator,5000,training_schedule_id);
                            }
                        }
                        else
                        {
                            $('.msg').html('Some thing is wrong! code 1001');
                            return false;
                        }
                    }
            });
        }

        function showDownloadPanel(training_schedule_id,certificate)
        {
            pdfurl = $('input[name="pdfurl"]').val();

            $.ajax({
                url: '/training/update-tr-download-panel',
                type: 'post',
                data: {
                    _token: $('input[name="_token"]').val(),
                    pdfurl: pdfurl,
                    training_schedule_id: training_schedule_id,
                    certificate: certificate
                },
                success: function (response) {
                    //alert(training_schedule_id);
                    $('#ddl_'+training_schedule_id).html(response);
                }
            });
        }

        /*
         function togglePassportList(id)
         {
         $('.span_id_'+id).toggle();
         }
         */
    </script>
@endsection
