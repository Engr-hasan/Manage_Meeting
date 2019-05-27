@extends('layouts.admin')

@section('page_heading')
    Application
@endsection
@section('content')
    <section class="content" xmlns="http://www.w3.org/1999/html">
        <div class="col-md-12">
            @if(Session::has('success'))
                <div class="alert alert-success">
                    {!!Session::get('success') !!}
                </div>
            @endif
            @if(Session::has('error'))
                <div class="alert alert-danger">
                    {!! Session::get('error') !!}
                </div>
            @endif
        </div>
        @if(in_array(Auth::user()->desk_id,array(1,2,3,4,5,6)))
            {!! Form::open(['url' => '/application/update-batch', 'method' => 'patch', 'class' => 'form apps_from', 'id' => 'batch_from', 'role' => 'form','enctype' =>'multipart/form-data', 'files'=>true]) !!}
        @endif
        <div class="col-md-12">
            <div class="with-border">
                @if(in_array(Auth::user()->desk_id,array(1,2,3,4,5,6)))
                @include('Apps::batch-process')
                @endif
            </div>
        </div>
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-6">
                            <b><i class="fa fa-list"></i> Application List</b>
                        </div>
                        <div class="col-md-6 text-right">
                            @if(in_array(Auth::user()->user_type, array('5x502', '6x601'))) <!-- applicant(Agency User, Section/Branch User) -->
                            <a href="{{URL::to('/application/create-form')}}" class="">
                                {!! Form::button('<i class="fa fa-plus"></i> New Application', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                            </a>
                            @endif
                            <a class="btn btn-default addAdvanceSearch"> Advance Search <i class="fa fa-arrow-down" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="panel panel-primary" id="innerAdvanceSearch" style="display:none;border-radius: 0 0 4px 4px;">
                        <div class="panel-body">
                            <div class="form-group" style="clear: both">
                                <div class="row">
                                    <div class="col-md-6 {{$errors->has('tracking_number') ? 'has-error': ''}}">
                                        {!! Form::label('tracking_number','Tracking Number', ['class'=>'col-md-5 text-left']) !!}
                                        <div class="col-md-7">
                                            <input class="form-control" placeholder="Tracking Number" name="tracking_number" type="text" id="tracking_number"
                                                   value="{!! Session::has('tracking_no') ? Session::get("tracking_no"): ''!!}" maxlength="100"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('applicant_name','Name ',['class'=>'col-md-5 text-left']) !!}
                                        <div class="col-md-7">
                                            <input class="form-control" placeholder="Name" name="applicant_name" type="text" id="applicant_name"
                                                   maxlength="100"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="clear: both">
                                <div class="row">
                                    <div class="col-md-6 {{$errors->has('nationality') ? 'has-error': ''}}">
                                        {!! Form::label('nationality','Nationality:',['class'=>'col-md-5 text-left']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('nationality', $nationality, '', $attributes = array('class'=>'form-control',
                                            'placeholder' => 'Select nationality', 'id'=>"nationality")) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('fax','Passport Number: ',['class'=>'text-left col-md-5']) !!}
                                        <div class="col-md-7">
                                            <input class="form-control" placeholder="Passport Number" name="passport_number" type="text" id="passport_number"
                                                   maxlength="100"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="clear: both">
                                <div class="row">
                                    <div class="col-md-6 {{$errors->has('modal_status_id') ? 'has-error': ''}}">
                                        {!! Form::label('modal_status_id','Status:',['class'=>'text-left required-star col-md-5']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('modal_status_id', $status, '', array('class'=>'form-control',
                                            'placeholder' => 'Select Status', 'id'=>"modal_status_id")) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="clear: both">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-2">
                                            <button  class="btn btn-default closeSearch" value="Done" type="button">Hide</button>
                                        </div>
                                        <div class="col-md-10">

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-10"></div>
                                        <div class="col-md-2">
                                            <button id="search_app" class="btn btn-primary" value="Done" type="button">search</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="table-responsive">
                                    <table id="application_list" class="table table-striped table-bordered dt-responsive nowrap resultTable display" cellspacing="0" width="100%">
                                        <thead class="alert alert-info">
                                        <tr>
                                            <th>
                                                @if (in_array(Auth::user()->desk_id, array(1,2,3,4,5,6)))
                                                    {!! Form::checkbox('chk_id','chk_id','',['class'=>'selectall', 'id'=>'chk_id']) !!}
                                                @endif
                                            </th>
                                            <th>Serial</th>
                                            <th>Tracking ID</th>
                                            <th>Application Title</th>
                                            <th>Applicant Name</th>
                                            <th>Serving Desk</th>
                                            <th>Status</th>
                                            <th>Modified</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $row_sl = 0; ?>
                                        @foreach($appsInfo as $app)
                                            <?php $row_sl++ ?>
                                            <tr>
                                                <td>
                                                    @if ($app->desk_id == Auth::user()->desk_id && $app->desk_id > 0 )
                                                    {!! Form::checkbox('application[]',$app->record_id, '',['class'=>'appCheckBox', 'onChange' => 'changeStatus(this.checked)']) !!}
                                                    {!! Form::hidden('hdn_batch[]',$app->status_id, ['class'=>'hdnStatus','id'=>"".$app->record_id."_status"]) !!}
                                                    @endif
                                                </td>
                                                <td>{!! $row_sl !!}</td>
                                                <td>{!! $app->track_no !!}</td>
                                                <td>{!! $app->application_title !!}<br/></td>
                                                <td>{!! $app->applicant_name !!}</td>
                                                <td>@if($app->desk_id == 0) Applicant @else {!! $deskList[$app->desk_id] !!} @endif</td>
                                                <td><span style="background-color: <?php echo $statusList[$app->status_id . 'color']; ?>; font-weight: bold;" class="label btn-sm">
                                                {!! $statusList[$app->status_id]!!}
                                            </span></td>
                                                <td>{!! App\Libraries\CommonFunction::updatedOn($app->updated_at) !!}</td>
                                                <td>
                                                    <a href="{{url('application/view/'.App\Libraries\Encryption::encodeId($app->record_id))}}" class="btn btn-xs btn-primary open" >
                                                        <i class="fa fa-folder-open-o"></i> View</a>
                                                    @if(($app->status_id == -1 || $app->status_id == 5) && $app->initiated_by == Auth::user()->id )
                                                        <a href="{{url('application/edit-form/'.Encryption::encodeId($row->record_id))}}" class="btn btn-xs btn-primary open" ><i class="fa fa-folder-open-o"></i> Edit</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div><!-- /.table-responsive -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('footer-script')
    @include('partials.datatable-scripts')
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <script language="javascript">

        var numberOfCheckedBox = 0;
        var curr_app_id = '';
        function setCheckBox()
        {
            numberOfCheckedBox = 0;
            var flag = 1;
            var selectedWO = $("input[type=checkbox]").not(".selectall");
            selectedWO.each(function() {
                if (this.checked)
                {
                    numberOfCheckedBox++;
                }
                else
                {
                    flag = 0;
                }
            });
            if (flag == 1)
            {
                $("#chk_id").checked = true;
            }
            else {
                $("#chk_id").checked = false;
            }
            if (numberOfCheckedBox >= 1){
                $('.applicable_status').trigger('click');
            }

        }

        function changeStatus(check)
        {
            $('#status_id').html('<option selected="selected" value="">Select Below</option>');
            setCheckBox();
        }

        $(document).ready(function() {


            $('#status_id').html('<option selected="selected" value="">Select Below</option>');
            $('#_list').DataTable({
                "paging": true,
                "lengthChange": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "iDisplayLength": 50
            });
            //            $("#assign_form").validate({
            //                errorPlacement: function() {
            //                    return false;
            //                }
            //            });
            $("#apps_from").validate({
                errorPlacement: function() {
                    return false;
                }
            });
            $("#batch_from").validate({
                errorPlacement: function() {
                    return false;
                }
            });
            $(".send").click(function() {
                var all_app_id = [];
                $('.appCheckBox').each(function () {
                    if ($(this).is(':checked')){
                        all_app_id.push($(this).val());
                    }
                });
                if (all_app_id == '')
                {
                    alert("Please select any application");
                    //                         $('#status_id').attr('disabled',true);
                    return false;
                }
            });
            $(".addAdvanceSearch").click(function() {
                $('#innerAdvanceSearch').slideDown();
                $(this).find('i').removeClass("fa fa-arrow-down");
                $(this).find('i').addClass("fa fa-arrow-up");
                $(".addAdvanceSearch").css("background-color", "#1abc9c");
                $(".addAdvanceSearch").css("color", "white");
            });
            $(".closeSearch").click(function() {
                $('#innerAdvanceSearch').slideUp();
                $('.addAdvanceSearch').find('i').removeClass("fa-arrow-up fa");
                $('.addAdvanceSearch').find('i').addClass("fa fa-arrow-down");
                $(".addAdvanceSearch").css("background-color", "");
                $(".addAdvanceSearch").css("color", "");
            });
            $(".process").click(function() {
                if (numberOfCheckedBox == 0)
                {
                    alert('Select Application');
                    return false;
                }

            });
            var base_checkbox = '.selectall';
            $(base_checkbox).click(function() {
                if (this.checked) {
                    $('.appCheckBox:checkbox').each(function() {
                        this.checked = true;
                        $('#status_id').html('<option selected="selected" value="">Select Below</option>');
                    });
                } else {
                    $('.appCheckBox:checkbox').each(function() {
                        this.checked = false;
                        //                            $('#status_id').attr('disabled',false);
                        $('#status_id').html('<option selected="selected" value="">Select Below</option>');
                    });
                }
                $('#status_id').html('<option selected="selected" value="">Select Below</option>');
                setCheckBox();
            });
            $('.appCheckBox:checkbox').not(base_checkbox).click(function() {
                $(".selectall").prop("checked", false);
            });
            var break_for_pending_verification = 0;
            $(".applicable_status").click(function() {

                $("#status_id").trigger("click");
                var all_app_id = [];
                break_for_pending_verification = 0;
                $('.appCheckBox').each(function () {
                    if ($(this).is(':checked')){
                        all_app_id.push($(this).val());
                    }
                });
                if (all_app_id == '')
                {
                    alert("Please select any application");
                    //                         $('#status_id').attr('disabled',true);
                    $('#status_id').html('<option selected="selected" value="">Select Below</option>');
                    return false;
                } else{
                    $('#status_id').attr('disabled', false);
                    $('#status_id').html('<option selected="selected" value="">Select Below</option>');
                    curr_app_id = all_app_id[0];
                    var curr_status_id = $("#" + curr_app_id + "_status").val();
                    $.ajaxSetup({async: false});
                    $.each(all_app_id, function(j, i) {
                        if (break_for_pending_verification == 1)
                        {
                            return false;
                        }

                        var tmp_curr_status = $("#" + i + "_status").val();
                        if (curr_status_id != tmp_curr_status)
                        {
                            alert('Please select application of same status...');
                            $('#status_id').attr('disabled', false);
                            $('#status_id').html('<option selected="selected" value="">Select Below</option>');
                            return false;
                        }
                        else
                        {
                            var _token = $('input[name="_token"]').val();
                            var delegate = '{{ @$delegated_desk }}';
                            var state = false;
                            $.post('/application/ajax/load-status-list', {curr_status_id: curr_status_id, curr_app_id: curr_app_id, delegate: delegate, _token: _token}, function(response) {

                                if (response.responseCode == 1) {

                                    var option = '';
                                    option += '<option selected="selected" value="">Select Below</option>';
                                    $.each(response.data, function(id, value) {
                                        option += '<option value="' + value.status_id + '">' + value.status_name + '</option>';
                                    });
                                    {{--state = !state; --}}
                                    {{--$("#status_id").prop("", state ? $("option").length : 1); --}}
                                    $("#status_id").html(option);
                                    $("#status_id").trigger("change");
                                    $("#status_id").focus();
                                } else if (response.responseCode == 5){
                                    alert('Without verification, application can not be processed');
                                    break_for_pending_verification = 1;
                                    option = '<option selected="selected" value="">Select Below</option>';
                                    $("#status_id").html(option);
                                    $("#status_id").trigger("change");
                                    return false;
                                } else {
                                    $('#status_id').html('Please wait');
                                }
                            });
                        }
                    });
                    $.ajaxSetup({async: true});
                }
            });
            $(document).on('change', '.status_id', function() {

                var object = $(".status_id");
                var obj = $(object).parent().parent().parent();
                var id = $(object).val();

                var _token = $('input[name="_token"]').val();
                var status_from = $('#status_from').val();
                $('#sendToDeskOfficer').css('display', 'block');
                if (id == 0) {
                    obj.find('.param_id').html('<option value="">Select Below</option>');
                } else {
                    //                                        	alert(status_from);
                    //                                if(id == 5 || id == 8 || id == 9 || id == 14 || id == 2){


                    $.post('/application/ajax/process', {id: id, curr_app_id: curr_app_id, status_from: status_from, _token: _token}, function(response) {
                        console.log(response);
                        if (response.responseCode == 1) {

                            var option = '';
                            option += '<option selected="selected" value="">Select Below</option>';
                            var countDesk = 0;
                            $.each(response.data, function(id, value) {
                                countDesk++;
                                option += '<option value="' + id + '">' + value + '</option>';
                            });
                            obj.find('#desk_id').html(option);
                            $('#desk_id').attr("disabled", false);
                            $('#remarks').attr("disabled", false);
                            if (countDesk == 0)
                            {
                                $('.dd_id').removeClass('required');
                                $('#sendToDeskOfficer').css('display', 'none');
                            }
                            else{
                                $('.dd_id').addClass('required');
                            }
                            if (response.status_to == 5 || response.status_to == 8 || response.status_to == 16
                                    || response.status_to == 17 || response.status_to == 19 || response.status_to == 24
                                    || response.status_to == 10 || response.status_to == 22){

                                $('#remarks').addClass('required');
                                $('#remarks').attr("disabled", false);
                            }
                            else{
                                $('#remarks').removeClass('required');
                            }


                            if (response.file_attach == 1)
                            {
                                $('#sendToFile').css('display', 'block');
                            }
                            else{
                                $('#sendToFile').css('display', 'none');
                            }


                        }
                    });
                    //       }
                }


            });
        });
        function resetElements(){
            $('#status_id').html('<option selected="selected" value="">Select Below</option>');
        }

                <?php if (isset($search_status_id) && $search_status_id > 0) { ?>
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: base_url + '/dashboard/search-result',
            type: 'post',
            data: {
                _token: _token,
                status_id:<?php echo $search_status_id; ?>,
            },
            dataType: 'json',
            success: function(response) {
                // success
                if (response.responseCode == 1) {
                    $('table.resultTable tbody').html(response.data);
                } else {
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            },
            beforeSend: function(xhr) {
                console.log('before send');
            },
            complete: function() {
                //completed
            }
        });
        <?php } ?>

        $("#search_app").on('click', function() {
            $('.selectall').addClass("hidden");
            var _token = $('input[name="_token"]').val();
            var tracking_number = $('#tracking_number').val();
            var passport_number = $('#passport_number').val();
            var applicant_name = $('#applicant_name').val();
            var nationality = $('#nationality').val();
            var status_id = $('#modal_status_id').val();
            var selected = false;

            // to restrict at lest one option select
            if(tracking_number == '' && status_id == '')
            {
                alert('At least you have to enter Tracking no. or select status');
                return;
            }

            //            if (tracking_number.trim() == '') {
            //                alert("Please enter voucher name");
            //            $('#tracking_number').addClass('error');
            //            return false;
            //    } else {
            //    $('#tracking_number').removeClass('error');
            //    }

            $.ajax({
                url: base_url + '/application/search-result',
                type: 'post',
                data: {
                    _token: _token,
                    tracking_number: tracking_number,
                    passport_number: passport_number,
                    applicant_name: applicant_name,
                    nationality: nationality,
                    status_id:status_id,
                },
                dataType: 'json',
                success: function(response) {
                    if (response.responseCode == 1) {
                        $('table.resultTable tbody').html(response.data);
                        $('#modal-close').trigger('click');
                    } else {
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                beforeSend: function(xhr) {
                    console.log('before send');
                },
                complete: function() {
                    //completed
                }
            });
        });

    </script>
@endsection