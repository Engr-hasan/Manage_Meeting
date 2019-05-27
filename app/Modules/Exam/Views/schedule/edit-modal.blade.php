@section('content')

<?php
$accessMode = ACL::getAccsessRight('Scheduling');
if (!ACL::isAllowed($accessMode, 'V'))
    die('no access right!');
?>

{!! Form::open(array('url' => '/exam/schedule/update/'.$_id,'method' => 'patch', 'class' => 'form-horizontal','id' => 'ExamScheduleForm',
'role' => 'form','enctype' =>'multipart/form-date')) !!}

<div class=" modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title" id="myModalLabel"> <b> Editing Schedule</b> </h4>
</div>

<div class="modal-body">

    <div class="errorMsg alert alert-danger alert-dismissible hidden">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button></div>
    <div class="successMsg alert alert-success alert-dismissible hidden">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button></div>


    @include('Exam::schedule.edit-form')

    {!! Form::hidden('last_updated_by', $data->updated_by, ['class'=>'','data-rule-maxlength'=>'20']) !!}
    {!! Form::hidden('last_updated_at', $data->updated_at, ['class'=>'','data-rule-maxlength'=>'20']) !!}

</div>

<div  class="modal-footer">
    <div class="row">
        <div class="col-md-7">
            {!! CommonFunction::showAuditLog($data->updated_at, $data->updated_by) !!}
        </div>
        <div class="col-md-5">
            {{-- @if(ACL::getAccsessRight('exam','E')) --}}
            <button type="button" class="btn btn-primary pull-right" id="schedule_create_btn"><i class="fa fa-save"></i> Save</button>
            {{-- @endif --}} {{-- Checking ACL --}}
        </div>
    </div>
</div>

{!! Form::close() !!}<!-- /.form end -->

<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>

<script type="text/javascript">

    $(document).ready(function () {
        var today = new Date();
        var yyyy = today.getFullYear();
        var mm = today.getMonth();
        var dd = today.getDate();
        $('#ScheduleModal').on('shown.bs.modal', function () {
            setTimeout(function () {
                $('.datetimepicker').datetimepicker({
                    viewMode: 'months',
                    sideBySide: true,
                    minDate: (mm) + '/' + dd + '/' + yyyy,
                    maxDate: (mm) + '/' + dd + '/' + (yyyy + 5),
                });
                $('.duration').datetimepicker({
                    format: 'HH:mm'
                });
            }, 2000);
        });


        $('#exam_type').click(function (e) {
            if (this.value == 'User Type Wise') {
                $('#usersTypeDiv').removeClass('hidden');
                $('#type_of_users').addClass('required');
            } else {
                $('#usersTypeDiv').addClass('hidden');
                $('#type_of_users').removeClass('required');
            }
        });

    });

    $(document).ready(function () {
        $("#schedule_create_btn").on('click', function () {
            $("#ExamScheduleForm").submit();
        });

        $("#ExamScheduleForm").validate({
            rules: {
                question_type: "required",
                question_title: "required",
                exam_available_from: "required",
                exam_disabled_at: "required",
                duration: "required",
                mark_per_question: {
                    required: true,
                    number: true,
                    minlength: 1
                },
                max_included_questions: {
                    required: true,
                    number: true
                },
                no_of_question_for_examinee: {
                    required: true,
                    number: true
                },
                exam_type: "required"
            },
//            submitHandler to ajax function
            submitHandler: formSubmit
        });

        var form = $("#ExamScheduleForm"); //Get Form ID
        var url = form.attr("action"); //Get Form action
        var type = form.attr("method"); //get form's data send method
        var info_err = $('.errorMsg'); //get error message div
        var info_suc = $('.successMsg'); //get success message div

        //============Ajax Setup===========//
        function formSubmit() {
            $.ajax({
                type: type,
                url: url,
                data: form.serialize(),
                dataType: 'json',
                beforeSend: function (msg) {
                    $("#schedule_create_btn").html('<i class="fa fa-cog fa-spin"></i> Loading...');
                    $("#schedule_create_btn").prop('disabled', true); // disable button
                },
                success: function (data) {
                    //==========validation error===========//
                    if (data.success == false) {
                        info_err.hide().empty();
                        $.each(data.error, function (index, error) {
                            info_err.removeClass('hidden').append('<li>' + error + '</li>');
                        });
                        info_err.slideDown('slow');
                        info_err.delay(2000).slideUp(1000, function () {
                            $("#schedule_create_btn").html('Submit');
                            $("#schedule_create_btn").prop('disabled', false);
                        });
                    }
                    //==========if data is saved=============//
                    if (data.success == true) {
                        info_suc.hide().empty();
                        info_suc.removeClass('hidden').html(data.message);
                        info_suc.slideDown('slow');
                        info_suc.delay(2000).slideUp(800, function () {
                            window.location.href = data.link;
                        });
                        form.trigger("reset");

                    }
                    //=========if data already submitted===========//
                    if (data.error == true) {
                        info_err.hide().empty();
                        info_err.removeClass('hidden').html(data.status);
                        info_err.slideDown('slow');
                        info_err.delay(1000).slideUp(800, function () {
                            $("#schedule_create_btn").html('Submit');
                            $("#schedule_create_btn").prop('disabled', false);
                        });
                    }
                },
                error: function (data) {
                    var errors = data.responseJSON;
                    $("#schedule_create_btn").prop('disabled', false);
                    console.log(errors);
                    alert('Sorry, an unknown Error has been occured! Please try again later.');
                }
            });
            return false;
        }
    });

</script>

