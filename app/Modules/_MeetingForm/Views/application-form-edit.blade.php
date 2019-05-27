@extends('layouts.admin')
@section('content')
    <?php

    $accessMode = ACL::getAccsessRight('MeetingForm');
    if (!ACL::isAllowed($accessMode, $mode)) {
        die('You have no access right! Please contact with system admin if you have any query.');
    }
    $user_type = CommonFunction::getUserType();
    ?>
    @include('partials.modal')
    <style>
        input.error[type="radio"]{
            outline: 2px solid red;
        }
    </style>
    <section class="content" id="LoanLocator">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
                    {{--{!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}--}}

                    @if($viewMode == 'on')
                        @if(Request::segment(4)){{-- 4= meeting module --}}
                        <?php
                        $boardMeetingInfo = CommonFunction::getBoardMeetingInfo(Request::segment(3));
                        ?>
                        <div class="panel-body">
                            <div class="panel panel-info">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-4  col-md-offset-1 "
                                             style="border-bottom: 1px solid #c7bebe">
                                            <label style="font-size: 15px;color: #478fca;padding: 0px"
                                                   for="infrastructureReq" class="text-success col-md-6">Board Meeting
                                                Info
                                            </label>
                                            <br>
                                        </div>
                                        <div class="col-md-4 col-md-offset-1 " style="border-bottom: 1px solid #c7bebe">
                                            <label style="font-size: 15px;color: #478fca;">Agenda Info</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">

                                        <div class="col-md-4  col-md-offset-1">
                                            <table>
                                                <tr>
                                                    <td style="font-weight: bold;font-size: 14px; color: #5f5f5f;">Board
                                                        meeting no. :
                                                    </td>
                                                    <td style="font-size: 13px;color: #5f5f5f;">
                                                        &nbsp;&nbsp; {{$boardMeetingInfo['board_meeting_info']->meting_number}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold;font-size: 14px; color: #5f5f5f;">Board
                                                        Meeting Date:
                                                    </td>
                                                    <td style="font-size: 13px;color: #5f5f5f;">
                                                        {{date("d M Y h:i a", strtotime($boardMeetingInfo['board_meeting_info']->meting_date))}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold;font-size: 14px; color: #5f5f5f;">
                                                        Location:
                                                    </td>
                                                    <td style="font-size: 13px;color: #5f5f5f;">
                                                        &nbsp;&nbsp;{{$boardMeetingInfo['board_meeting_info']->location}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold;font-size: 14px; color: #5f5f5f;">
                                                        Status:
                                                    </td>
                                                    <td style="font-size: 13px;color: #5f5f5f;">
                                                        &nbsp;&nbsp;<button class="btn btn-xs btn-{{$boardMeetingInfo['board_meeting_info']->panel}}">{{$boardMeetingInfo['board_meeting_info']->status_name}}</button>
                                                    </td>
                                                </tr>
                                            </table>

                                        </div>
                                        <div class="col-md-4 col-md-offset-1">
                                            <table>
                                                <tr>
                                                    <td style="font-weight: bold; color: #5f5f5f;">Agenda Name: :</td>
                                                    <td style="font-size: 13px;color: #5f5f5f;">
                                                        &nbsp;&nbsp; {{$boardMeetingInfo['agenda_info']->name}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold; color: #5f5f5f;">Description:</td>
                                                    <td style="font-size: 13px;color: #5f5f5f;">
                                                        &nbsp;&nbsp; {{$boardMeetingInfo['agenda_info']->description}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold; color: #5f5f5f;">Process Type:</td>
                                                    <td style="font-size: 13px;color: #5f5f5f;">
                                                        &nbsp;&nbsp; {{$boardMeetingInfo['agenda_info']->process_name}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold; color: #5f5f5f;">Status:</td>
                                                    <td style="font-size: 13px;color: #5f5f5f;">
                                                        &nbsp;@if($boardMeetingInfo['agenda_info']->status_name == '')
                                                            <label class="btn btn-warning btn-xs">Pending</label>
                                                        @else
                                                            <label class="btn btn-{{$boardMeetingInfo['agenda_info']->panel}} btn-xs">{{$boardMeetingInfo['agenda_info']->status_name}}</label>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold; color: #5f5f5f;">Remarks:</td>
                                                    <td style="font-size: 13px;color: #5f5f5f;">
                                                        <span>{!! isset($boardMeetingInfo['agenda_info']->remarks)  !!}</span>
                                                    </td>
                                                </tr>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-info">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-9  col-md-offset-1 "
                                             style="border-bottom: 1px solid #c7bebe">
                                            <label
                                                    for="infrastructureReq" class="text-success col-md-6">
                                                <span style="font-size: 16px;color: #478fca;padding: 0px">Chairman Remarks:</span> {{isset($boardMeetingInfo['chairmanRemarks']->bm_remarks)?$boardMeetingInfo['chairmanRemarks']->bm_remarks:''}}
                                            </label>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="btn-group btn-breadcrumb steps">
                                            <?php
                                            $x = 0;
                                            ?>
                                            @foreach($statusName as $status)
                                                <?php
                                                if($status->id == $appInfo->status_id){
                                                    $class = 'warning';
                                                    $color = '#';
                                                    $disable = 'disable';
                                                }elseif($status->id > $appInfo->status_id){
                                                    $class = 'info';
                                                    $color = '#';
                                                    $disable = 'disaabled';
                                                }elseif($status->id ==''){

                                                }
                                                else{
//                                              $current_status = 'Shorfall';
                                                    $class = 'success';
                                                    $color = '#358e35';
                                                    $disable = 'disable';

                                                    ?>
                                            <?php
                                                }
                                                $x++;
                                                ?>
                                                <a href="#" class="btn btn-<?php echo $class;?>" style="background-color:{{$color}};">{{$status->status_name}}</a>

                                                {{--<a href="#" class="btn btn-danger">{{$status->status_name}}</a>--}}
                                                {{--<a href="#" class="btn btn-warning">{{$status->status_name}}</a>--}}
                                            @endforeach

                                        </div>
                                    </div>

                                </div>
                                <br>
                            @endif
                            @endif
                    <div class="panel panel-red" id="inputForm">
                        <div class="panel-heading">{{trans('messages.meeting_form')}}</div>
                        <div class="panel-body" style="margin:6px;">
                        @if($viewMode == 'on')
                        <section>
                        <div class="panel-body">
                            <div class="panel panel-primary">
                                <div class="panel-heading"></div>
                                <ol class="breadcrumb">
                                    <li><strong>Tracking no. : </strong>{{ $appInfo->tracking_no  }}</li>
                                    <li><strong> Date of Submission: </strong> {{ \App\Libraries\CommonFunction::formateDate($appInfo->date_of_submission)  }} </li>
                                    <li><strong>Current Status : </strong>
                                        @if(isset($appInfo) && $appInfo->status_id == -1) Draft
                                        @else {!! $statusArray[$appInfo->status_id] !!}
                                        @endif
                                    </li>
                                    <li>
                                        @if($appInfo->desk_id != 0) <strong>Current Desk :</strong>
                                        {{ \App\Libraries\CommonFunction::getDeskName($appInfo->desk_id)  }}
                                        @else
                                            <strong>Current Desk :</strong> Board Admin
                                        @endif
                                    </li>
                                    @if(isset($appInfo->status_id) && $appInfo->status_id == 8)
                                        <li>
                                            <strong>Discard Reason :</strong> {{ !empty($appInfo->process_desc)? $appInfo->process_desc : 'N/A' }}
                                        </li>
                                    @endif

                                    @if(isset($appInfo->status_id) && $appInfo->status_id == 18)
                                        <li>
                                            <strong>Challan Declined Reason :</strong> {{ !empty($appInfo->process_desc)? $appInfo->process_desc : 'N/A' }}
                                        </li>
                                    @endif

                                    <li>
                                        <?php if (isset($appInfo) && $appInfo->status_id == 25 && isset($appInfo->certificate) && $appInfo->certificate != '') { ?>
                                        <a href="{{ url($appInfo->certificate) }}" class="btn show-in-view btn-xs btn-success"
                                           title="Download Approval Letter" target="_blank"> <i class="fa  fa-file-pdf-o"></i> <b>Download Certificate</b>
                                        </a>
                                        <?php } ?>


                                    </li>
                                </ol>
                            </div>
                        </div>
                            </section>
                        @endif
                            {!! Form::open(array('url' => '/meeting-form/add','method' => 'post','id' => 'appClearenceForm','role'=>'form','enctype'=>'multipart/form-data')) !!}
                            <input type="hidden" name="app_id" value="{{ Encryption::encodeId($appInfo->id)}}">
                            <input type="hidden" name="selected_file" id="selected_file">
                            <input type="hidden" name="validateFieldName" id="validateFieldName">
                            <input type="hidden" name="isRequired" id="isRequired">

                            <div class="panel panel-primary">
                                <div class="panel-heading"><strong>{{trans('messages.required_info')}}</strong></div>
                                <div class="panel-body">

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-8  {{$errors->has('task_name') ? 'has-error': ''}}">
                                                {!! Form::label('task_name',''.trans("messages.task_name").' :',['class'=>'col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('task_name',$appInfo->task_name, ['maxlength'=>'255',
                                                    'class' => 'form-control input-sm bnEng required']) !!}
                                                    {!! $errors->first('task_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-8  {{$errors->has('comments') ? 'has-error': ''}}">
                                                {!! Form::label('comments',''.trans("messages.comments").' :',['class'=>'col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('comments',$appInfo->comments, ['maxlength'=>'255',
                                                    'class' => 'form-control input-sm bnEng']) !!}
                                                    {!! $errors->first('comments','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-8  {{$errors->has('task_description') ? 'has-error': ''}}">
                                                {!! Form::label('task_description',''.trans("messages.task_description").' :',['class'=>'col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::textarea('task_description',$appInfo->task_description, ['maxlength'=>'1000',
                                                    'class' => 'form-control input-sm bnEng ']) !!}
                                                    {!! $errors->first('task_description','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-8  {{$errors->has('remarks') ? 'has-error': ''}}">
                                                {!! Form::label('remarks',''.trans("messages.remarks").' :',['class'=>'col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('remarks',$appInfo->remarks, ['maxlength'=>'255',
                                                    'class' => 'form-control input-sm bnEng ']) !!}
                                                    {!! $errors->first('remarks','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div><!--/col-md-12-->
                                <!--/panel-body-->
                            </div> <!--/panel-->


                            <div style="margin:6px;">
                                <div class="row">

                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        @if($appInfo->status_id != 5)
                                        <button type="submit" class="btn btn-primary btn-md cancel" value="draft" name="actionBtn">Save as Draft</button>
                                        @endif
                                    </div>

                                    <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                                        <button type="submit" class="btn btn-primary btn-md" value="save" name="submitInsert">Submit
                                        </button>
                                    </div>
                                    <!-- /.form end -->
                                </div>


                            </div>
                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
                </div>
            </div>
    </section>


@endsection
@section('footer-script')

    <script type="text/javascript">
        $("#appClearenceForm").validate();
        $("#pr_division").change(function () {
            var divisionId = $(this).val();
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "<?php echo url(); ?>/users/get-district-by-division",
                data: {
                    divisionId: divisionId
                },
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            option += '<option value="' + id + '">' + value + '</option>';
                        });
                    }
                    $("#pre_distract").html(option);
                    $(self).next().hide();
                }
            });
        });
        // get thana list by district id for Office Address

        $("#pre_distract").change(function () {
            var self = $(this);
            var districtId = $(this).val();
            if (districtId !== '') {
                $(this).after('<span class="loading_data">Loading...</span>');
                $("#loaderImg").html("<img style='margin-top: -15px;' src='<?php echo url(); ?>/public/assets/images/ajax-loader.gif' alt='loading' />");
                $.ajax({
                    type: "GET",
                    url: "<?php echo url(); ?>/users/get-thana-by-district-id",
                    data: {
                        districtId: districtId
                    },
                    success: function (response) {
                        var option = '<option value="">Select One</option>';
                        if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
                                // for edit list , applicant thana
                                {{--if (id == '{{$users->thana}}'){--}}
                                {{--option += '<option value="'+ id + '" selected>' + value + '</option>';--}}
                                {{--}--}}
                                //                                    else {
                                option += '<option value="' + id + '">' + value + '</option>';
//                                    }
                            });
                        }
                        $("#pre_thana").html(option);
                        self.next().hide();
                    }
                });
            }
        });



        $("#per_division").change(function () {
            var divisionId = $(this).val();
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "<?php echo url(); ?>/users/get-district-by-division",
                data: {
                    divisionId: divisionId
                },
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            option += '<option value="' + id + '">' + value + '</option>';
                        });
                    }
                    $("#per_distract").html(option);
                    $(self).next().hide();
                }
            });
        });
        $("#per_distract").change(function () {
            var self = $(this);
            var districtId = $(this).val();
            if (districtId !== '') {
                $(this).after('<span class="loading_data">Loading...</span>');
                $("#loaderImg").html("<img style='margin-top: -15px;' src='<?php echo url(); ?>/public/assets/images/ajax-loader.gif' alt='loading' />");
                $.ajax({
                    type: "GET",
                    url: "<?php echo url(); ?>/users/get-thana-by-district-id",
                    data: {
                        districtId: districtId
                    },
                    success: function (response) {
                        var option = '<option value="">Select One</option>';
                        if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
                                option += '<option value="' + id + '">' + value + '</option>';
                            });
                        }
                        $("#per_thana").html(option);
                        self.next().hide();
                    }
                });
            }
        });



        $("#bank").change(function () {
            var bank_id = $(this).val();
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "<?php echo url(); ?>/loan-locator/get-branch-by-bank",
                data: {
                    bank_id: bank_id
                },
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            option += '<option value="' + id + '">' + value + '</option>';
                        });
                    }
                    $("#branch").html(option);
                    $(self).next().hide();
                }
            });

        });
        function uploadDocument(targets, id, vField, isRequired) {
            var inputFile = $("#" + id).val();
            if (inputFile == '') {
                $("#" + id).html('');
                document.getElementById("isRequired").value = '';
                document.getElementById("selected_file").value = '';
                document.getElementById("validateFieldName").value = '';
                document.getElementById(targets).innerHTML = '<input type="hidden" class="required" value="" id="' + vField + '" name="' + vField + '">';
                if ($('#label_' + id).length)
                    $('#label_' + id).remove();
                return false;
            }

            try {
                document.getElementById("isRequired").value = isRequired;
                document.getElementById("selected_file").value = id;
                document.getElementById("validateFieldName").value = vField;
                document.getElementById(targets).style.color = "red";
                var action = "{{url('/loan-locator/upload-document')}}";
                $("#" + targets).html('Uploading....');
                var file_data = $("#" + id).prop('files')[0];
                var form_data = new FormData();
                form_data.append('selected_file', id);
                form_data.append('isRequired', isRequired);
                form_data.append('validateFieldName', vField);
                form_data.append('_token', "{{ csrf_token() }}");
                form_data.append(id, file_data);
                $.ajax({
                    target: '#' + targets,
                    url: action,
                    dataType: 'text', // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    success: function (response) {
                        $('#' + targets).html(response);
                        var fileNameArr = inputFile.split("\\");
                        var l = fileNameArr.length;
                        if ($('#label_' + id).length)
                            $('#label_' + id).remove();
                        var doc_id = parseInt(id.substring(4));
                        var newInput = $('<label class="saved_file_' + doc_id + '" id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + ' <a href="javascript:void(0)" onclick="EmptyFile(' + doc_id + ')"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
//                        var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                        $("#" + id).after(newInput);
                        //check valid data
                        var validate_field = $('#' + vField).val();
                        if (validate_field == '') {
                            document.getElementById(id).value = '';
                        }
                    }
                });
            } catch (err) {
                document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
            }
        }

        <?php if ($viewMode == 'on') { ?>
        $(".MoreInfo").click(function () {
            $(this).closest("tr").next().show();

        });

        $('#inputForm select').each(function (index) {
            var text = $(this).find('option:selected').text();
            var id = $(this).attr("id");
            var val = $(this).val();
            $('#' + id + ' option:selected').replaceWith("<option value='" + val + "' selected>" + text + "</option>");
        });
        $("#inputForm :input[type=text]").each(function (index) {
            $(this).attr("value", $(this).val());
        });
        $("#inputForm textarea").each(function (index) {
            $(this).text($(this).val());
        });

        $("#inputForm select").css({
            "border": "none",
            "background": "#fff",
            "pointer-events": "none",
            "box-shadow": "none",
            "-webkit-appearance": "none",
            "-moz-appearance": "none",
            "appearance": "none"
        });

        $("#inputForm .actions").css({"display": "none"});
        $("#inputForm .draft").css({"display": "none"});
        $("#inputForm .title ").css({"display": "none"});
        //document.getElementById("previewDiv").innerHTML = document.getElementById("projectClearanceForm").innerHTML;

        $('#inputForm #showPreview').remove();
        $('#inputForm #save_btn').remove();
        $('#inputForm #save_draft_btn').remove();
        $('#inputForm .stepHeader, #inputForm .calender-icon,#inputForm .pss-error,#inputForm .hiddenDiv, #inputForm .input-group-addon').remove();
        $('#inputForm .required-star').removeClass('required-star');
        $('#inputForm input[type=hidden], #inputForm input[type=file]').remove();
        $('#inputForm .panel-orange > .panel-heading').css('margin-bottom', '10px');
        $('#invalidInst').html('');

        $('#inputForm').find('input:not(:checked),textarea').each(function () {
            if (this.value != '') {
                var displayOp = ''; //display:block
            } else {
                var displayOp = 'display:none';
            }

            if ($(this).hasClass("onlyNumber") && !$(this).hasClass("nocomma")) {
                var thisVal = commaSeparateNumber(this.value);
                $(this).replaceWith("<span class='onlyNumber " + this.className +
                    "' style='background-color:#ddd !important;border-radius:3px;padding:6px; height:auto; margin-bottom:2px;"
                    + displayOp + "'>" + thisVal + "</span>");
            } else {
                $(this).replaceWith("<span class='" + this.className + "' style='background-color:#ddd;padding:6px; height:auto; margin-bottom:2px;"
                    + displayOp + "'>" + this.value + "</span>");
            }
        });

        $('#inputForm').find('textarea').each(function () {
            var displayOp = '';
            if (this.value != '') {
                displayOp = ''; //display:block
            } else {
                displayOp = 'display:none';
            }
            $(this).replaceWith("<span class='" + this.className + "' style='background-color:#ddd;height:auto;padding:6px;margin-bottom:2px;"
                + displayOp + "'>" + this.value + "</span>");
        });


        $('#inputForm .btn').not('.show-in-view,.documentUrl').each(function () {
            $(this).replaceWith("");
        });

        $('#inputForm').find('input[type=radio]').each(function () {
            jQuery(this).attr('disabled', 'disabled');
        });

        $("#inputForm select").replaceWith(function () {
            var selectedText = $(this).find('option:selected').text().trim();
            var displayOp = '';
            if (selectedText != '' && selectedText != 'Select One') {
                displayOp = ''; //display:block
            } else {
                displayOp = 'display:none';
            }

            return "<span class='" + this.className + "' style='background-color:#ddd;height:auto;padding:6px;margin-bottom:2px;"
                + displayOp + "'>" + selectedText + "</span>";
        });

        $("#inputForm select").replaceWith(function () {
            var selectedText = $(this).find('option:selected').text();
            return "<span style='background-color:#ddd;width:68%; height:auto; margin-bottom:2px;padding:6px;display:block;'>"
                + selectedText + "</span>";
        });

        function commaSeparateNumber(val) {
            while (/(\d+)(\d{3})/.test(val.toString())) {
                val = val.toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2');
            }
            return val;
        }

        <?php } ?> /* viewMode is on */
    </script>
@endsection