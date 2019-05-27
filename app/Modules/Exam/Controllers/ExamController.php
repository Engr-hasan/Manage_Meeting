<?php

namespace App\Modules\Exam\Controllers;

use App;
use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\Exam\Models\ExamEmailQueue;
use App\Modules\Exam\Models\ExamType;
use App\Modules\Exam\Models\Schedule;
use App\Modules\Exam\Models\ScheduleUsers;
use App\Modules\Exam\Models\Question;
use App\Modules\Exam\Models\QuestionOption;
use App\Modules\Exam\Models\ScheduleQuestion;
use App\Modules\Exam\Models\ScheduleQuestionOption;
use App\Modules\Exam\Models\UserAnswerOption;
use App\Modules\Exam\Models\UserAnswerQuestion;
use App\Modules\Users\Models\UsersModel;
use App\Modules\Users\Models\UserTypes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Excel;
use Mockery\CountValidator\Exception;
use PHPExcel_Style_Fill;
use yajra\Datatables\Datatables;

class ExamController extends Controller {

    public function __construct() {
        if (Session::has('lang'))
            App::setLocale(Session::get('lang'));
    }

    public function hasInitiatedSchedule($scheduleListId){
        $scheduleStatus = Schedule::where('id',$scheduleListId)->pluck('schedule_status');
        if($scheduleStatus == 'Initiated'){
            return true;
        }
        return false;
    }

    public function questionList() {
        if (!ACL::getAccsessRight('QuestionBank', 'V')) {
            die('You have no access right!');
        }

        return view('Exam::question.list');
    }

    public function getQuestionList() {
        $questionList = Question::getQuestionList();
        return Datatables::of($questionList)
            ->editColumn('last_update', function ($data) {
                return CommonFunction::showExamAuditLog($data->last_update, $data->updated_by);
            })
            ->addColumn('action', function ($data) {
                $link = '<a href="' . url('exam/question-bank/edit/' . Encryption::encodeId($data->id)) .
                    '" class="btn btn-xs btn-info"><i class="fa fa-edit"></i> Edit</a>' . ' ';

                $link .= '<a href="' . url('exam/question-bank/view/' . Encryption::encodeId($data->id)) .
                    '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open-o"></i> Open</a>' . ' ';

                $link .= '<a href="' . url('exam/question-bank/delete/' . Encryption::encodeId($data->id)) .
                    '" onclick = "return confirm(' . "'Are you sure?'" . ')" class="btn btn-xs btn-danger"><i class="fa fa-trash-o"></i> Delete</a>' . ' ';

                return $link;
            })
            ->make(true);
    }

    public function addQuestion() {
        if (!ACL::getAccsessRight('QuestionBank', 'V')) {
            die('You have no access right!');
        }
        $examType = ExamType::lists('exam_name','id');
        return view('Exam::question.create',compact('examType'));
    }

    public function editQuestion($questionId) {
        if (!ACL::getAccsessRight('QuestionBank', 'V')) {
            die('You have no access right!');
        }

        $decodedQuestionId = Encryption::decodeId($questionId);
        $question = Question::find($decodedQuestionId);
        $options = QuestionOption::where('question_id', $decodedQuestionId)
            ->orderBy('option_no')
            ->get();
        $examType = ExamType::lists('exam_name','id');
        $viewStatus = 'off';
        return view('Exam::question.edit', compact('question', 'options', 'viewStatus','examType'));
    }

    public function storeQuestion(Request $request) {

        if (!ACL::getAccsessRight('QuestionBank', 'A')) {
            die('You have no access right!');
        }

        $rules = [
            'question_type' => 'required',
            'question_name' => 'required',
        ];
        $message = [
            'question_name.required' => 'The question is required.',
            'correct_answer.required' => 'You have to select at least 1 correct answer.',
        ];
        if ($request->get('correct_answer') == null) {
            $rules['correct_answer'] = 'required';
        }
        $this->validate($request, $rules, $message);


        $uniqueArray = $request->get('answer_option');
        if(count(array_unique($uniqueArray)) != count($request->get('answer_option'))){
            Session::flash('error', 'Similar option is not allow for a question.');
            return redirect()->back()->withInput();
        }

        try {
            $questionData = new Question;
            $questionData->question_type = $request->get('question_type');
            $questionData->question_name = $request->get('question_name');
            $questionData->additional_part = $request->get('additional_part');
            $questionData->save();

            $questionId = $questionData->id;
            $answerOption = $request->get('answer_option');
            $correctAnswerArray = $request->get('correct_answer');

            $i=1;
            foreach ($answerOption as $key => $value) {
                $questionOption = new QuestionOption;
                $questionOption->question_id = $questionId;
                $questionOption->option_name = $value;
                $questionOption->option_no = $i;$i++;
                $questionOption->is_correct_answer = in_array($key, $correctAnswerArray) ? 1 : 0;
                $questionOption->save();
            }
            Session::flash('success', 'Question successfully saved.');
            return redirect('/exam/question-bank/list');
        } catch (\Exception $e) {

            if($e->getCode() == 23000){
                Session::flash('error', 'Duplicate question entry is not allow for same question type.');
                return redirect()->back()->withInput();
            }
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return redirect('/exam/question-bank/list');

        }
    }

    public function updateQuestion($questionId, Request $request) {

        if (!ACL::getAccsessRight('QuestionBank', 'E')) {
            die('You have no access right!');
        }

        $decodedQuestionId = Encryption::decodeId($questionId);
        $rules = [
            'question_type' => 'required',
            'question_name' => 'required',
        ];
        $message = [
            'question_name.required' => 'The question is required.',
            'correct_answer.required' => 'You have to select at least 1 correct answer.',
        ];
        if ($request->get('correct_answer') == null) {
            $rules['correct_answer'] = 'required';
        }
        $this->validate($request, $rules, $message);

        try {
            $questionData = Question::find($decodedQuestionId);
            $questionData->question_type = $request->get('question_type');
            $questionData->question_name = $request->get('question_name');
            $questionData->additional_part = $request->get('additional_part');
            $questionData->save();

            QuestionOption::where('question_id', $decodedQuestionId)->delete();

            $answerOption = $request->get('answer_option');
            $correctAnswerArray = $request->get('correct_answer');

            $i=1;
            foreach ($answerOption as $key => $value) {
                $questionOption = new QuestionOption;
                $questionOption->question_id = $questionData->id;
                $questionOption->option_name = $value;
                $questionOption->option_no = $i;$i++;
                $questionOption->is_correct_answer = in_array($key, $correctAnswerArray) ? 1 : 0;
                $questionOption->save();
            }
            Session::flash('success', 'Question successfully updated.');
            return redirect('/exam/question-bank/list');
        } catch (\Exception $e) {

            if($e->getCode() == 23000){
                Session::flash('error', 'Duplicate question entry is not allow for same question type.');
                return redirect()->back()->withInput();
            }

            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return redirect('/exam/question-bank/list');
        }
    }

    public function viewQuestion($questionId) {
        if (!ACL::getAccsessRight('QuestionBank', 'V')) {
            die('You have no access right!');
        }
        $decodedQuestionId = Encryption::decodeId($questionId);
        $question = Question::find($decodedQuestionId);
        $options = QuestionOption::where('question_id', $decodedQuestionId)
            ->orderBy('option_no')
            ->get();
        $viewStatus = 'on';
        $examType = ExamType::lists('exam_name','id');
        return view('Exam::question.edit', compact('question', 'options', 'viewStatus','examType'));
    }

    public function deleteQuestion($questionId) {
        if (!ACL::getAccsessRight('QuestionBank', 'E')) {
            die('You have no access right!');
        }
        $decodedQuestionId = Encryption::decodeId($questionId);
        Question::find($decodedQuestionId)->delete();
        QuestionOption::where('question_id',$decodedQuestionId)->delete();
        Session::flash('success', 'Question deleted successfully.');
        return redirect('exam/question-bank/list');
    }

