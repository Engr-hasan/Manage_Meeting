@extends('layouts.admin')

<?php
$accessMode = ACL::getAccsessRight('ResultProcess');
if (!ACL::isAllowed($accessMode, 'V'))
    die('no access right!');
?>

<style>
    .answer_div{
        margin: 0 0 20px 0;
        padding: 15px 30px 15px 15px;
        border-left: 5px solid #eee;
        -webkit-border-radius: 0 4px 4px 0;
        -moz-border-radius: 0 4px 4px 0;
        -ms-border-radius: 0 4px 4px 0;
        -o-border-radius: 0 4px 4px 0;
        border-radius: 0 4px 4px 0;
    }
    .correct{
        background-color: #eef7ea;
        border-color: #bbdba1;
        color: #3c763d;
    }
    .wrong{
        background-color: #f9f0f0;
        border-color: #dca7b0;
        color: #a94442;
    }

    .noTouch{
        background-color: #E6EDEC;
        border-color: #CACDCD;
        color: #777A79;
    }
    .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 8px !important;
        line-height: 1.42857143 !important;
        vertical-align: top !important;
        border-top: 1px solid #ddd !important;
    }
</style>

@section('content')

    <div class="col-lg-12">

        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

        <div class="panel panel-primary">
            <div class="panel-heading" style="padding: 2px;">
                <span style=" font-size: 18px;">{{ $heading }}</span>
                <div class="clearfix"></div>
            </div>

            <!-- /.panel-heading -->
            <div class="panel-body" style="padding-top: 15px;">
                <div class="row">
                    <div class="col-md-8">
                        <div class="panel panel-default">
                            <div class="panel-heading"><strong><i class="fa fa-graduation-cap" aria-hidden="true"></i> Question Details</strong></div>
                            <div class="panel-body" style="padding: 15px;">
                                <?php $i=1; ?>
                                @foreach($examQuestions as $examQuestion)
                                        <?php
                                            $statusClass = '';
                                            if($examQuestion->answer_status==1){
                                                $statusClass = 'correct';
                                            }
                                            if($examQuestion->answer_status==0){
                                                $statusClass = 'noTouch';
                                            }
                                            if(($examQuestion->answer_status==-1)){
                                                $statusClass = 'wrong';
                                            }
                                        ?>
                                    <input type="hidden" name="question[]" value="{{ $examQuestion->schedule_question_id }}">
                                    <div class="col-md-12 answer_div {{ $statusClass }}">
                                        <strong><img width="30" src="{{ ($examQuestion->answer_status == 1)? url('assets/images/right.png') : url('assets/images/wrong.png') }}"> {{$i++}}. &nbsp;&nbsp;{{ $examQuestion->question_name }}</strong>
                                        @if($examQuestion->additional_part != '')
                                        <div class="well notouch">
                                            {!! $examQuestion->additional_part !!}
                                        </div>
                                        @endif


                                        @foreach($examQuestionOptions[$examQuestion->schedule_question_id] as $examQuestionOption)
                                            <?php
                                            $checked = '';
                                            $given_answers = explode(',',$userAnsweredOptions[$examQuestion->schedule_question_id]->given_answers);

                                            if(in_array($examQuestionOption->id,$given_answers)){
                                                $checked = 'checked';
                                            }
                                            ?>
                                            <p style="padding: 7px 15px 0; @if($examQuestionOption->is_correct_answer == 1) background-color: #CEF7C0 @endif"><label style="font-weight: normal;">
                                                    <input {{$checked}} type="checkbox" disabled name="option[{{ $examQuestion->schedule_question_id }}][]" value="{{ $examQuestionOption->schedule_question_id }}" >
                                                    {{$examQuestionOption->option_name}} </label></p>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading"><strong><i class="fa fa-book" aria-hidden="true"></i> Exam Details</strong></div>
                            <div class="panel-body" style="padding: 15px;">
                                <table class="table">
                                    <tbody>
                                        <tr><td>Subject name</td><td>{{ $scheduleUserInfo->question_title }}</td></tr>
                                        <tr><td>Student's name</td><td>{{ $scheduleUserInfo->user_full_name }}</td></tr>
                                        <tr><td>User type</td><td>{{ $scheduleUserInfo->type_name }}</td></tr>
                                        <tr><td>Exam date</td><td>{{ date('d/m/Y',strtotime($scheduleUserInfo->exam_available_from)) }}</td></tr>
                                        <tr><td>Total question</td><td>{{ $scheduleUserInfo->no_of_question_for_examinee }}</td></tr>
                                        <tr><td>Right answer</td><td><span class="label label-success">{{ $scheduleUserInfo->no_of_right_answer }}</span></td></tr>
                                        <tr><td>Wrong answer</td><td><span class="label label-danger">{{ $scheduleUserInfo->no_of_wrong_answer }}</span></td></tr>
                                        <tr><td>No touch</td><td><span class="label label-default">{{ $scheduleUserInfo->no_of_not_touch }}</span></td></tr>
                                        <tr><td>Total mark</td><td>{{ $scheduleUserInfo->mark_per_question*$scheduleUserInfo->no_of_question_for_examinee }}</td></tr>
                                        <tr><td>Pass mark</td><td>{{ ($scheduleUserInfo->mark_per_question*$scheduleUserInfo->no_of_question_for_examinee*40)/100 }}</td></tr>
                                        <tr><td>Obtain mark</td><td>{{ $scheduleUserInfo->mark_obtain }}</td></tr>
                                        <tr><td>Result status</td><td><strong>{{ $scheduleUserInfo->remarks }}</strong></td></tr>
                                        <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                                        {{--<tr><td colspan="2" style="padding: 0px;"><a href="" class="btn btn-block btn-primary">Try Next Exam </a></td></tr>--}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.box-body -->
        </div>
    </div>

@endsection


@section('footer-script')
    @include('partials.datatable-scripts')
    <script type="text/javascript">

    </script>
@endsection <!--- footer script--->

