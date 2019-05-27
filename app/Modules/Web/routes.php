<?php

Route::group(array('module' => 'Web', 'namespace' => 'App\Modules\Web\Controllers'), function() {

    Route::resource('Web', 'WebController');
    Route::get('web/get-report-object/{type}','WebController@loadDashboardObjectsChart');
    //    public training Schedule
    Route::get('training-public/get-training-public-schedule', "WebController@getTrainingPublicSchedule");
    Route::post('training-public/application-form', "WebController@applyForm");
    Route::post('apply', "WebController@applyPublicTraining");

//    Public Training Resource
    Route::get('training-resource-public/embedded/{id}', "WebController@publicTrainingVideo");
    
});	