    public function downloadQuestionInfo(Excel $excel) {

        $excelData[1]['question_type'] = 'Simple';
        $excelData[1]['question_name'] = 'Type your pre-registration related questions in this format.';
        $excelData[1]['answer_option_1'] = 'Option 1';
        $excelData[1]['answer_option_2'] = 'Option 2';
        $excelData[1]['answer_option_3'] = 'Option 3';
        $excelData[1]['answer_option_4'] = 'Option 4';
        $excelData[1]['correct_answer'] = '1';

        $excelData[2]['question_type'] = 'Medium';
        $excelData[2]['question_name'] = 'Type your registration related questions in this format.';
        $excelData[2]['answer_option_1'] = 'Option 1';
        $excelData[2]['answer_option_2'] = 'Option 2';
        $excelData[2]['answer_option_3'] = 'Option 3';
        $excelData[2]['answer_option_4'] = 'Option 4';
        $excelData[2]['correct_answer'] = '2,3';

        $downloadable_file_name = 'question_info_' . date('Y_m_d');
        $excel->create($downloadable_file_name, function ($excel) use ($excelData) {
            $excel->sheet('Sheetname', function ($sheet) use ($excelData) {
                $sheet->fromArray($excelData)->getStyle('A1:H1')->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'D7E4BC')
                        ),
                        'font' => array(
                            'bold' => true
                        )
                    )
                );
            });
        })->download('xlsx');
    }

    public function uploadQuestionInfo(Request $request) {

        $this->validate($request, [
            'import_question' => 'required'
        ]);

        $data = $request->all();
        $file = $data['import_question'];
        $file_mime = $file->getMimeType();
        $mimes = array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'text/plain',
            'text/csv', 'text/tsv');
        if (in_array($file_mime, $mimes)) {
            if (!empty($file)) {
                $filename = $file->getClientOriginalName();
                $fileOnlyName = pathinfo($filename, PATHINFO_FILENAME);
                $savedPath = 'question-upload/'; // upload path
                $extension = $file->getClientOriginalExtension(); // getting image extension
                $onlyFileName = $fileOnlyName . '_' . rand(11111, 99999);
                $fileName = $onlyFileName . '.' . $extension; // rename of file name
                $path = public_path($savedPath);
                $file->move($path, $fileName);
                $filePath = $savedPath . $fileName;
                // redirect view question information
                return redirect('exam/question-bank/question-verification/' . Encryption::encode($filePath));
            } else {
                Session::flash('error', 'Sorry! Something went wrong. Question file upload failed.');
                return redirect('/exam/question-bank/list/');
            }
        } else {
            Session::flash('error', 'Only supported file formats are csv, xls or xlsx format!');
            return redirect('/exam/question-bank/list/');
        }
    }

    public function uploadedQuestionsVerification($path, Excel $excel) {
        $getFilePath = Encryption::decode($path);
        if (!file_exists($getFilePath)) {
            Session::flash('error', 'Sorry, something went wrong. File does not exist.');
            return redirect('/exam/question-bank/list/');
        }
        $excelUrl = $path;
        $excelData = $excel->selectSheetsByIndex(0)->load($getFilePath)->get();

        if (!empty($excelData) && $excelData->count() > 0) {
            $firstrow = $excelData->first()->toArray();

            $heading = ['question_type',
                'question_name',
                'answer_option_1',
                'answer_option_2',
                'answer_option_3',
                'answer_option_4',
                'correct_answer'];
            $examTypes = ExamType::lists('exam_name','id');
            $questionTypes = json_decode(json_encode($examTypes), True);

            foreach ($firstrow as $key => $column_firstrow) {
                if (!in_array($key, $heading)) {
                    Session::flash('error', 'The header of the file is not written according to the given sample. Please upload a file with similar heading.');
                    return redirect('/exam/question-bank/list/');
                }
            }
            $invalidQuestionType = [];
            $expectedCorrectAnswers = [1,2,3,4];
            foreach($excelData as $exData){
                $givencorrectAnswers = [];
                $givenOptions = [];
                if(!in_array($exData->question_type,$questionTypes)){
                    $invalidQuestionType[] = $exData->question_type;
                }else{
                    if($exData->correct_answer != ''){
                        $givencorrectAnswers = explode(',',$exData->correct_answer);
                    }else{
                        Session::flash('error', 'The correct answer field should not be empty, please check your file.');
                        return redirect('/exam/question-bank/list/');
                    }

                }



                foreach($givencorrectAnswers as $given){
                    if(!in_array($given,$expectedCorrectAnswers)){
                        Session::flash('error', 'Please check the correct answers in your file, Only 1,2,3,4 are allow as correct answer.');
                        return redirect('/exam/question-bank/list/');
                    }
                }
            }

            if(count($invalidQuestionType)>0){
                Session::flash('error', 'Invalid Question Type !!!! Your question type is not available. Please check question type and spelling should be correct.');
                return redirect('/exam/question-bank/list/');
            }

            $count_status = array();
        } else {
            Session::flash('error', 'Sorry, system can not read data from the uploaded file! Please upload a file similar to given sample.');
            return redirect('/exam/question-bank/list/');
        }

        return view("Exam::question.question-verification", compact('excelData', 'excelUrl', 'count_status'));
    }

    public function saveQuestionExcel(Excel $excel, Request $request) {
        try {
            $data = $request->all();
            if (!empty($data['excelUrl'])) {
                $getFilePath = Encryption::decode($data['excelUrl']);
                if (!file_exists($getFilePath)) {
                    Session::flash('error', 'Sorry, something went wrong. File path is incorrect.');
                    return redirect('/exam/question-bank/list/');
                }
            } else {
                Session::flash('error', 'Sorry, something went wrong. File does not exist.');
                return redirect('/exam/question-bank/list/');
            }

            $excelData = $excel->selectSheetsByIndex(0)->load($getFilePath)->get();
            $examTypes = ExamType::lists('exam_name','id');
            $questionTypes = json_decode(json_encode($examTypes), True);

            foreach ($excelData as $data) {

                //For not allowing similar option for a question
                $optoins = [];
                for($i = 1; $i <= 4; $i++){
                    $optoins[] = $data['answer_option_'.$i];
                }
                $uniqueArray = $optoins;
                if(count(array_unique($uniqueArray)) != count($optoins)){
                    Session::flash('error', 'Similar option is not allow for a question, please modify your options.');
                    return redirect()->back()->withInput();
                }

                if(in_array($data['question_type'],$questionTypes)){
                    $examTypeId = array_search($data['question_type'],$questionTypes);
                }

                DB::beginTransaction();
                $ques = new Question;
                $ques->question_type = $examTypeId;
                $ques->question_name = $data['question_name'];
                $ques->save();
                $correctAnswerArr = explode(',', $data['correct_answer']);

                for ($i = 1; $i <= 4; $i++) {
                    $quesOption = new QuestionOption;
                    $quesOption->question_id = $ques->id;
                    $quesOption->option_name = $data['answer_option_'.$i];
                    $quesOption->option_no = $i;
                    $is_correct_answer = in_array($i, $correctAnswerArr) ? 1 : 0;
                    $quesOption->is_correct_answer = $is_correct_answer;
                    $quesOption->save();
                }
                DB::commit();
            }
            Session::flash('success', "Questions have been updated successfully.");
            return redirect('/exam/question-bank/list/');
        } catch (\Exception $e) {
            DB::rollback();

            if($e->getCode() == 23000){
                Session::flash('error', 'Duplicate question entry is not allow for same question type, please check your question in your file.');
                return redirect()->back()->withInput();
            }

            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return redirect('/exam/question-bank/list/');
        }
    }

    /* Ending of Question related functions */

    public function scheduleList() {
        if (!ACL::getAccsessRight('Scheduling', 'V')) {
            die('You have no access right!');
        }
        return view('Exam::schedule.list');
    }

    public function getScheduleList() {
        DB::statement(DB::raw('set @serial=0'));
        $questions = Schedule::leftJoin('ex_exam_type as et','et.id','=','ex_schedule.question_type')
            ->orderBy('ex_schedule.id', 'desc')
            ->where('ex_schedule.is_archive', 0)
            ->get([
                'ex_schedule.id',
                'et.exam_name',
                'ex_schedule.question_title',
                'ex_schedule.schedule_status',
                'ex_schedule.updated_at as last_update',
                DB::raw('@serial  := @serial  + 1 AS serial')
            ]);

        return Datatables::of($questions)
            ->addColumn('action', function ($data) {
                $link = ' <a href="' . url('exam/schedule/view/' . Encryption::encodeId($data->id)) .
                    '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open-o"></i> Open</a>' . ' ';
                return $link;
            })
            ->editColumn('last_update', function ($data) {
                return CommonFunction::changeDateFormat(substr($data->last_update, 0, 10))
                . ' at ' . substr($data->last_update, 11, 8);
            })
            ->make(true);
    }

    public function addSchedule() {
        if (!ACL::getAccsessRight('Scheduling', 'V')) {
            die('You have no access right!');
        }
        $userTypes = UserTypes::where('status', 'active')
            ->whereNotIn('id',['9x909'])
            ->orderBy('type_name')
            ->lists('type_name', 'id');
        $examType = ExamType::lists('exam_name','id');
        return view('Exam::schedule.create', compact('userTypes','examType'));
    }

    public function storeSchedule(Request $request) {
        if (!ACL::getAccsessRight('Scheduling', 'A')) {
            die('You have no access right!');
        }
        $rules = [
            'question_type' => 'required',
            'question_title' => 'required',
            'exam_available_from' => 'required',
            'exam_disabled_at' => 'required',
            'duration' => 'required',
            'mark_per_question' => 'required',
            'max_included_questions' => 'required',
            'no_of_question_for_examinee' => 'required',
            'exam_type' => 'required',
        ];
        $validation = Validator::make(Input::all(), $rules);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validation->errors(),
            ]);
        }

        if($request->get('exam_available_from')>=$request->get('exam_disabled_at')){
            return response()->json([
                'error' => true,
                'status' => 'Select valid data for Exam available and Exam disable'
            ]);
        }

        if($request->get('max_included_questions')<=$request->get('no_of_question_for_examinee')){
            return response()->json([
                'error' => true,
                'status' => 'Number of maximum questions must be greater than available questions for examinee'
            ]);
        }

        try {
            DB::beginTransaction();

            $entryData = array(
                'question_type' => $request->get('question_type'),
                'question_title' => $request->get('question_title'),
                'exam_available_from' => date('Y-m-d H:i:s', strtotime($request->get('exam_available_from'))),
                'exam_disabled_at' => date('Y-m-d H:i:s', strtotime($request->get('exam_disabled_at'))),
                'duration' => date('H:i', strtotime($request->get('duration'))),
                'mark_per_question' => $request->get('mark_per_question'),
                'max_included_questions' => $request->get('max_included_questions'),
                'no_of_question_for_examinee' => $request->get('no_of_question_for_examinee'),
                'exam_type' => $request->get('exam_type'),
                'type_of_users' => $request->get('type_of_users'),
                'is_archive' => 0,
            );
            $schedule = Schedule::create($entryData);

            if ($request->get('exam_type') == "User Type Wise") {
                $userType = $request->get('type_of_users');

                $typewiseUsers = UsersModel::where('users.is_approved', 1)
                    ->where('users.user_status', 'active')
                    ->where('users.user_type', $userType)
                    ->get(['users.id']);
                $scheduleUserDatas=[];
                foreach ($typewiseUsers as $user) {
                    $scheduleUserDatas[] = array(
                        'schedule_id' => $schedule->id,
                        'user_id' => $user->id,
                        'is_archive' => 0,
                    );
                }
                $insert = ScheduleUsers::insert($scheduleUserDatas);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'status' => 'Exam schedule has been created successfully!',
                'link' => '/exam/schedule/view/' . Encryption::encodeId($schedule->id)
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'status' => 'There is an unknown error. Please try again later!'
            ]);
        }
    }

    public function editSchedule($_id) {
        if (!ACL::getAccsessRight('Scheduling', 'V')) {
            die('You have no access right!');
        }
        $id = Encryption::decodeId($_id);
        $data = Schedule::where('id', $id)->first();
        $userTypes = UserTypes::where('status', 'active')
            ->whereNotIn('id',['9x909'])
            ->orderBy('type_name')
            ->lists('type_name', 'id');
        $examType = ExamType::lists('exam_name','id');
        $viewMode = 'off';
        return view('Exam::schedule.edit-modal', compact('_id', 'data', 'userTypes', 'viewMode','examType'));
    }

    public function updateSchedule($scheduleListId, Request $request) {
        if (!ACL::getAccsessRight('Scheduling', 'E')) {
            die('You have no access right!');
        }
        $decodedScheduleListId = Encryption::decodeId($scheduleListId);
        $rules = [
            'question_type' => 'required',
            'question_title' => 'required',
            'exam_available_from' => 'required',
            'exam_disabled_at' => 'required',
            'duration' => 'required',
            'mark_per_question' => 'required',
            'max_included_questions' => 'required',
            'no_of_question_for_examinee' => 'required',
            'exam_type' => 'required',
        ];

        $validation = Validator::make(Input::all(), $rules);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validation->errors(),
            ]);
        }

        if($request->get('exam_available_from')>=$request->get('exam_disabled_at')){
            return response()->json([
                'error' => true,
                'status' => 'Select valid data for Exam available and Exam disable'
            ]);
        }

        if($request->get('max_included_questions')<=$request->get('no_of_question_for_examinee')){
            return response()->json([
                'error' => true,
                'status' => 'Number of maximum questions must be greater than available questions for examinee'
            ]);
        }

        try {

            DB::beginTransaction();

            $examScheduleData = Schedule::find($decodedScheduleListId);
            $oldExamtype=$examScheduleData->exam_type;
            $examScheduleData->question_type = $request->get('question_type');
            $examScheduleData->question_title = $request->get('question_title');
            $examScheduleData->exam_available_from = date('Y-m-d H:i:s', strtotime($request->get('exam_available_from')));
            $examScheduleData->exam_disabled_at = date('Y-m-d H:i:s', strtotime($request->get('exam_disabled_at')));
            $examScheduleData->duration = date('H:i', strtotime($request->get('duration')));
            $examScheduleData->mark_per_question = $request->get('mark_per_question');
            $examScheduleData->max_included_questions = $request->get('max_included_questions');
            $examScheduleData->no_of_question_for_examinee = $request->get('no_of_question_for_examinee');
            $examScheduleData->mark_per_question = $request->get('mark_per_question');
            $examScheduleData->exam_type = $request->get('exam_type');
            $examScheduleData->type_of_users = $request->get('type_of_users');
            $examScheduleData->save();


            if ($request->get('exam_type') == "User Type Wise" and $oldExamtype!="User Type Wise") {
                $userType = $request->get('type_of_users');

                $typewiseUsers = UsersModel::where('users.is_approved', 1)
                    ->where('users.user_status', 'active')
                    ->where('users.user_type', $userType)
                    ->get(['users.id']);
                $scheduleUserDatas=[];
                ScheduleUsers::where('schedule_id',$examScheduleData->id)->delete();
                foreach ($typewiseUsers as $user) {
                    $scheduleUserDatas[] = array(
                        'schedule_id' => $examScheduleData->id,
                        'user_id' => $user->id,
                        'is_archive' => 0,
                    );
                }
                $insert = ScheduleUsers::insert($scheduleUserDatas);
            }
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data is changed successfully!',
                'link' => '/exam/schedule/view/' . $scheduleListId
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            $message = Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }
    }

    public function viewSchedule($_id) {
        if (!ACL::getAccsessRight('Scheduling', 'V')) {
            die('You have no access right!');
        }
        $decodedScheduleId = Encryption::decodeId($_id);
        $data = Schedule::find($decodedScheduleId);
        $userTypes = UserTypes::where('status', 'active')->orderBy('type_name')->lists('type_name', 'id');
        $viewMode = 'on';
        $examType = ExamType::lists('exam_name','id');
        return view('Exam::schedule.view', compact('_id', 'data', 'userTypes', 'viewMode','examType'));
    }

    public function publishSchedule($scheduleId){
        if (!ACL::getAccsessRight('Scheduling', 'E')) {
            die('You have no access right!');
        }

        $decodedScheduleId = Encryption::decodeId($scheduleId);
        try{
            $schedule = Schedule::find($decodedScheduleId);
            $currentQuestionsForSchedule = ScheduleQuestion::where('schedule_id',$decodedScheduleId)->count();
            $maxQuestionsForSchedule = $schedule->max_included_questions;
            $minQuestionsForSchedule = $schedule->no_of_question_for_examinee;

            if($currentQuestionsForSchedule<$minQuestionsForSchedule){
                Session::flash('error','Minimum '.$minQuestionsForSchedule.' questions need to add for publish this schedule.');
                return redirect()->back();
            }

            if($currentQuestionsForSchedule>$maxQuestionsForSchedule){
                Session::flash('error','Maximum '.$maxQuestionsForSchedule.' question allow for this schedule.');
                return redirect()->back();
            }

            if($currentQuestionsForSchedule>=$minQuestionsForSchedule && $currentQuestionsForSchedule <= $maxQuestionsForSchedule){
                $schedule->schedule_status = 'Schedule Published';
                DB::beginTransaction();
                $schedule->save();
                /* Email will send to all user in published schedule */
                $this->sendingMailAfterSchedulePublish($decodedScheduleId);
                DB::commit();
                Session::flash('success','Schedule published successfully.');
                return redirect()->back();
            }
        }catch(\Exception $e){
            DB::rollback();
            Session::flash('error','Something went wrong'.$e->getMessage());
            return redirect()->back();
        }


    }

    public function scheduleExamTaken($scheduleId){
        if (!ACL::getAccsessRight('Scheduling', 'E')) {
            die('You have no access right!');
        }
        try{
            $decodedScheduleId = Encryption::decodeId($scheduleId);
            $schedule = Schedule::find($decodedScheduleId);
            $schedule->schedule_status = 'Exam Taken';
            DB::beginTransaction();
            $schedule->save();
            $this->sendingMailAfterExamTaken($decodedScheduleId);
            DB::commit();
            Session::flash('success','Schedule exam taken successfully.');
            return redirect()->back();
        }catch(\Exception $e){
            DB::rollback();
            Session::flash('error','Something went wrong '. $e->getMessage());
            return redirect()->back();
        }

    }

    public function QuestionListForSchedule($_id) {
        if (!ACL::getAccsessRight('Scheduling', 'V')) {
            die('You have no access right!');
        }
        return view('Exam::schedule.list-questions', compact('_id'));
    }

    public function getQuestionListForSchedule($_id) {
        $scheduleId = Encryption::decodeId($_id);
        $scheduleQuesType = Schedule::where('id', $scheduleId)->pluck('question_type');

        DB::statement(DB::raw('set @serial=0'));
        $questions = Question::leftJoin('ex_schedule_question', function($join) use($scheduleId) {
            $join->on('ex_question.id', '=', 'ex_schedule_question.question_id');
            $join->on('ex_schedule_question.schedule_id', '=', DB::raw($scheduleId));
            $join->on('ex_schedule_question.is_archive', '=', DB::raw(0));
        })
            ->leftJoin('ex_exam_type as et','et.id','=','ex_question.question_type')
            ->where('ex_question.is_archive', 0)
            ->where('ex_question.question_type', $scheduleQuesType)
            ->where('ex_schedule_question.question_id', '=', null)
            ->orderBy('serial', 'asc')
            ->get([
                'ex_question.id',
                'et.exam_name',
                'ex_question.question_name',
                'ex_question.updated_at as last_update',
                DB::raw('@serial  := @serial  + 1 AS serial')
            ]);

        return Datatables::of($questions)
            ->editColumn('last_update', function ($data) {
                return CommonFunction::showExamAuditLog($data->last_update, $data->updated_by);
            })
            ->editColumn('serial', function ($data) {
                return $data->serial;
            })
            ->addColumn('action', function ($data) {
                $link = '<a target="_blank" href="' . url('exam/question-bank/view/' . Encryption::encodeId($data->id)) .
                    '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open-o"></i> Open</a>' . ' ';
                return $link;
            })
            ->addColumn('add', function ($questions) {
                return '<input name="question_id[]" type="checkbox" value="' . Encryption::encodeId($questions->id) . '" class="questions">';
            })
            ->make(true);
    }

    public function getAddedQuestionsByScheduleID($scheduleId) {
        $decodedScheduleId = Encryption::decodeId($scheduleId);

        DB::statement(DB::raw('set @serial=0'));
        $questions = ScheduleQuestion::leftJoin('ex_schedule as s','s.id','=','ex_schedule_question.schedule_id')
            ->leftJoin('ex_exam_type as et','et.id','=','ex_schedule_question.question_type')
            ->where('ex_schedule_question.schedule_id', $decodedScheduleId)
            ->where('ex_schedule_question.is_archive', 0)
            ->get([
                'ex_schedule_question.id',
                's.schedule_status',
                'et.exam_name',
                'ex_schedule_question.question_name',
                DB::raw('@serial  := @serial  + 1 AS serial')
            ]);


        return Datatables::of($questions)
            ->editColumn('question_type', function ($data) {
                if ($data->question_type == 1) {
                    return 'Pre-registrations';
                } elseif ($data->question_type == 2) {
                    return 'Registration';
                }
            })
            ->editColumn('last_update', function ($data) {
                return CommonFunction::showExamAuditLog($data->last_update, $data->updated_by);
            })
            ->editColumn('serial', function ($data) {
                return $data->serial;
            })
            ->addColumn('action', function ($data) {
                $link = '<a target="_blank" href="' . url('exam/question-bank/view/' . Encryption::encodeId($data->id)) .
                    '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open-o"></i> Open</a>' . ' ';
                return $link;
            })
            ->addColumn('remove', function ($data) {
                if($data->schedule_status == 'Initiated'){
                    return '<a href="/exam/schedule/remove-question/'.Encryption::encodeId($data->id).'" class="btn btn-xs btn-danger" title="Remove"
                                    onclick="return confirm('."'Are you sure that you want to remove the question from this schedule?'".')"><i class="fa fa-close"></i></a>';
                }else{
                    return '<a disabled="true" href="" class="btn btn-xs btn-default"><i class="fa fa-close"></i></a>';
                }

            })
            ->make(true);
    }

    public function addQuestionToSchedule(Request $request) {
        if (!ACL::getAccsessRight('Scheduling', 'A')) {
            die('You have no access right!');
        }
        try {
            $scheduleId = $request->get('schedule_id');
            $decodedScheduleId = Encryption::decodeId($scheduleId);
            $question_ids = $request->get('question_id');

            DB::beginTransaction();
            if(count($question_ids)>0){
                foreach ($question_ids as $_question_id) {
                    $questionId = Encryption::decodeId($_question_id);

                    $questionInfo = Question::find($questionId);
                    $scheduleQuestionData = new ScheduleQuestion();
                    $scheduleQuestionData->schedule_id = $decodedScheduleId;
                    $scheduleQuestionData->question_type = $questionInfo->question_type;
                    $scheduleQuestionData->question_name = $questionInfo->question_name;
                    $scheduleQuestionData->additional_part = $questionInfo->additional_part;
                    $scheduleQuestionData->question_id = $questionId;
                    $scheduleQuestionData->save();

                    $questionOptionInfos = QuestionOption::where('question_id', $questionId)->get();
                    $optionData=[];
                    foreach ($questionOptionInfos as $info) {
                        $optionData[] = array(
                            'schedule_question_id' => $scheduleQuestionData->id,
                            'option_name' => $info->option_name,
                            'option_no' => $info->option_no,
                            'is_correct_answer' => $info->is_correct_answer,
                        );
                    }
                    if (count($optionData) > 0)
                        ScheduleQuestionOption::insert($optionData);
                }
                DB::commit();
                Session::flash('successDown','Questions have been added successfully!');
                return redirect()->back();
            }else{
                Session::flash('errorDown','You didn\'t select any question');
                return redirect()->back();
            }

        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorDown','Something went wrong'.$e->getMessage());
            return redirect()->back();
        }
    }

    public function removeQuestionFromSchedule($scheduleQuestionId) {
        if (!ACL::getAccsessRight('Scheduling', 'D')) {
            die('You have no access right!');
        }
        $decodedScheduleQuestionId = Encryption::decodeId($scheduleQuestionId);
        $scheduleQuestionInfo = ScheduleQuestion::find($decodedScheduleQuestionId);
        $scheduleOptionInfo = ScheduleQuestionOption::where('schedule_question_id',$decodedScheduleQuestionId);
        try{
            if(!($this->hasInitiatedSchedule($scheduleQuestionInfo->schedule_id))){ //return false if schedule status '!initiated'
                Session::flash('errorDown', 'Schedule published! The question will be not remove from schedule.');
                return Redirect::back()->withInput();
            }
            $scheduleQuestionInfo->delete(); // delete question from schedule question table
            $scheduleOptionInfo->delete(); // delete options from schedule question option table
            Session::flash('successDown','Question has been removed successfully!');
            return Redirect::back()->withInput();

        }catch(\Exception $e){
            Session::flash('errorDown', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }

    }


    public function UsersListForSchedule($_id) {
        if (!ACL::getAccsessRight('Scheduling', 'V')) {
            die('You have no access right!');
        }
        return view('Exam::schedule.list-users', compact('_id'));
    }

    public function getUsersListForSchedule($_id) {
        $scheduleId = Encryption::decodeId($_id);

        DB::statement(DB::raw('set @serial=0'));
        $users = UsersModel::leftJoin('user_types', 'users.user_type', '=', 'user_types.id')
            ->leftJoin('ex_schedule_users', function($join) use($scheduleId) {
                $join->on('users.id', '=', 'ex_schedule_users.user_id');
                $join->on('ex_schedule_users.schedule_id', '=', DB::raw($scheduleId));
                $join->on('ex_schedule_users.is_archive', '=', DB::raw(0));
            })
            ->where('users.is_approved', 1)
            ->where('users.user_status', 'active')
            ->where('ex_schedule_users.user_id', '=', null)
            ->orderBy('serial', 'asc')
            ->get([
                'users.id as user_id',
                'users.user_full_name',
                'users.user_email',
                'user_types.type_name',
                DB::raw('@serial  := @serial  + 1 AS serial')
            ]);

        return Datatables::of($users)
            ->addColumn('add', function ($users) {
                return '<input name="user_id[]" type="checkbox" value="' . Encryption::encodeId($users->user_id) . '" class="users">';
            })
            ->make(true);
    }

    public function getSelectedUsersByScheduleID($scheduleId) {
        $decodedScheduleId = Encryption::decodeId($scheduleId);
        DB::statement(DB::raw('set @serial=0'));
        $users = ScheduleUsers::leftJoin('users as u', 'ex_schedule_users.user_id', '=', 'u.id')
            ->leftJoin('ex_schedule as s', 's.id', '=', 'ex_schedule_users.schedule_id')
            ->leftJoin('user_types as ut', 'u.user_type', '=', 'ut.id')
            ->where('ex_schedule_users.schedule_id', $decodedScheduleId)
            ->where('ex_schedule_users.is_archive', 0)
            ->get([
                'ex_schedule_users.id as schedule_user_id',
                's.schedule_status',
                'u.id as user_id',
                'u.user_full_name',
                'u.user_email',
                'ut.type_name',
                DB::raw('@serial  := @serial  + 1 AS serial')
            ]);

        return Datatables::of($users)
            ->addColumn('remove', function ($data) {
                if($data->schedule_status == 'Initiated'){
                    return '<a href="/exam/schedule/remove-user/'.Encryption::encodeId($data->schedule_user_id).'" class="btn btn-xs btn-danger" title="Remove"
                                onclick="return confirm('."'Are you sure that you want to remove the user from this schedule?'".')"><i class="fa fa-close"></i></a>';
                }
                return '<a disabled="true" href="" class="btn btn-xs btn-default"><i class="fa fa-close"></i></a>';
            })
            ->make(true);


    }

    public function addUsersToSchedule(Request $request) {
        if (!ACL::getAccsessRight('Scheduling', 'A')) {
            die('You have no access right!');
        }

        try {
            DB::beginTransaction();

            $scheduleId = $request->get('schedule_id');
            $decodedScheduleId = Encryption::decodeId($scheduleId);
            $userIds = $request->get('user_id');
            if(count($userIds)>0){
                foreach ($userIds as $userId) {
                    $decodedUserId = Encryption::decodeId($userId);

                    $entryData = array(
                        'schedule_id' => $decodedScheduleId,
                        'user_id' => $decodedUserId,
                        'is_archive' => 0,
                    );
                    ScheduleUsers::create($entryData);
                }

                DB::commit();

                Session::flash('successDown','Users have been added successfully!');
                return redirect()->back();
            }else{
                Session::flash('errorDown', 'You didn\'t select any user');
                return redirect()->back();
            }

        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorDown', CommonFunction::showErrorPublic($e->getMessage()));
            return redirect()->back();
        }
    }

    public function removeUsersFromSchedule($scheduleUserId) {

        if (!ACL::getAccsessRight('Scheduling', 'D')) {
            die('You have no access right!');
        }

        $decodedScheduleUserId = Encryption::decodeId($scheduleUserId);
        $scheduleUserInfo = ScheduleUsers::find($decodedScheduleUserId);

        try{
            if(!($this->hasInitiatedSchedule($scheduleUserInfo->schedule_id))){
                Session::flash('errorDown', 'Schedule published! The user will be not remove from schedule.');
                return Redirect::back()->withInput();
            }

            $scheduleUserInfo->delete();
            Session::flash('successDown','User has been removed successfully!');
            return Redirect::back()->withInput();

        }catch(\Exception $e){
            Session::flash('errorDown', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }

    }

    /* Ending of Schedule related functions */

    public function examList() {
        if (!ACL::getAccsessRight('ExamList', 'V')) {
            die('You have no access right!');
        }

        return view('Exam::exam.list');
    }

    public function getExamList() {
        DB::statement(DB::raw('set @serial=0'));
        $examData = ScheduleUsers::leftJoin('ex_schedule as s','ex_schedule_users.schedule_id','=','s.id')
            ->orderBy('ex_schedule_users.id', 'desc')
            ->where('s.is_archive', 0)
            ->where('s.schedule_status', '!=','Initiated')
            ->where('ex_schedule_users.user_id',Auth::user()->id)
            ->get([
                'ex_schedule_users.id as schedule_user_id',
                'ex_schedule_users.remarks',
                's.exam_type',
                's.question_title as exam_title',
                's.exam_available_from as exam_date',
                's.no_of_question_for_examinee as no_of_question',
                's.mark_per_question',
                DB::raw('@serial  := @serial  + 1 AS serial')
            ]);
        return Datatables::of($examData)
            ->editColumn('exam_date', function($data) {
                return date('d-M-Y', strtotime($data->exam_date));
            })
            ->addColumn('mark', function($data) {
                return $data->no_of_question * $data->mark_per_question;
            })
            ->addColumn('action', function ($data) {
                $link = '<a href="' . url('exam/exam-list/exam-open/' . Encryption::encodeId($data->schedule_user_id)) .
                    '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open-o"></i> Open</a>' . ' ';
                return $link;
            })
            ->make(true);
    }

    public function examOpen($ScheduleUserId) {
        if (!ACL::getAccsessRight('ExamList', 'V')) {
            die('You have no access right!');
        }
        $decodedScheduleUserId = Encryption::decodeId($ScheduleUserId);

        $scheduleUserInfo = ScheduleUsers::leftJoin('ex_schedule as s','s.id','=','ex_schedule_users.schedule_id')
            ->where('ex_schedule_users.id',$decodedScheduleUserId)
            ->first([
                'ex_schedule_users.id as exam_schedule_user_id',
                'ex_schedule_users.schedule_id',
                'ex_schedule_users.no_of_right_answer',
                'ex_schedule_users.no_of_wrong_answer',
                'ex_schedule_users.mark_obtain',
                'ex_schedule_users.remarks',
                's.schedule_status',
                's.question_title',
                's.duration',
                's.mark_per_question',
                's.no_of_question_for_examinee',
                's.exam_available_from',
                's.exam_disabled_at'
            ]);

        $currentTime = Carbon::now();
        $scheduleValidity = false;
        if($currentTime<$scheduleUserInfo->exam_disabled_at && $currentTime>$scheduleUserInfo->exam_available_from){
            $scheduleValidity = true;
        }

        $noOfQuestion = $scheduleUserInfo->no_of_question_for_examinee;
        $scheduleId = $scheduleUserInfo->schedule_id;

        $scheduleQuestions = ScheduleQuestion::leftJoin('ex_question as question','ex_schedule_question.question_id','=','question.id')
            ->where('ex_schedule_question.schedule_id',$scheduleId)
            ->where('ex_schedule_question.is_archive',0)
            ->orderBy(DB::Raw('RAND()'))->take($noOfQuestion)
            ->get(['ex_schedule_question.*']);

        $scheduleQuestionOptions = array();
        foreach($scheduleQuestions as $scheduleQuestion){
            $scheduleQuestionOptions[$scheduleQuestion->id] = ScheduleQuestionOption::where('schedule_question_id',$scheduleQuestion->id)
                ->get([
                    'id',
                    'option_name',
                    'option_no',
                    'is_correct_answer'
                ]);
        }


        return view('Exam::exam.open',compact('scheduleQuestions', 'scheduleQuestionOptions','scheduleUserInfo','scheduleValidity'));
    }

    public function examStartTime(){
        Session::put('xm_start',Carbon::now());
        return response()->json([
            'success' => true,
            'status' => 'session store.'
        ]);
    }

    public function examSubmit($scheduleUserId, Request $request){
        if (!ACL::getAccsessRight('ExamList', 'A')) {
            die('You have no access right!');
        }
        $decodedScheduleUserId = Encryption::decodeId($scheduleUserId);

        $examScheduleDuration = ScheduleUsers::leftJoin('ex_schedule','ex_schedule.id','=','ex_schedule_users.schedule_id')
            ->where('ex_schedule_users.id',$decodedScheduleUserId)
            ->first(['ex_schedule.duration']);

        $scheduleDuration = explode(':',$examScheduleDuration->duration);
        $sdHour = $scheduleDuration[0];
        $sdMinutes = $scheduleDuration[1];
        $sdSeconds = $scheduleDuration[2];

        $scheduleMiliSecond = ($sdHour*60*60*1000)+($sdMinutes*60*1000)+($sdSeconds*1000);


        $xmStart = Session::get('xm_start');
        $durationInSeconds = Carbon::now()->diffInSeconds($xmStart);
        $xmInMillisecondLessFiveSecond = ($durationInSeconds-5)*1000;

        if($scheduleMiliSecond<$xmInMillisecondLessFiveSecond){
            Session::forget('xm_start');
            Session::flash('error','You couldn\'t finish you exam in time.');
            return redirect()->back();
        }


        $scheduleQuestions = $request->input('question'); // all question ids (selected + no touch)
        $scheduleQuestionOptions = $request->input('option');  // all option id of selected question

        try{
            DB::beginTransaction();

            $ansIds = $this->examEvaluation($decodedScheduleUserId,$scheduleQuestions,$scheduleQuestionOptions);
            foreach($scheduleQuestions as $scheduleQuestionId){
                $userAnswerQuestion = new UserAnswerQuestion;
                $userAnswerQuestion->schedule_user_id = $decodedScheduleUserId;
                $userAnswerQuestion->schedule_question_id = $scheduleQuestionId;
                $userAnswerQuestion->answer_status = 0;

                if(in_array($scheduleQuestionId,$ansIds['right'])){
                    $userAnswerQuestion->answer_status = 1;
                }else{
                    if(in_array($scheduleQuestionId,$ansIds['wrong'])){
                        $userAnswerQuestion->answer_status = -1;
                    }else{
                        $userAnswerQuestion->answer_status = 0;
                    }
                }
                $userAnswerQuestion->save();  // all question store which arrived for examinee.
                if(isset($scheduleQuestionOptions[$scheduleQuestionId])){
                    $selectedOptions = $scheduleQuestionOptions[$scheduleQuestionId]; // selected options of schedule question
                    foreach($selectedOptions as $scheduleQuestionOptionId){
                        $userAnsOptionArray[] = [
                            'user_answer_question_id'=>$userAnswerQuestion->id,
                            'selected_schedule_question_option_id'=>$scheduleQuestionOptionId,
                            'created_by'=>CommonFunction::getUserId(),
                            'updated_by'=>CommonFunction::getUserId(),
                            'updated_at'=>Carbon::now()
                        ];
                    }
                }
            }

            if(count($scheduleQuestionOptions)>0){
                UserAnswerOption::insert($userAnsOptionArray);
            }



            DB::commit();
            Session::flash('success','Your exam is successfully submitted');
            return redirect('/exam/exam-list/list');
        }catch(\Exception $e){
            dd($e->getMessage());
            DB::rollback();
            Session::flash('error','Something went wrong.');
        }
    }

    public function examEvaluation($decodedScheduleUserId,$scheduleQuestions,$selectedOptions){
        if (!ACL::getAccsessRight('ExamList', 'E')) {
            die('You have no access right!');
        }

        $markPerQuestion = ScheduleUsers::leftJoin('ex_schedule','ex_schedule.id','=','ex_schedule_users.schedule_id')
            ->where('ex_schedule_users.id',$decodedScheduleUserId)
            ->pluck('mark_per_question');
        $totalQuestions = count($scheduleQuestions);
        $totalExamMarks = $totalQuestions * $markPerQuestion;
        $examScheduleUserInfo = ScheduleUsers::find($decodedScheduleUserId);

        $rightAnswers = [];
        $wrongAnswers = [];

        if(count($selectedOptions) > 0){
            $correctOptionIds = ScheduleQuestionOption::select('schedule_question_id', DB::raw('group_concat(ex_schedule_question_option.id) AS correctOptionIds'))
                ->whereIn('schedule_question_id', $scheduleQuestions)
                ->where('is_correct_answer', 1)
                ->groupBy('schedule_question_id')
                ->get();
            $correctAnswers = [];
            foreach($correctOptionIds as $value){
                $correctAnswers[$value->schedule_question_id] = explode(',',$value->correctOptionIds);
            }
            foreach($selectedOptions as $scheduleQuestionId => $submittedAnswer){

                if(count($submittedAnswer) == count($correctAnswers[$scheduleQuestionId])){
                    $flag=1;
                    foreach($correctAnswers[$scheduleQuestionId] as $ansValue){
                        if(in_array($ansValue,$submittedAnswer)){
                            continue;
                        }else{
                            $flag=0;break;
                        }
                    }
                    if($flag==1) $rightAnswers[] = $scheduleQuestionId;
                    else $wrongAnswers[] = $scheduleQuestionId;
                }else{
                    $wrongAnswers[] = $scheduleQuestionId;
                }
            }
            $totalRightAnswers = count($rightAnswers);
            $totalWrongAnswers = count($wrongAnswers);
            $totalNoTouch = $totalQuestions - ($totalRightAnswers+$totalWrongAnswers);
            $markObtain = $totalRightAnswers * $markPerQuestion;


            $examScheduleUserInfo->no_of_right_answer = $totalRightAnswers;
            $examScheduleUserInfo->no_of_wrong_answer = $totalWrongAnswers;
            $examScheduleUserInfo->no_of_not_touch = $totalNoTouch;
            $examScheduleUserInfo->mark_obtain = $markObtain;

            $markPercentage = ($markObtain/$totalExamMarks)*100;
            if($markPercentage<40){
                $examScheduleUserInfo->remarks = 'Failed';
            }else{
                $examScheduleUserInfo->remarks = 'Passed';
            }
        }else{
            $examScheduleUserInfo->no_of_right_answer = 0;
            $examScheduleUserInfo->no_of_wrong_answer = 0;
            $examScheduleUserInfo->no_of_not_touch = $totalQuestions;
            $examScheduleUserInfo->mark_obtain = 0;
            $examScheduleUserInfo->remarks = 'Failed';
        }
        $examScheduleUserInfo->save();
        $ans['right'] = $rightAnswers;
        $ans['wrong'] = $wrongAnswers;
        return $ans;
    }

    /* Ending Exam method parts */

    public function resultList() {
        if (!ACL::getAccsessRight('ResultProcess', 'V')) {
            die('You have no access right!');
        }
        return view('Exam::result.list');
    }

    public function getResultList() {
        DB::statement(DB::raw('set @serial=0'));
        $examData = Schedule::orderBy('ex_schedule.id', 'desc')
            ->where('ex_schedule.is_archive', 0)
            ->whereIn('ex_schedule.schedule_status',['Exam Taken','Result Published'])
            ->get([
                'id',
                'exam_type',
                'schedule_status',
                'question_title as exam_title',
                'exam_available_from as exam_date',
                'no_of_question_for_examinee as no_of_question',
                'mark_per_question',
                DB::raw('@serial  := @serial  + 1 AS serial')
            ]);

        return Datatables::of($examData)
            ->editColumn('exam_date',function ($data){
                $date = date('d-M-Y',strtotime($data->exam_date));
                return $date;
            })
            ->addColumn('mark', function($data) {
                return $data->no_of_question * $data->mark_per_question;
            })
            ->addColumn('action', function ($data) {
                $link = '<a href="' . url('exam/result/open/' . Encryption::encodeId($data->id)) .
                    '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open-o"></i> Open</a>' . ' ';
                return $link;
            })
            ->make(true);
    }

    public function resultOpen($scheduleId) {
        if (!ACL::getAccsessRight('ResultProcess', 'V')) {
            die('You have no access right!');
        }
        $decodedScheduleId = Encryption::decodeId($scheduleId);

        $examInfo = Schedule::leftJoin('ex_schedule_users as su','su.schedule_id','=','ex_schedule.id')
            ->leftJoin('ex_exam_type as et','et.id','=','ex_schedule.question_type')
            ->where('ex_schedule.id',$decodedScheduleId)
            ->first([
                'ex_schedule.id as schedule_id',
                'ex_schedule.schedule_status',
                'et.exam_name',
                'ex_schedule.mark_per_question',
                'ex_schedule.no_of_question_for_examinee',
                'ex_schedule.exam_type',
                'ex_schedule.question_title',
                'ex_schedule.exam_available_from',
                'ex_schedule.exam_disabled_at',
                'ex_schedule.duration',
                DB::raw("count(*) as candidate"),
                DB::raw("sum(if(su.remarks != 'Not Participate',1,0)) as participate"),
                DB::raw("sum(if(su.remarks = 'Failed',1,0)) as failed"),
                DB::raw("sum(if(su.remarks = 'Passed',1,0)) as passed")
            ]);

        $viewStatus = 'off';
        $heading = trans('messages.view_result');
        return view("Exam::result.open", compact('heading', 'viewStatus','examInfo'));
    }



    public function getExamineeList($scheduleId) {
        $decodedScheduleId = Encryption::decodeId($scheduleId);

        DB::statement(DB::raw('set @serial=0'));
        $examineeInfo = ScheduleUsers::leftJoin('users','users.id','=','ex_schedule_users.user_id')
            ->orderBy('ex_schedule_users.mark_obtain', 'desc')
            ->where('ex_schedule_users.schedule_id', $decodedScheduleId)
            ->where('ex_schedule_users.remarks', '!=','Not Participate')
            ->where('ex_schedule_users.is_archive', 0)
            ->get([
                'users.user_full_name as examinee_name',
                'ex_schedule_users.id as schedule_user_id',
                'ex_schedule_users.no_of_right_answer as right_answer',
                'ex_schedule_users.no_of_wrong_answer as wrong_answer',
                'ex_schedule_users.mark_obtain',
                'ex_schedule_users.remarks',
                DB::raw('@serial  := @serial  + 1 AS serial')
            ]);

        return Datatables::of($examineeInfo)
            ->addColumn('action', function ($data) {
                $link = '<a href="/exam/result/examinee-list/view/'.Encryption::encodeId($data->schedule_user_id).'" class="btn btn-xs btn-primary"><i class="fa fa-folder-open-o"></i> Open</a>';
                return $link;
            })
            ->make(true);
    }


    public function viewExamineeExam($scheduleUserId){
        if (!ACL::getAccsessRight('ResultProcess', 'V')) {
            die('You have no access right!');
        }

        $decodedScheduleUserId = Encryption::decodeId($scheduleUserId);
        $scheduleUserInfo = ScheduleUsers::leftJoin('users','ex_schedule_users.user_id','=','users.id')
            ->leftJoin('ex_schedule','ex_schedule.id','=','ex_schedule_users.schedule_id')
            ->leftJoin('user_types','user_types.id','=','users.user_type')
            ->where('ex_schedule_users.id',$decodedScheduleUserId)
            ->first([
                'ex_schedule_users.*',
                'user_types.type_name',
                'ex_schedule.question_title',
                'ex_schedule.mark_per_question',
                'ex_schedule.no_of_question_for_examinee',
                'ex_schedule.exam_available_from',
                'ex_schedule.exam_disabled_at',
                'users.user_full_name'
            ]);

        $examQuestions = UserAnswerQuestion::leftJoin('ex_schedule_question as sq','ex_user_answer_question.schedule_question_id','=','sq.id')
            ->where('ex_user_answer_question.schedule_user_id',$decodedScheduleUserId)
            ->get();


        $examQuestionOptions = array();
        $userAnsweredOptions = array();
        foreach($examQuestions as $examQuestion){
            $examQuestionOptions[$examQuestion->schedule_question_id] = ScheduleQuestionOption::where('schedule_question_id',$examQuestion->schedule_question_id)
                ->get([
                    'id',
                    'option_name',
                    'option_no',
                    'is_correct_answer'
                ]);


            $userAnsweredOptions[$examQuestion->schedule_question_id] = UserAnswerQuestion::leftJoin('ex_user_answer_option as uao','ex_user_answer_question.id','=','uao.user_answer_question_id')
                ->where('schedule_question_id',$examQuestion->schedule_question_id)
                ->where('ex_user_answer_question.schedule_user_id',$decodedScheduleUserId)
                ->groupBy('schedule_question_id')
                ->first([
                    'schedule_question_id',
                    'selected_schedule_question_option_id',
                    DB::raw('group_concat(selected_schedule_question_option_id) as given_answers')
                ]);

        }

        $heading = trans('messages.view_examinee_result');
        return view("Exam::result.individual-open", compact('heading','correctAnswers','examQuestions','examQuestionOptions','userAnsweredOptions','scheduleUserInfo'));
    }

    public function publishResult($scheduleId){
        if (!ACL::getAccsessRight('ResultProcess', 'A')) {
            die('You have no access right!');
        }

        try{
            $decodedScheduleId = Encryption::decodeId($scheduleId);
            $schedule = Schedule::find($decodedScheduleId);
            $schedule->schedule_status = 'Result Published';
            DB::beginTransaction();
            $schedule->save();
            $this->sendingMailAfterResultPublish($decodedScheduleId);
            DB::commit();
            Session::flash('success','Result published successfully.');
            return redirect()->back();
        }catch(\Exception $e){
            DB::rollback();
            Session::flash('error','Something went wrong '.$e->getMessage());
            return redirect()->back();
        }
    }
    /* Ending of ExamList related functions */




    public function sendingMailAfterSchedulePublish($decodedScheduleId){
        if (!ACL::getAccsessRight('ResultProcess', 'A')) {
            die('You have no access right!');
        }

        $candidateExaminees =  ScheduleUsers::leftJoin('users','users.id','=','ex_schedule_users.user_id')
            ->leftJoin('ex_schedule as schedule','schedule.id','=','ex_schedule_users.schedule_id')
            ->where('ex_schedule_users.schedule_id',$decodedScheduleId)
            ->get([
                'ex_schedule_users.id as schedule_user_id',
                'schedule.exam_available_from',
                'users.user_email',
                'users.user_full_name',
                'users.user_phone'
            ]);

        if(count($candidateExaminees)>0){
            $dataArray = [];
            foreach($candidateExaminees as $examinee){
                $dateNow = date('d/m/Y');
                $subject = 'Exam schedule published';
                $body_msg = '<span style="color:#000;text-align:justify;">';
                $body_msg .= 'Dear User, <br/> You are allowed to participate in exam at '.date('d-M-Y',strtotime($examinee->exam_available_from));
                $body_msg .= '<br/>Thank you';
                $body_msg .= '</span>';
                $header = 'Exam schedule publish';
                $applicant = '<span>' . $examinee->user_full_name . '</span><br/>';

                $email_content = view("examEmail.email-template", compact('header', 'dateNow', 'subject', 'body_msg', 'applicant'))->render();
                $sms_content = "Dear examinee you are allow to sit in exam.";

                $dataArray[] = array(
                    'service_id'=>20,
                    'app_id'=>$examinee->schedule_user_id,
                    'email_content'=>$email_content,
                    'email_to'=>$examinee->user_email,
                    'email_cc'=>'hasibkamal.cse@gmail.com',
                    'sms_content'=>$sms_content,
                    'sms_to'=>$examinee->user_phone,
                    'attachment'=>'',
                    'secret_key'=>'',
                    'pdf_type'=>'',
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                );

            }
            $mailArray = array_chunk($dataArray, 50);
            foreach($mailArray as $mailData){
                ExamEmailQueue::insert($mailData);
            }

        }

    }


    public function sendingMailAfterExamTaken($decodedScheduleId){
        if (!ACL::getAccsessRight('ResultProcess', 'A')) {
            die('You have no access right!');
        }

        $absenceParticipateExaminees =  ScheduleUsers::leftJoin('users','users.id','=','ex_schedule_users.user_id')
            ->where('ex_schedule_users.schedule_id',$decodedScheduleId)
            ->get([
                'ex_schedule_users.id as schedule_user_id',
                'ex_schedule_users.remarks',
                'users.user_email',
                'users.user_full_name',
                'users.user_phone'
            ]);

        if(count($absenceParticipateExaminees)>0){
            $dataArray = [];
            foreach($absenceParticipateExaminees as $examinee){
                $dateNow = date('d/m/Y');
                $subject = 'Exam taken';
                if($examinee->remarks == 'Not Participate'){
                    $body_msg = '<span style="color:#000;text-align:justify;">';
                    $body_msg .= 'Dear candidate,<br/> You didn\'t participate in exam.';
                    $body_msg .= '<br/>Thank you';
                    $body_msg .= '</span>';
                    $sms_content= "You didn't participate in exam";
                }else{
                    $body_msg = '<span style="color:#000;text-align:justify;">';
                    $body_msg .= 'Dear Examinee, <br/>Thank you for participate in exam. You will be notify after publishing your result.';
                    $body_msg .= '<br/>Thank you';
                    $body_msg .= '</span>';
                    $sms_content= "Thank you for participate result will publish soon";
                }

                $header = 'Exam taken';
                $applicant = '<span>' . $examinee->user_full_name . '</span><br/>';

                $email_content = view("examEmail.email-template", compact('header', 'dateNow', 'subject', 'body_msg', 'applicant'))->render();


                $dataArray[] = array(
                    'service_id'=>20,
                    'app_id'=>$examinee->schedule_user_id,
                    'email_content'=>$email_content,
                    'email_to'=>$examinee->user_email,
                    'email_cc'=>'hasibkamal.cse@gmail.com',
                    'sms_content'=>$sms_content,
                    'sms_to'=>$examinee->user_phone,
                    'attachment'=>'',
                    'secret_key'=>'',
                    'pdf_type'=>'',
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                );

            }
            $mailArray = array_chunk($dataArray, 50);
            foreach($mailArray as $mailData){
                ExamEmailQueue::insert($mailData);
            }
        }

    }



    public function sendingMailAfterResultPublish($decodedScheduleId){
        if (!ACL::getAccsessRight('ResultProcess', 'A')) {
            die('You have no access right!');
        }

        $participateExaminees =  ScheduleUsers::leftJoin('users','users.id','=','ex_schedule_users.user_id')
            ->where('ex_schedule_users.remarks','!=','Not Participate')
            ->where('ex_schedule_users.schedule_id',$decodedScheduleId)
            ->get([
                'ex_schedule_users.id as schedule_user_id',
                'ex_schedule_users.remarks',
                'users.user_email',
                'users.user_full_name',
                'users.user_phone'
            ]);

        if(count($participateExaminees)>0){
            $dataArray = [];
            foreach($participateExaminees as $examinee){
                $dateNow = date('d/m/Y');
                $subject = 'Exam result published';
                $body_msg = '<span style="color:#000;text-align:justify;">';
                $body_msg .= 'Dear Examinee, <br/>Your exam result has been published';
                $body_msg .= '<br/>Thank you';
                $body_msg .= '</span>';
                $header = 'Exam result publish';
                $applicant = '<span>' . $examinee->user_full_name . '</span><br/>';

                $email_content = view("examEmail.email-template", compact('header', 'dateNow', 'subject', 'body_msg', 'applicant'))->render();
                $sms_content = "Your exam result has been published";


                $dataArray[] = array(
                    'service_id'=>20,
                    'app_id'=>$examinee->schedule_user_id,
                    'email_content'=>$email_content,
                    'email_to'=>$examinee->user_email,
                    'email_cc'=>'hasibkamal.cse@gmail.com',
                    'sms_content'=>$sms_content,
                    'sms_to'=>$examinee->user_phone,
                    'attachment'=>'',
                    'secret_key'=>'',
                    'pdf_type'=>'',
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                );

            }
            $mailArray = array_chunk($dataArray, 50);
            foreach($mailArray as $mailData){
                ExamEmailQueue::insert($mailData);
            }
        }
    }



    /*     * *******************End of Controller******************** */
}
