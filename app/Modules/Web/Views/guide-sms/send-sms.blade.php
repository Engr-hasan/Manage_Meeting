<style>.err_msg_hide {
        display: none;
    }

    .col-md-12 {
        padding: 3px !important;
    }</style>
@extends('layouts.front')
<link rel="stylesheet" href="{{ asset("assets/scripts/datatable/responsive.bootstrap.min.css") }}"/>
@section('content')

    <h4 class="text-center pull-left"><b>প্রেরিত ক্ষুদে বার্তা</b></h4>
    <a href="#sms_section" class="pull-right btn btn-primary btn-sm">নতুন+</a>
    <table class="table table-striped table-bordered dt-responsive nowrap" id="list">
        <thead>
        <tr>
            <th>Time</th>
            <th>Name</th>
            <th>Mobile No.</th>
            <th>SMS</th>
            <th>Rate</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        @foreach($previous_sms as $sms)
            <tr>
                <td>
                    <?php
                    $date = DateTime::createFromFormat('Y-m-d H:i:s', $sms->submitted_at);
                    echo $date->format('m/d H:i');
                    ?>
                </td>
                <td>{{$sms->full_name_bangla}}</td>
                <td>{{$sms->destination}}</td>
                <td>{{$sms->body}}</td>
                <td>{{($sms->rate==0)?'0':$sms->rate}} TK</td>
                <td>
                @if($sms->status==0)
                    <label class="label label-warning">Pending</label>
                @elseif($sms->status==1)
                        <label class="label label-success">Sent</label>
                @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>



    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}
    <div class="alert alert-danger alert-dismissible err_msg_hide">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <span id="err_msg"></span>
    </div>
    <div id="sms_section" style="background-color: #E0E0E0;">
        <h4 class="text-center" style="background-color: #31b0d5;color:white;padding:5px 0;"><b>নতুন জরুরী ক্ষুদে বার্তা প্রেরণ</b></h4>
        <h6><b>&nbsp;আপনার এস.এম.এস ব্যালেন্স: {{$sms_balance->balance}} TK</b></h6>
        <div class="form-group col-md-12 {{$errors->has('no_type') ? 'has-error' : ''}}">
            <div class="col-md-6">
                <label class="font-normal">{!! Form::radio('no_type',  'bd_no', null, ['class' => 'no_type required']) !!}
                    বাংলাদেশী নাম্বার </label>&nbsp;&nbsp;
                <label class="font-normal">{!! Form::radio('no_type', 'ksa_no', null, ['class' => 'no_type required']) !!}
                    সৌদি নাম্বার </label>
                {!! $errors->first('no_type','<span class="help-block">:message</span>') !!}
            </div>
        </div>
        <div class="form-group col-md-12 {{$errors->has('gender') ? 'has-error' : ''}}">
            <div class="col-md-6">
                <label class="font-normal">{!! Form::radio('gender',  'male', null, ['class' => 'gender required']) !!}
                    পুরুষ </label>&nbsp;&nbsp;
                <label class="font-normal">{!! Form::radio('gender',  'female', null, ['class' => 'gender required']) !!}
                    মহিলা </label>&nbsp;&nbsp;
                {{--                            <label class="font-normal">{!! Form::radio('gender', 'all', null, ['class' => 'gender required']) !!} সকল </label>--}}
                {!! $errors->first('gender','<span class="help-block">:message</span>') !!}
            </div>
        </div>
        <div class="form-group col-md-12 {{$errors->has('message') ? 'has-error' : ''}}">
            {!! Form::label('message','Message / বার্তা : ',['class'=>'col-md-12']) !!}
            <div class="col-md-12">
                {!! Form::textarea('message', '', ['class'=>'form-control engOnly', 'id' => 'message', 'cols' => '3', 'rows' => '3']) !!}
                {!! $errors->first('message','<span class="help-block">:message</span>') !!}
            </div>
        </div>
        <div class="form-group col-md-12">
            <div class="col-md-12">
                <button type="button" class="btn btn-sm btn-success pull-right sms_next_step">
                    পরবর্তী ধাপ <i class="fa fa-chevron-circle-right"></i>
                </button>
            </div>
        </div>
    </div>
@endsection

