@section('content')

<?php
$accessMode = ACL::getAccsessRight('Scheduling');
if (!ACL::isAllowed($accessMode, 'V'))
    die('no access right!');
?>

{!! Form::open(array('url' => '/exam/schedule/store/','method' => 'post', 'class' => 'form-horizontal', 'id'=>'ExamScheduleForm', 
'role' => 'form','enctype' => 'multipart/form-date')) !!}

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title" id="myModalLabel"> {{ trans('messages.create_schedule') }}</h4>
</div>

<div class="modal-body">

    <div class="errorMsg alert alert-danger alert-dismissible hidden">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button></div>
    <div class="successMsg alert alert-success alert-dismissible hidden">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button></div>


    <div class="row">
        <div class="col-md-12">

            <div class="col-md-6">
                <div class="form-group">
                    <label for="question_type" class="col-md-6">Type of Question: </label>
                    <div class="col-md-6">
                        {!! Form::select('question_type', $examType,'', ['class' => 'form-control input-md',
                        'id'=>'question_type','placeholder'=>'Select one']) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="question_title" class="col-md-6">Question Title: </label>
                    <div class="col-md-6">
                        <input class="form-control required input-md" placeholder="Enter the title of the exam" name="question_title" type="text" 
                               id="question_title" maxlength="100"/>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group ">
                    <label for="exam_available_from" class="col-md-6">Exam Available From: </label>
                    <div class="col-md-6">
                        <div class="datetimepicker input-group col-md-12">
                            <input class="form-control required input-md" placeholder="Datetimepicker" name="exam_available_from"
                                   type="text" id="exam_available_from"/>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group ">
                    <label for="exam_disabled_at" class="col-md-6">Exam Disabled At: </label>
                    <div class="col-md-6">
                        <div class="datetimepicker input-group col-md-12">
                            <input class="form-control required input-md" placeholder="Datetimepicker" name="exam_disabled_at"
                                   type="text" id="exam_disabled_at"/>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group ">
                    <label for="duration" class="col-md-6">Duration: </label>
                    <div class="col-md-6">
                        <div class="duration input-group col-md-12" data-format="HH:mm">
                            <input class="form-control required input-md" placeholder="Timepicker" name="duration"
                                   type="text" id="duration"/>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="mark_per_question" class="col-md-6">Marks per question: </label>
                    <div class="col-md-6">
                        <input type="number" class="form-control required input-md number" value="" placeholder="Weight of each question" 
                               name="mark_per_question" id="mark_per_question">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="max_included_questions" class="col-md-6">Maximum questions that can be selected: </label>
                    <div class="col-md-6">
                        <input type="number" class="form-control required input-md number" placeholder="Max questions to select" 
                               name="max_included_questions" id="max_included_questions"/>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="no_of_question_for_examinee" class="col-md-6">No. of questions available for examinee: </label>
                    <div class="col-md-6">
                        <input type="number" class="form-control required input-md number" name="no_of_question_for_examinee"
                               id="no_of_question_for_examinee" maxlength="20" placeholder="No. of questions examinee will get" />
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="exam_type" class="col-md-6">Type of Examination: </label>
                    <div class="col-md-6">
                        {!! Form::select('exam_type', ["User Type Wise"=>"User Type Wise","User Wise"=>"User Wise"],'',
                        ['class' => 'form-control input-md', 'id'=>'exam_type', 'placeholder' => 'Select One']) !!}
                    </div>
                </div>
            </div>            
            <div class="col-md-6 hidden" id="usersTypeDiv">
                <div class="form-group">
                    <label for="type_of_users" class="col-md-6">Type of Users: </label>
                    <div class="col-md-6">
                        {!! Form::select('type_of_users', $userTypes,'', ['class' => 'form-control input-md', 'id'=>'type_of_users',
                        'placeholder' => 'Select One']) !!}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal-footer" style="text-align:left;">
    <div class="pull-right">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

        {{-- @if(ACL::getAccsessRight('exam','E')) --}}

        <button type="button" value="Done" class="btn btn-primary" id="schedule_create_btn">Create</button>

        {{-- @endif --}} {{-- Checking ACL --}}

    </div>
</div>

{!! Form::close() !!}

<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>

<script type="text/javascript">

    $(document).ready(function () {
        var today = new Date();
        var yyyy = today.getFullYear();
        var mm = today.getMonth();
        var dd = today.getDate();

        $('.datetimepicker').datetimepicker({
            viewMode: 'months',
            sideBySide: true,
            minDate: (mm) + '/' + dd + '/' + yyyy,
            maxDate: (mm) + '/' + dd + '/' + (yyyy + 5),
        });

        $('.duration').datetimepicker({
            format: 'HH:mm'
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
                    console.log("before send");
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
                        info_suc.removeClass('hidden').html(data.status);
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

@section('footer-script')
<script>
    $(document).ready(
            function () {
                $("#ExamScheduleForm").validate({
                    errorPlacement: function () {
                        return false;
                    }
                });
            });
</script>

@endsection <!--- footer-script--->
