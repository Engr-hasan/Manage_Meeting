@extends('layouts.admin')


@section('content')
    <style>.err_msg_hide{display:none;}</style>
    <div class="col-lg-12">

        <div class="col-md-12" id="response_msg">
            <div class="alert alert-danger alert-dismissible err_msg_hide"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><span id="err_msg"></span></div>
            {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
            {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}
        </div>

        <section class="col-md-12">
            <div class="col-md-12">

                <div class="panel panel-primary">
                    <div class="panel-group panel-primary">
                        <div class="panel-heading">
                            <b> {{$training_schedule->training_title}} </b>
                        </div><!-- /.panel-heading -->

                        <div class="panel-body">
                            <div class="col-md-12 {{$errors->has('user_types') ? 'has-error' : ''}}">
                                {!! Form::label('user_types','User Types: ',['class'=>'col-md-3']) !!}
                                <div class="col-md-6"> {{ $training_schedule->public_user_types }}</div>
                            </div>

                            <div class="col-md-12 {{$errors->has('training_title') ? 'has-error' : ''}}">
                                {!! Form::label('training_title','Training Name: ',['class'=>'col-md-3']) !!}
                                <div class="col-md-6"> {{ $training_schedule->training_title }}</div>
                            </div>

                            <div class="col-md-12 {{$errors->has('venue_title') ? 'has-error' : ''}}">
                                {!! Form::label('venue_title','Venue Name: ',['class'=>'col-md-3']) !!}
                                <div class="col-md-6">{{ $training_schedule->venue }}</div>
                            </div>

                            <div class="col-md-12 {{$errors->has('trainer_name') ? 'has-error' : ''}}">
                                {!! Form::label('trainer_name','Trainer Name: ',['class'=>'col-md-3']) !!}
                                <div class="col-md-6">{{ $training_schedule->trainer_name }}</div>
                            </div>

                            <div class="col-md-12 {{$errors->has('total_seats') ? 'has-error' : ''}}">
                                {!! Form::label('total_seats','Total Seats: ',['class'=>'col-md-3']) !!}
                                <div class="col-md-6">{{ $training_schedule->total_seats }}</div>
                            </div>

                            <div class="col-md-12 {{$errors->has('phone') ? 'has-error' : ''}}">
                                {!! Form::label('start_time','Training Start Time: ',['class'=>'col-md-3']) !!}
                                <div class="col-md-6">
                                    <?php
                                    if (!empty($training_schedule->start_time)) {
                                        $start_time = date('jS \of F Y \a\t h:i:s A', strtotime($training_schedule->start_time));
                                    } else {
                                        $start_time = '';
                                    }
                                    ?>
                                    {{ $start_time }}
                                </div>
                            </div>

                            <div class="col-md-12 {{$errors->has('end_time') ? 'has-error' : ''}}">
                                {!! Form::label('end_time','Training End Time: ',['class'=>'col-md-3']) !!}
                                <div class="col-md-6">
                                    <?php
                                    if (!empty($training_schedule->end_time)) {
                                        $end_time = date('jS \of F Y \a\t h:i:s A', strtotime($training_schedule->end_time));
                                    } else {
                                        $end_time = '';
                                    }
                                    ?>
                                    {{ $end_time }}
                                </div>
                            </div>

                            <div class="col-md-12 {{$errors->has('phone') ? 'has-error' : ''}}">
                                {!! Form::label('location','Status: ',['class'=>'col-md-3']) !!}
                                <div class="col-md-6">{{ ($training_schedule->status==1) ? 'Active' : 'Inactive' }}</div>
                            </div>
                            <div class="col-md-12"><div class="row"><br/></div></div>
                                <div class="col-md-12">
                                    <div class="col-md-2">
                                        <?php
                                        $clz_url = 'training/view/'.Encryption::encodeId($training_schedule->training_id);
                                        if(in_array(Auth::user()->user_type, CommonFunction::trainingAdmin()))
                                        {
                                            $clz_url = 'training/schedule';
                                        }
                                        ?>
                                        <a href="{{ url ($clz_url) }}">
                                            {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                                        </a>

                                    </div>
                                    <div class="col-md-6 col-md-offset-1">
                                        {!! CommonFunction::showAuditLog($training_schedule->updated_at, $training_schedule->updated_by) !!}
                                    </div>
                                    <div class="col-md-12">

                                            <div class="col-md-8 pull-right">
                                                @if(time() >= strtotime($training_schedule->start_time) && in_array(Auth::user()->user_type,CommonFunction::trainingAdmin()))
                                                <div class="col-md-9">
                                                    <span id="certificate-div">
                                                        <div class="col-md-6">
                                                            @if($training_schedule->certificate !=  '')
                                                                <a target="_blank" href="{{ $training_schedule->certificate }}" class="btn btn-danger download_crt " id="dl_'{{Encryption::encodeId($training_schedule->id)}}'">Download Certificate</a>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6">
                                                        @if($training_schedule->certificate !=  '')
                                                            <span id="ddl_{{Encryption::encodeId($training_schedule->id)}}"><a href="javascript:void(0);" class="btn btn-warning get_crt " id="{{Encryption::encodeId($training_schedule->id)}}">Re Generate</a></span>
                                                        @else
                                                            <span id="ddl_{{Encryption::encodeId($training_schedule->id)}}"><a href="javascript:void(0);" class="btn btn-info get_crt " id="{{Encryption::encodeId($training_schedule->id)}}">Get Certificate</a></span>
                                                        @endif
                                                        </div>
                                                    </span>
                                                </div>
                                                @endif
                                                <div class="col-md-3">

                                                    @if(in_array(Auth::user()->user_type, CommonFunction::trainingAdmin()))
                                                    <a href="{{ url('training-schedule/edit/'. Encryption::encodeId($training_schedule->id)) }}">
                                                        {!! Form::button('<i class="fa fa-edit"></i>&nbsp;<b>Edit Training </b>', array('type' => 'button', 'class' => 'btn btn-primary ')) !!}
                                                    </a>
                                                    @endif
                                                </div>
                                            </div>

                                    </div>

                                    <!--
                                    <div>&nbsp;</div>
                                    <div>&nbsp;</div>
                                    <div>&nbsp;</div>
                                    <div class="col-md-2">
                                        &nbsp;
                                    </div>
                                    <div class="col-md-6 col-md-offset-1">
                                        &nbsp;
                                    </div>
                                    <div class="col-md-2">
                                        <span id="ddl_rBD6pLpdwwzJWrqZSYTch0uwpuWfAs1fn0skVxu-kyg">&nbsp;&nbsp;<a href="javascript:void(0);" class="btn btn-xs btn-primary get_crt" id="rBD6pLpdwwzJWrqZSYTch0uwpuWfAs1fn0skVxu-kyg"><i class="fa fa-edit"></i><b> Get Certificate</b></a></span>
                                    </div>
                                    -->

                                </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                @include('Training::schedule.schedule-assign')
            </div>
        </section>
        @if(in_array(Auth::user()->user_type, CommonFunction::trainingAdmin()))
        <section class="col-md-12">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="pull-left" style="font-size: large;">{{trans('messages.trainee_lists')}}</div>
                        <div class="pull-right">
                            <a href="{{ url('download/trainee-list/'.Encryption::encodeId($training_schedule->id)) }}" class="pull-right btn btn-default">Download Participant List &nbsp;<i class="fa fa-download"></i></a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="table-responsive">
                                <table id="trainee_list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" role="grid">
                                    <thead class="alert alert-info">
                                        <tr>
                                            <th>
                                                {!! Form::checkbox('chk_id','chk_id','',['class'=>'selectall', 'id'=>'chk_id']) !!}
                                            </th>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Organization</th>
                                            <th>District</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $row_sl = 0;?>
                                        @foreach($participant_list as $participant)
                                            <?php $row_sl++ ?>
                                            <tr>
                                                <td>
                                                    {!! Form::checkbox('application[]',$participant->id, '',['class'=>'appCheckBox', 'onChange' => 'changeStatus(this.checked)']) !!}
                                                    {!! Form::hidden('hdn_batch[]',$participant->schedule_id, ['class'=>'hdnStatus','id'=>"".$participant->id."_status"]) !!}
                                                </td>
                                                <td>{!! $row_sl !!}</td>
                                                <td>{!! $participant->name !!}</td>
                                                <td>{!! $participant->email !!}</td>
                                                <?php
                                                $organization = '';
                                                if (!empty($participant->bank)){
                                                    $organization = $participant->bank;
                                                }
                                                else if (!empty($participant->agency_license)){
                                                    $organization = "<span>". $participant->agency_name ."<b> (" . $participant->agency_license .")</b></span>";
                                                }
                                                ?>
                                                <td>{!! $organization !!}</td>
                                                <td>{!! $participant->district !!}</td>
                                                <?php
                                                if ($participant->status == 1){
                                                    $status =  "<span class='text-info status'>Applied</span>";
                                                }
                                                elseif($participant->status == 2){
                                                    $status = "<span class='text-success status'>Verified</span>";
                                                }
                                                elseif($participant->status == 3){
                                                    $status = "<span class='text-success status'>Participated</span>";
                                                }
                                                elseif($participant->status == 0){
                                                    $status = "<span class='text-danger status'>Declined</span>";
                                                }
                                                elseif($participant->status == 4){
                                                    $status = "<span class='text-danger status'>Declined(Abs.)</span>";
                                                }
                                                ?>
                                                <td>{!! $status !!}</td>
                                                <?php
                                                $action = '<a href="' . url('training-participant/view/' . Encryption::encodeId($participant->id)) .
                                                        '" class="btn btn-xs btn-info"><i class="fa fa-folder-open-o"></i> Open</a>';
                                                if ($participant->status == 1){
                                                    $action .= '<span id="phase_'.Encryption::encodeId($participant->id).'">&nbsp;&nbsp;<a href="'.url('training/decline-from-training/' . Encryption::encodeId($participant->id)).'" class="btn btn-xs btn-danger" id="'.Encryption::encodeId($participant->id).'">&nbsp; Decline</a>';
                                                    $action .= '&nbsp;&nbsp;<a href="'.url('training/verify-training-applicant/' . Encryption::encodeId($participant->id)).'" class="btn btn-xs btn-success" id="'.Encryption::encodeId($participant->id).'">&nbsp; Verify &nbsp;</a></span>';
                                                }
                                                else if ($participant->status == 2)
                                                {
                                                    $action .= '<span id="phase_'.Encryption::encodeId($participant->id).'">&nbsp;&nbsp;<a href="javascript:void(0);"  class="btn btn-xs btn-danger absentParticipant" id="'.Encryption::encodeId($participant->id).'">&nbsp; Decline</a>';
                                                    $action .= '&nbsp;&nbsp;<a href="javascript:void(0);" class="btn btn-xs btn-primary presentParticipant" id="'.Encryption::encodeId($participant->id).'">&nbsp; Participate &nbsp;</a></span>';
                                                }
                                                ?>
                                                <td>{!! $action !!}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif
    </div>
@endsection

@section('footer-script')
    @include('partials.datatable-scripts')
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <input type="hidden" name="pdfurl" value="<?php echo env('PDF_API_BASE_URL'); ?>">
    <input type="hidden" name="schedule_id" value="<?php echo Encryption::encodeId($training_schedule->id); ?>">
    <input type="hidden" name="training_id" value="<?php echo Encryption::encodeId($training_schedule->training_id); ?>">
    <script>
        var numberOfCheckedBox = 0;
        function setCheckBox() {
            numberOfCheckedBox = 0;
            var flag = 1;
            var selectedWO = $("input[type=checkbox]").not(".selectall");
            selectedWO.each(function () {
                if (this.checked) {
                    numberOfCheckedBox++;
                } else {
                    flag = 0;
                }
            });
            if (flag == 1) {
                $("#chk_id").checked = true;
            } else {
                $("#chk_id").checked = false;
            }
            if (numberOfCheckedBox >= 1) {
                $('.applicable_status').trigger('click');
            }
        } // end of setCheckBox()

        function changeStatus(check) {
            $('#status_id').html('<option selected="selected" value="">Select Below</option>');
            setCheckBox();
        } // end of changeStatus();

        var base_checkbox = '.selectall';
        $(base_checkbox).click(function () {
            if (this.checked) {
                $('.appCheckBox:checkbox').each(function () {
                    this.checked = true;
                    $('#status_id').html('<option selected="selected" value="">Select Below</option>');
                });
            } else {
                $('.appCheckBox:checkbox').each(function () {
                    this.checked = false;
                    $('#status_id').html('<option selected="selected" value="">Select Below</option>');
                });
            }
            $('#status_id').html('<option selected="selected" value="">Select Below</option>');
            setCheckBox();
        });
        $('.appCheckBox:checkbox').not(base_checkbox).click(function () {
            $(".selectall").prop("checked", false);
        });

        curr_schedule_id = $('input[name="schedule_id"]').val();
        training_id = $('input[name="training_id"]').val();
        $(".applicable_status").click(function () {
            $.ajax({
                url: '/training/schedule-list-for-assign',
                type: 'post',
                data: {
                    _token: $('input[name="_token"]').val(),
                    curr_schedule_id: curr_schedule_id,
                    training_id: training_id
                },
                success: function (response) {

                    if (response.responseCode == 1) {
                        var option = '';
                        option += '<option selected="selected" value="">Select Below</option>';
                        $.each(response.data, function (id, value) {
                            option += '<option value="' + value.schedule_id + '">' + value.schedule_heading + '</option>';
                        });
                        $("#status_id").html(option);
                        $("#status_id").trigger("change");
                        $("#status_id").focus();
                    }else {
                        $('#status_id').html('Please wait');
                    }
                    //btn.html(btn_content);
                }
            });
        });
        $(".send").click(function () {
            var all_participant_id = [];
            $('.appCheckBox').each(function () {
                if ($(this).is(':checked')) {
                    all_participant_id.push($(this).val());
                }
            });
            if (all_participant_id == '') {
                alert("Please select participant to transfer them another schedule.");
                return false;
            }
            $.ajax({
                url: '/training/schedule-assign-for-selected-participants',
                type: 'post',
                data: {
                    _token: $('input[name="_token"]').val(),
                    training_id: training_id,
                    curr_schedule_id: curr_schedule_id,
                    applied_schedule_id: $("select#status_id option:checked").val(),
                    all_participant_id: all_participant_id
                },
                success: function (response) {

                    if (response.responseCode == 1) {
                        window.location.hash = '#response_msg';
                        $('.err_msg_hide').show();
                        $('.err_msg_hide').css({
                            background : '#DFF2BF',
                        });
                        $('#err_msg').css({
                            color: '#31510e'
                        });
                        $('#err_msg').html(response.data);
                        window.location.reload();
                    }else if(response.responseCode == 0) {
                        window.location.hash = '#response_msg';
                        $('.err_msg_hide').show();
                        $('#err_msg').html(response.data);
                    }
                    //btn.html(btn_content);
                }
            });
        });

        /*$(document).on('click','.get_crt',function(e){
            btn = $(this);
            btn_content = btn.html();
            btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;'+btn_content);
            btn.prop('disabled', true);

            training_participant_id = $(this).attr('id');
            pdfurl = $('input[name="pdfurl"]').val();



            $.ajax({
                url: '/training/ajax-certificate-letter',
                type: 'post',
                data: {
                    _token: $('input[name="_token"]').val(),
                    pdfurl: pdfurl,
                    training_participant_id: training_participant_id
                },
                success: function (response) {

                    if(response.responseCode == 1)
                    {
                        checkgenerator(training_participant_id);
                    }
                    //btn.html(btn_content);
                }
            });
        });




        function checkgenerator(training_participant_id)
        {
            pdfurl = $('input[name="pdfurl"]').val();

            $.ajax({
                url: '/training/ajax-certificate-feedback',
                type: 'POST',
                data: {
                    training_participant_id: training_participant_id,
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
                                myVar = setTimeout(checkgenerator,5000,training_participant_id);
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


        function showDownloadPanel(training_participant_id,certificate)
        {
            pdfurl = $('input[name="pdfurl"]').val();

            $.ajax({
                url: '/training/update-download-panel',
                type: 'post',
                data: {
                    _token: $('input[name="_token"]').val(),
                    pdfurl: pdfurl,
                    training_participant_id: training_participant_id,
                    certificate: certificate
                },
                success: function (response) {
                    //alert(training_participant_id);
                    //$('#ddl_'+training_participant_id).html(response);
                }
            });
        }*/

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
            // console.log(training_schedule_id);
        

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
                    // $('#ddl_'+training_schedule_id).html(response);
                    $("#certificate-div").html(response);
                }
            });
        }

        $(document).ready(function(){
            $("#trainee_list").DataTable({
                "paging": true,
                "lengthChange": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "iDisplayLength": 50
            });
        });

        {{--$(function () {--}}
            {{--$('#trainee_list').DataTable({--}}
                {{--processing: true,--}}
                {{--serverSide: true,--}}
                {{--ajax: {--}}
                    {{--url: '{{url( 'training/get-trainee-list' )}}',--}}
                    {{--method: 'post',--}}
                    {{--data: function (d) {--}}
                        {{--d._token = $('input[name="_token"]').val();--}}
                        {{--d.schedule_id = $('input[name="schedule_id"]').val();--}}
                    {{--}--}}
                {{--},--}}
                {{--columns: [--}}
                    {{--{data: 'check', name: 'check'},--}}
                    {{--{data: 'name', name: 'name'},--}}
                    {{--{data: 'email', name: 'email'},--}}
                    {{--{data: 'organization', name: 'organization'},--}}
                    {{--{data: 'district', name: 'district'},--}}
                    {{--{data: 'status', name: 'status'},--}}
                    {{--{data: 'action', name: 'action', orderable: false, searchable: false}--}}
                {{--],--}}
                {{--"aaSorting": []--}}
            {{--});--}}

        {{--});--}}

