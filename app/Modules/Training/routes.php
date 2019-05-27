<?php
Route::group(array('module' => 'Training', 'middleware' => ['auth','XssProtection'], 'namespace' => 'App\Modules\Training\Controllers'), function() {

//    Routes of Training Material
    Route::post('Training/store', "TrainingController@store");
    Route::get('training/view/{id}', "TrainingController@show");
    Route::get('Training/edit/{id}', "TrainingController@edit");
    Route::patch('training/update/{id}', "TrainingController@update");

    Route::get('Training/resource/{id}', "TrainingController@resource");
    Route::post('Training/resource-store/{id}', "TrainingController@storeResource");
    Route::get('training-resource/edit/{id}', "TrainingController@resourceEdit");
    Route::post('training-resource/update/{id}', "TrainingController@resourceUpdate");
    Route::post('training/get-training-resource-data', "TrainingController@getResourceData");
    Route::get('training-resource/publish/{id}', "TrainingController@resourcePublish");
    Route::get('training-resource/unpublish/{id}', "TrainingController@resourceUnpublish");
    Route::get('training-resource/public/{id}', "TrainingController@resourcePublic");
    Route::get('training-resource/remove//{id}', "TrainingController@resourceRemove");
    Route::post('training/participant-training-resource-data', "TrainingController@getParticipantResource");
    Route::get('training-resource/embedded/{id}', "TrainingController@embeddedResource");

    Route::get('Training/get-training-details-data', "TrainingController@getTrainingDetailsData");
    Route::get('Training/material-list', "TrainingController@trainingList");
    Route::get('Training/get-training-list', "TrainingController@getTrainingData");
    Route::post('Training/apply-for-training', "TrainingController@applyForTraining");
    Route::get('training/decline-from-training/{participant_id}', "TrainingController@declineFromTraining");
    Route::get('training/verify-training-applicant/{participant_id}', "TrainingController@verifyTrainingApplicant");
    Route::post('training/present-participant', "TrainingController@presentParticipant");
    Route::post('training/absent-participant', "TrainingController@absentParticipant");

//    Routes of public training application
    Route::post('training-public/apply/{id}', "TrainingController@applyPublicTraining");



    
//    Routes of Training Schedule
    Route::get('training/schedule', 'TrainingController@scheduleList');
    Route::get('training/create-schedule', 'TrainingController@createSchedule');
    Route::post('training/store-schedule', "TrainingController@storeSchedule");
    Route::get('training-schedule/view/{id}', "TrainingController@viewSchedule");
    Route::get('training-schedule/edit/{id}', "TrainingController@editSchedule");
    Route::patch('training-schedule/update/{id}', "TrainingController@updateSchedule");

    ### Routes of training participants
    //Route::post('training/get-trainee-list', "TrainingController@getTraineeList");
    Route::post('training/get-trainee-list', "TrainingController@getNewTraineeList");
    Route::post('training/schedule-list-for-assign', "TrainingController@getScheduleListForAssign");
    Route::post('training/schedule-assign-for-selected-participants', "TrainingController@assignSchedule");
    Route::post('training/ajax-certificate-letter', "TrainingController@ajaxCertificateLetter");
    Route::post('training/ajax-certificate-feedback', "TrainingController@ajaxCertificateFeedback");
    Route::post('training/update-download-panel', "TrainingController@updateDownloadPanel");
    Route::get('training-participant/view/{id}', "TrainingController@participantView");

    Route::post('training/ajax-tr-certificate-letter', "TrainingController@ajaxTrCertificateLetter");
    Route::post('training/ajax-tr-certificate-feedback', "TrainingController@ajaxTrCertificateFeedback");
    Route::post('training/update-tr-download-panel', "TrainingController@updateTrDownloadPanel");


    Route::post('training/get-training-material-schedule-data', "TrainingController@getTrainingMaterialScheduleData");
    Route::post('training/get-training-schedule-data', "TrainingController@getTrainingScheduleData");

    ### Download trainee list as CSV
    Route::get('download/trainee-list/{id}', "TrainingController@downloadTraineeList");



    Route::resource('Training', "TrainingController");
    Route::get('training/correction-licence-num', "TrainingController@correctionLicenceNum");
    Route::get('training/fill-participant-info', "TrainingController@fillParticipantInfo");



});