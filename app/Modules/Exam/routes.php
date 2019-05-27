<?php

Route::group(array('module' => 'Exam', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'], 'namespace' => 'App\Modules\Exam\Controllers'), function() {

    /* Question List */
    Route::get("/exam/question-bank/list", "ExamController@questionList");
    Route::post("exam/question-bank/get-question-list", "ExamController@getQuestionList");

    /* Question add, edit, save */
    Route::get("exam/question-bank/create", "ExamController@addQuestion");
    Route::get("exam/question-bank/edit/{id}", "ExamController@editQuestion");
    Route::post("exam/question-bank/store", "ExamController@storeQuestion");
    Route::post("exam/question-bank/update/{id}", "ExamController@updateQuestion");
    Route::get("exam/question-bank/view/{id}", "ExamController@viewQuestion");
    Route::get("exam/question-bank/delete/{id}", "ExamController@deleteQuestion");

    Route::get('exam/question-bank/download-question-info-excel/', 'ExamController@downloadQuestionInfo');
    Route::post('exam/question-bank/upload-question-info/', 'ExamController@uploadQuestionInfo');
    Route::get('exam/question-bank/question-verification/{filePath}', 'ExamController@uploadedQuestionsVerification');
    Route::post('exam/question-bank/save-question-excel', 'ExamController@saveQuestionExcel');

    /* Schedule list add, edit, save, view */
    Route::get("exam/schedule/list", "ExamController@scheduleList");
    Route::post("exam/schedule/get-list", "ExamController@getScheduleList");

    Route::get("exam/schedule/create", "ExamController@addSchedule");
    Route::get("exam/schedule/edit/{id}", "ExamController@editSchedule");
    Route::post("exam/schedule/store", "ExamController@storeSchedule");
    Route::patch("exam/schedule/update/{id}", "ExamController@updateSchedule");
    Route::get("exam/schedule/view/{id}", "ExamController@viewSchedule");
    Route::get("exam/schedule/publish/{id}", "ExamController@publishSchedule");
    Route::get("exam/schedule/exam-taken/{id}", "ExamController@scheduleExamTaken");

    /* Get Question List for adding in Schedule */
    Route::get("exam/schedule/question-list/{id}", "ExamController@QuestionListForSchedule");
    Route::post("/exam/schedule/get-question-list/{id}", "ExamController@getQuestionListForSchedule");
    Route::post("/exam/schedule/get-added-questions/{id}", "ExamController@getAddedQuestionsByScheduleID");

    Route::post("exam/schedule/add-questions/", "ExamController@addQuestionToSchedule");
    Route::get("exam/schedule/remove-question/{id}", "ExamController@removeQuestionFromSchedule");
//    Route::post("exam/schedule/remove-question/", "ExamController@removeQuestionFromSchedule");

    /* Get User List for adding in Schedule */
    Route::get("exam/schedule/users-list/{id}", "ExamController@UsersListForSchedule");
    Route::post("/exam/schedule/get-users-list/{id}", "ExamController@getUsersListForSchedule");
    Route::post("/exam/schedule/get-selected-users/{id}", "ExamController@getSelectedUsersByScheduleID");

    Route::post("exam/schedule/add-users/", "ExamController@addUsersToSchedule");
    Route::get("/exam/schedule/remove-user/{id}", "ExamController@removeUsersFromSchedule");
//    Route::post("exam/schedule/remove-user/", "ExamController@removeUsersFromSchedule");

    /* ExamList list, add, edit, save */
    Route::get("exam/exam-list/list", "ExamController@examList");
    Route::post("exam/exam-list/get-exam-list", "ExamController@getExamList");
    Route::get("exam/exam-list/exam-open/{id}", "ExamController@examOpen");
    Route::post("exam/exam-list/exam-submit/{id}", "ExamController@examSubmit");
    Route::get("/exam/exam-list/exam-start","ExamController@examStartTime");

    /* Result Process Route */
    Route::get("exam/result/list", "ExamController@resultList");
    Route::post("exam/result/get-result-list", "ExamController@getResultList");
    Route::get("exam/result/open/{id}", "ExamController@resultOpen");
    Route::post("exam/result/examinee-list/{id}", "ExamController@getExamineeList");
    Route::get("/exam/result/examinee-list/view/{id}", "ExamController@viewExamineeExam");
    Route::get("/exam/result/publish/{id}","ExamController@publishResult");

    // *********************************End of Route group****************************** */
});