//        decline from Training




        $(document).on('click','.declineFromTraining',function(e){
            btn = $(this);
            btn_content = btn.html();
            btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;'+btn_content);
            var participant_id = btn.attr('id');

            $.ajax({
                url: '{{url("training/decline-from-training")}}',
                type: 'post',
                data: {
                    _token: $('input[name="_token"]').val(),
                    participant_id: participant_id
                },
                dataType: 'json',
                success: function (response) {
                    /*
                    if(response.responseCode == 1)
                    {
                        $('#phase_'+participant_id).html(response.html);
                    }
                    */
                    $('#phase_'+participant_id).html(response.html);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);

                },
                beforeSend: function (xhr) {
                    console.log('before send');
                },
                complete: function () {
                    //completed
                }
            });
        });




        // Verify the training applicant
        $(document).on('click','.verifyTrainingParticipant',function(e){
            btn = $(this);
            btn_content = btn.html();
            btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;'+btn_content);
            participant_id = btn.attr('id');
            btn.prop('disabled', true);


            $.ajax({
                url: '{{url("training/verify-training-applicant")}}',
                type: 'post',
                data: {
                    _token: $('input[name="_token"]').val(),
                    participant_id: participant_id
                },
                dataType: 'json',
                success: function (response) {
                    /*
                    if(response.responseCode == 1)
                    {
                        $('#phase_'+participant_id).html(response.html);
                    }
                    */
                    $('#phase_'+participant_id).html(response.html);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);

                },
                beforeSend: function (xhr) {
                    console.log('before send');
                },
                complete: function () {
                    //completed
                }
            });
        });



        $(document).on('click','.presentParticipant',function(e){
            btn = $(this);
            btn_content = btn.html();
            btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;'+btn_content);
            participant_id = btn.attr('id');
            btn.prop('disabled', true);

            $.ajax({
                url: '{{url("training/present-participant")}}',
                type: 'post',
                data: {
                    _token: $('input[name="_token"]').val(),
                    participant_id: participant_id
                },
                dataType: 'json',
                success: function (response) {
                    if(response.responseCode == 1)
                    {
                        $('#phase_'+participant_id).html('');
                    }
                    else if(response.responseCode == 0)
                    {
                        btn.prop('disabled', false);
                        btn.html(btn_content);
                        alert(response.msg);
                        return false;
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);

                },
                beforeSend: function (xhr) {
                    console.log('before send');
                },
                complete: function () {
                    //completed
                }
            });
        });

        $(document).on('click','.absentParticipant',function(e){
            btn = $(this);
            btn_content = btn.html();
            btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;'+btn_content);
            participant_id = btn.attr('id');
            btn.prop('disabled', true);

            $.ajax({
                url: '{{url("training/absent-participant")}}',
                type: 'post',
                data: {
                    _token: $('input[name="_token"]').val(),
                    participant_id: participant_id
                },
                dataType: 'json',
                success: function (response) {
                    if(response.responseCode == 1)
                    {
                        $('#phase_'+participant_id).html('');
                    }
                    else if(response.responseCode == 0)
                    {
                        btn.prop('disabled', false);
                        btn.html(btn_content);
                        alert(response.msg);
                        return false;
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);

                },
                beforeSend: function (xhr) {
                    console.log('before send');
                },
                complete: function () {
                    //completed
                }
            });
        });
    </script>
@endsection