@section('footer-script')
    @include('partials.datatable-scripts')
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <script>
        $(document).ready(function () {

            $(document).on('click', '.sms_next_step', function (e) {

                btn = $(this);
                btn_content = btn.html();
                btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;' + btn_content);
                var no_type = $('.no_type:checked').val();
                var gender = $('.gender:checked').val();
                var sms = $.trim($("#message").val());
                var guide_tracking_no = '{{ Request::segment(4) }}';

                // Validation for bangla text
                if (!(/^[a-zA-Z \-\.,()0-9\/#!@\$\%\^\&_\+\=\*\?\>\<\{\}\[\]\;\:\'\"\`\~\×\|\\]+$/.test(sms))) {
                    $('#err_msg').html('Please enter English message only.');
                    $('.err_msg_hide').show();
                    btn.html(btn_content);
                    return false;
                } else {
                    btn.html(btn_content);
                    $('.err_msg_hide').hide();
                }

                // Including signature
                var signature = ' -sent by {{@$guideInfo->full_name_english}} using HAJ Guide App';
                sms += ' -sent by {{@$guideInfo->full_name_english}} using HAJ Guide App';
                if (sms.length > 155) {
                    $('#err_msg').html('Message content should not exceed ' + (155 - signature.length) + " Characters.");
                    $('.err_msg_hide').show();
                    btn.html(btn_content);
                    return false;
                } else {
                    btn.html(btn_content);
                    $('.err_msg_hide').hide();
                }

                var is_checked = true;
                $('input').each(function () {
                    is_checked = is_checked && $('input:radio[name=no_type]').is(':checked') && $('input:radio[name=gender]').is(':checked');
                });
                if (!is_checked) {
                    $('#err_msg').html('Please select the required information.');
                    $('.err_msg_hide').show();
                    btn.html(btn_content);
                    return false;
                }
                else {
                    btn.html(btn_content);
                    $('.err_msg_hide').hide();
                }

                $.ajax({
                    url: base_url + "/web/guide/send-sms-preview",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: $('input[name="_token"]').val(),
                        guide_tracking_no: guide_tracking_no,
                        no_type: no_type,
                        gender: gender,
                        sms: sms
                    },
                    success: function (response) {
                        btn.html(btn_content);
                        if (response.responseCode == 1) {
                            $('.alert-danger').hide();
                            $('.err_msg_hide').hide();
                            $("#sms_section").html(response.public_html);
                            $("#sms_section").load();
                        }
                        else {
                            btn.prop('disabled', false);
                            $('.alert-danger').hide();
                            $('#err_msg').html(response.msg);
                            $('.err_msg_hide').show();
                            return false;
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);

                    },
                    beforeSend: function (xhr) {
                        console.log('before send'.xhr);
                    },
                    complete: function () {
                        //completed
                    }
                });
            });

            // Send SMS
            $(document).on('click', '.send_sms', function (e) {

                btn = $(this);
                btn_content = btn.html();
                btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;' + btn_content);


                $.ajax({
                    url: base_url + "/web/guide/sms-store",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: $('input[name="_token"]').val(),
                        sender_tracking_no: $('input[name="sender_tracking_no"]').val(),
                        sms_content: $('input[name="sms_content"]').val(),
                        nmbr_type: $('input[name="nmbr_type"]').val(),
                        receiver_list: $('input[type=checkbox]:checked').map(function (_, el) {
                            return $(el).val();
                        }).get()

                    },
                    success: function (response) {
                        btn.html(btn_content);
                        if (response.responseCode == 1) {
                            $('.alert-danger').hide();
                            $('#sms_section').html('');
                            $('.alert-danger').css({
                                color: '#3c763d',
                                background: '#dff0d8',
                                border: '#d6e9c6'
                            });
                            $('.err_msg_hide').hide();
                            $('#err_msg').html(response.msg);
                            $('.err_msg_hide').show();
                            btn.prop('disabled', false);
                        }
                        else {
                            btn.prop('disabled', false);
                            $('.alert-danger').hide();
                            $('#err_msg').html(response.msg);
                            $('.err_msg_hide').show();
                            return false;
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);

                    },
                    beforeSend: function (xhr) {
                        console.log('before send'.xhr);
                    },
                    complete: function () {
                        //completed
                    }
                });
            });

            // Datatable
            $('#list').dataTable({
                "lengthChange": false,
                'displayLength': 15,
                "dom": '<"top">t<"bottom"ifp><"clear">',
                "aaSorting": [[0, 'desc']],
                "searching":false
            });
        });
    </script>
@endsection