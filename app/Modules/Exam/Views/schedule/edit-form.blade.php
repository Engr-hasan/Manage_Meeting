<div class="row">
    <div class="col-md-12">

        <div class="col-md-6">
            <div class="form-group">
                <label for="question_type" class="col-md-6">Type of question: </label>
                <div class="col-md-6">
                    {!! Form::select('question_type', $examType, $data->question_type,
                    ['class' => 'form-control input-md','id'=>'question_type']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="question_title" class="col-md-6">Question title: </label>
                <div class="col-md-6">
                    <input class="form-control required input-md" placeholder="Enter the title of the exam" name="question_title" type="text" 
                           id="question_title" maxlength="100" value="{{$data->question_title}}"/>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group ">
                <label for="exam_available_from" class="col-md-6">Exam available from: </label>
                <div class="col-md-6">
                    <div class="datetimepicker input-group col-md-12">
                        <?php
                        $exam_available_from = !empty($data->exam_available_from) ? date('m-d-Y h:i A', strtotime($data->exam_available_from)) : '';
                        ?>
                        <input class="form-control required input-md bnEng" name="exam_available_from" type="text"
                               id="exam_available_from" value="{{$exam_available_from}}" placeholder="Datetimepicker" />
                        @if($viewMode != 'on')
                        <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                        @endif {{-- view mode off --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group ">
                <label for="exam_disabled_at" class="col-md-6">Exam disabled at: </label>
                <div class="col-md-6">
                    <div class="datetimepicker input-group col-md-12">
                        <?php
                        $exam_disabled_at = !empty($data->exam_disabled_at) ? date('m-d-Y h:i A', strtotime($data->exam_disabled_at)) : '';
                        ?>
                        <input class="form-control required input-md bnEng"  name="exam_disabled_at" type="text"
                               id="exam_disabled_at" value="{{$exam_disabled_at}}" placeholder="Datetimepicker"/>
                        @if($viewMode != 'on')
                        <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                        @endif {{-- view mode off --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group ">
                <label for="duration" class="col-md-6">Duration: </label>
                <div class="col-md-6">
                    <div class="duration input-group col-md-12" data-format="HH:mm">
                        <?php
                        $duration = !empty($data->duration) ? date('H:i', strtotime($data->duration)) : '';
                        ?>
                        <input class="form-control required input-md bnEng" placeholder="Timepicker" name="duration"
                               type="text" id="duration" value="{{$duration}}"/>
                        @if($viewMode != 'on')
                        <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                        @endif {{-- view mode off --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="mark_per_question" class="col-md-6">Marks per question: </label>
                <div class="col-md-6">
                    <input type="number" class="form-control required input-md number" placeholder="Weight of each question" 
                           name="mark_per_question" id="mark_per_question" value="{{$data->mark_per_question}}">
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="max_included_questions" class="col-md-6">Maximum questions that can be selected: </label>
                <div class="col-md-6">
                    <input type="number" class="form-control required input-md number" placeholder="Max questions to select" 
                           name="max_included_questions" id="max_included_questions" value="{{$data->max_included_questions}}"/>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="no_of_question_for_examinee" class="col-md-6">No. of questions available for examinee: </label>
                <div class="col-md-6">
                    <input type="number" class="form-control required input-md number" name="no_of_question_for_examinee"
                           id="no_of_question_for_examinee" maxlength="20" placeholder="No. of questions examinee will get"
                           value="{{$data->no_of_question_for_examinee}}"/>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="exam_type" class="col-md-6">Type of examination: </label>
                <div class="col-md-6">
                    {!! Form::select('exam_type', ["User Type Wise"=>"User Type Wise","User Wise"=>"User Wise"], $data->exam_type,
                    ['class' => 'form-control input-md', 'id'=>'exam_type']) !!}
                </div>
            </div>
        </div>
        <?php $visibility= ($data->exam_type == 'User Type Wise')? '':'hidden';?>
        <div class="col-md-6 {{$visibility}}"  id="usersTypeDiv">
            <div class="form-group">
                <label for="type_of_users" class="col-md-6">Type of users: </label>
                <div class="col-md-6">
                    {!! Form::select('type_of_users', $userTypes,$data->type_of_users, ['class' => 'form-control input-md', 'id'=>'type_of_users',
                    'placeholder' => 'Select One']) !!}
                </div>
            </div>
        </div>

    </div>
</div>