<?php

Route::group(array('module' => 'ProcessPath','middleware' => ['auth','XssProtection'], 'namespace' => 'App\Modules\ProcessPath\Controllers'), function() {

//    List of all process
    Route::get('process/list', "ProcessPathController@processListById");
    Route::get('process/list/{process_id}', "ProcessPathController@processListById");

    Route::get('process/view/{id}', "ProcessPathController@viewApplication");
    Route::post('process-path/get-desk-by-status', "ProcessPathController@getDeskByStatus");
    Route::post('process-path/batch-process-update', "ProcessPathController@updateProcess");
    Route::get('process-path/check-process-validity', "ProcessPathController@checkApplicationValidity");
    Route::post('process-path/ajax/{param}', 'ProcessPathController@ajaxRequest');
//    Route::get('process/get-list/{process_type_id}/{status}',[
    Route::get('process/get-list/{status?}/{desk?}',[
            'as' => 'process.getList',
            'uses' => 'ProcessPathController@getList'
        ]);
//    Route::get('process/bard-meting/{status?}/{desk?}',[
//        'as' => 'process.boardMeting',
//        'uses' => 'ProcessPathController@boardMeting'
//    ]);
//    Route::get('process/boardMeting/{status?}/{desk?}',[
//        'as' => 'process.boardMeting',
//        'uses' => 'ProcessPathController@boardMeting'
//    ]);
//    Route::get('process/boardMeting', "ProcessPathController@boardMeting");
    Route::get('process/set-process-type',[
        'as' => 'process.setProcessType',
        'uses' => 'ProcessPathController@setProcessType'
    ]);
    Route::get('process/search-process-type',[
        'as' => 'process.searchProcessType',
        'uses' => 'ProcessPathController@searchProcessType'
    ]);

    Route::resource('ProcessPath', 'ProcessPathController');
});