<?php
$moduleName = Request::segment(1);
$proecss_type_id = Request::segment(3);
?>
{!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
{!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}
{!! Form::open(array('url' => 'process-path/batch-process-update', 'method' => 'post', 'id' => 'batch-process-form','files'=>true)) !!}
<div class="alert alert-info">

    <div id="FormDiv"></div>

    @if (isset($appInfo->ref_id))
        {!! Form::hidden('application_ids[0]', Encryption::encodeId($appInfo->ref_id),['class' => 'form-control input-sm required', 'id'=>'application_id']) !!}
    @endif

    {{--hidden data for data validation, update process--}}
    {!! Form::hidden('status_from',Encryption::encodeId($appInfo->status_id)) !!}
    {!! Form::hidden('process_list_id', Encryption::encodeId($appInfo->process_list_id), ['id' => 'process_list_id']) !!}
    {!! Form::hidden('data_verification', Encryption::encode(\App\Libraries\UtilFunction::processVerifyData($verificationData)), ['id' => 'data_verification']) !!}
    {!! Form::hidden('is_remarks_required','',['class' => 'form-control input-sm ', 'id'=>'is_remarks_required']) !!}
    {!! Form::hidden('is_file_required','',['class' => 'form-control input-sm ', 'id'=>'is_file_required']) !!}

    <div class="loading" style="display: none"><h2><i class="fa fa-spinner fa-spin"></i> &nbsp;</h2></div>
    <span class="col-md-3 {{$errors->has('status_id') ? 'has-error' : ''}}">
        {!! Form::label('status_id','Apply Status ') !!}
        {!! Form::select('status_id',[], null, ['class' => 'form-control required applyStausId', 'id' => 'application_status']) !!}
        {!! $errors->first('status_id','<span class="help-block">:message</span>') !!}
    </span>

    <span id="sendToDeskOfficer">
        <span class="col-md-3 {{$errors->has('desk_id') ? 'has-error' : ''}}">
            {!! Form::label('desk_id','Send to Desk') !!}
            {!! Form::select('desk_id', [''=>'Select Below'], '', ['class' => 'form-control dd_id required', 'id' => 'desk_status']) !!}
            {!! $errors->first('desk_id','<span class="help-block">:message</span>') !!}
        </span>
         <span class="col-md-2 {{$errors->has('priority') ? 'has-error' : ''}}">
             {!! Form::label('priority','Priority') !!}
             {!! Form::select('priority', [''=>'Select Below'], '', ['class' => 'form-control required', 'id' => 'priority']) !!}
             {!! $errors->first('priority','<span class="help-block">:message</span>') !!}
        </span>
    </span>


    <span class="col-md-3 {{$errors->has('remarks') ? 'has-error' : ''}}">
		{!! Form::label('remarks','Remarks') !!}
        {!! Form::textarea('remarks',null,['class'=>'form-control','id'=>'remarks', 'placeholder'=>'Enter Remarks','maxlength' => 254, 'rows' => 1, 'cols' => 50]) !!}
        <small><b style="font-size:11px;color:#004c99;">(Maximum length 254)</b></small><br/>
        {!! $errors->first('remarks','<span class="help-block">:message</span>') !!}
    </span>


    <span class="col-md-1">
		<label for="" style="width: 100%;height: 15px;"></label>
        {!! Form::button('<i class="fa fa-save"></i> Process', array('type' => 'submit', 'value'=> 'Submit', 'class' => 'btn btn-primary send')) !!}
    </span>
    <span id="sendToFile" style="clear: both;display: block;">
        <span class="col-md-3 {{$errors->has('desk_id') ? 'has-error' : ''}}">
            {!! Form::label('attach_file','Attach file') !!}
            {!! Form::file('attach_file[]', ['id'=>'','multiple'=>true]) !!}
            {!! $errors->first('attach_file','<span class="help-block">:message</span>') !!}
            <span class="text-danger" style="font-size: 9px; font-weight: bold">
                [File Format: *.pdf | File size(75-125)KB]
            </span>
        </span>
        <span class="col-md-3 hidden {{$errors->has('desk_id') ? 'has-error' : ''}}" id="pin_number">
             {!! Form::label('Enter Pin Number','') !!}
          <input class="form-control input-sm col-sm "type="text" name="pin_number">
             <span class="text-danger" style="font-size: 10px; font-weight: bold">
                Please check your email or phone number
            </span>
        </span>


        <?php
        $current_user_desk_ids = CommonFunction::getDeskId();

        if(!in_array($appInfo->desk_id, explode(",", $current_user_desk_ids)))
        {
        $DelegateUserInfo = CommonFunction::DelegateUserInfo($appInfo->desk_id);
        //        dd($DelegateUserInfo);
        ?>
        {{--<span class="col-md-6 col-sm-offset-3">--}}
            {{--<div class="form-group has-feedback">--}}
                {{--{!! Form::hidden('on_behalf_user_id',Encryption::encodeId($DelegateUserInfo->id),['maxlength'=>'500', 'class' => 'form-control input-sm']) !!}--}}
                {{--<label class="col-lg-4 text-left"></label>--}}
                {{--<div class="col-lg-8">--}}
                    {{--<fieldset class="scheduler-border">--}}
                        {{--<legend class="scheduler-border">On-behalf of</legend>--}}
                        {{--<div class="control-group">--}}
                            {{--<span>Name: {{$DelegateUserInfo->user_full_name}}</span><br>--}}
                            {{--<span>Designation: {{$DelegateUserInfo->designation}}</span><br>--}}
                            {{--<span>User Image: <img style="width: 100px;"  src="{{ $userPic = url() . '/users/upload/' . $DelegateUserInfo->user_pic}}" class="profile-user-img img-responsive"  alt="Profile Picture" id="uploaded_pic"  width="150" ></span>--}}
                        {{--</div>--}}
                    {{--</fieldset>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</span>--}}
        <?php
        }
        ?>
    </span>
    <div class="clearfix"></div>
    <br/><br/><br/>
</div>
{!! Form::close() !!}


<script src="{{ asset("assets/scripts/jquery.min.js") }}" src="" type="text/javascript"></script>
<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
<script>
    $(document).ready(function () {

        /**
         * Batch Form Validate
         * @type {jQuery}
         */
        $("#batch-process-form").validate({
            errorPlacement: function () {
                return false;
            }
        });

        /**
         * show remarks div in application-history
         * @type {jQuery}
         */
        $(".MoreInfo").click(function () {
            $(this).closest("tr").next().show();

        });

        /**
         * load Process status(Send to desk) on select apply status
         * @type {jQuery}
         */
        $("#application_status").change(function () {

            var self = $(this);
            var statusId = $('#application_status').val();
            if (statusId !== '') {
                $(this).after('<span class="loading_data">Loading...</span>');
                $.ajax({
                    type: "POST",
                    url: "{{ url('process-path/get-desk-by-status') }}",
                    data: {
                        _token: $('input[name="_token"]').val(),
                        process_list_id: $('input[name="process_list_id"]').val(),
                        status_from: $('input[name="status_from"]').val(),
                        statusId: statusId
                    },
                    success: function (response) {

                        var option = '<option value="">Select One</option>';

                        var countDesk = 0;

                        if (response.responseCode == 1) {
                            if(response.pin_number==1) {
                                $('#pin_number').removeClass('hidden');
                                $('#pin_number').children('input').addClass('required');
                                $('#pin_number').children('input').attr('disabled',false);
                            }else{
                                $('#pin_number').addClass('hidden');
                                $('#pin_number').children('input').removeClass('required');
                                $('#pin_number').children('input').attr('disabled',true);

                            }
                            $('#FormDiv').html(response.html);

                            $.each(response.data, function (id, value) {
                                countDesk++;
                                option += '<option value="' + id + '">' + value + '</option>';
                            });
                            // Setup required field about remarks field
                            if (response.remarks == 1) {
                                $("#remarks").addClass('required');
                                $('#is_remarks_required').val(response.remarks);
                            } else {
                                $("#remarks").removeClass('required');
                            }


                            // Setup required field about remarks field
                            if (response.file_attachment == 1) {
                                $("#attach_file").addClass('required');
                                $('#is_file_required').val(response.file_attachment);
                            } else {
                                $("#attach_file").removeClass('required');
                            }

                        }
                        $("#desk_status").html(option);

                        self.next().hide();
                        if (countDesk == 0) {
                            $('.dd_id').removeClass('required');
                            $('#sendToDeskOfficer').css('display', 'none');
                        }
                        else {
                            $('.dd_id').addClass('required');
                            $('#sendToDeskOfficer').css('display', 'block');
                        }
                    }
                });
            }
        });


        /**
         * load apply status list on load page
         * @type {jQuery}
         */
        var application_id = $("#application_id").val();
        var process_list_id = $("#process_list_id").val();
        var curr_process_status_id = $("#curr_process_status_id").val();
        $.ajaxSetup({async: false});
        var _token = $('input[name="_token"]').val();
        var delegate = '{{ @$delegated_desk }}';
        var state = false;
        $.post('/process-path/ajax/load-status-list', {
            curr_process_status_id: curr_process_status_id,
            application_id: application_id,
            process_list_id: process_list_id,
            delegate: delegate,
            _token: _token
        }, function (response) {

            if (response.responseCode == 1) {
                var option = '';
                var PriorityOption = '<option value="">Select One</option>';
                option += '<option selected="selected" value="">Select Below</option>';
                $.each(response.data, function (id, value) {
                    option += '<option value="' + value.id + '">' + value.status_name + '</option>';
                });
                var CurrentPriority = '{{ $appInfo->priority}}';
                $.each(response.priority, function (id, value) {
                    var selected = "";
                    if(id == CurrentPriority)
                        selected = "selected";
                    PriorityOption += '<option ' + selected + ' value="' + id + '">' + value + '</option>';
                });

                $("#application_status").html(option);
                $("#priority").html(PriorityOption);
                $("#application_status").trigger("change");
                $("#application_status").focus();
            } else if (response.responseCode == 5) {
                alert('Without verification, application can not be processed');
                break_for_pending_verification = 1;
                option = '<option selected="selected" value="">Select Below</option>';
                $("#application_status").html(option);
                $("#application_status").trigger("change");
                return false;
            } else {
                $('#status_id').html('Please wait');
            }
        });
        $.ajaxSetup({async: true});
    });


    /**
     * Check application verification and process time
     * @type {jQuery}
     */
    @if(\App\Libraries\CommonFunction::getUserType()=='4x404' && in_array($appInfo->desk_id,[1,2,3,4,5]))
    function getVerificationSession() {
        var setVerificationSession = '';
        var data_verification = $("#data_verification").val();
        var process_list_id = $("#process_list_id").val();
        $.get("{{url('process-path/check-process-validity')}}",
            {
                data_verification: data_verification,
                process_list_id: process_list_id
            },
            function (data, status) {
                if (data.responseCode == 1) {
                    setVerificationSession = setTimeout(getVerificationSession, 5000);
                } else {
                    alert('Sorry, Data has been updated by another user.');
                    window.location.href = "{{url($moduleName.'/list/'.\App\Libraries\Encryption::encodeId($appInfo->process_type_id))}}";
                }
            });
    }

    setVerificationSession = setTimeout(getVerificationSession, 5000);

    var setSession = '';

    function getSession() {
        $.get("/users/get-user-session", function (data, status) {
            if (data.responseCode == 1) {
                setSession = setTimeout(getSession, 3000);
            } else {
                alert('Your session has been closed. Please login again');
                window.location.replace('/login');
            }
        });
    }

    setSession = setTimeout(getSession, 3000);
    @endif
</script>
