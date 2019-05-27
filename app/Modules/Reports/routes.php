<?php

Route::group(array('module' => 'Reports', 'middleware' => ['auth','XssProtection'], 'namespace' => 'App\Modules\Reports\Controllers'), function() {

    Route::get('/reports', "ReportsController@index");

    Route::get('/reports/create', "ReportsController@create");
    Route::get('/reports/edit/{id}', "ReportsController@edit");

    Route::get('/reports/show/{id}', "ReportsController@show");
    Route::get('/reports/view/{id}', "ReportsController@view");

    Route::get('/reports/add-to-favourite/{id}', "ReportsController@addToFavourite");
    Route::get('/reports/remove-from-favourite/{id}', "ReportsController@removeFavourite");

    Route::post('/reports/verify', "ReportsController@reportsVerify");
    Route::get('/reports/tables', "ReportsController@showTables");

    Route::post('/reports/show-report/{report_id}', "ReportsController@showReport");
    Route::get('/reports/show-report/{report_id}', "ReportsController@showReport");


    Route::patch('/reports/store', "ReportsController@store");
    Route::patch('/reports/update/{id}', "ReportsController@update");
    
});

