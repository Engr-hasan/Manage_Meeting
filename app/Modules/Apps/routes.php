<?php

Route::group(array('module' => 'Apps', 'middleware' => ['auth'], 'namespace' => 'App\Modules\Apps\Controllers'), function() {

    Route::get('application', 'AppsController@index');
    Route::get('application/create-form', 'AppsController@createApplication');
    Route::get('application/view/{id}', 'AppsController@appFormView');
    Route::get('application/edit/{id}', 'AppsController@appFormEdit');
    Route::post('application/store', 'AppsController@appStore');

    ### upload document of an application
    Route::any('application/upload-document', 'AppsController@uploadDocument');

    Route::patch('application/update-batch', "AppsController@updateBatch");

    Route::get('application/preview', 'AppsController@preview');

    Route::get('application/get-apps-list', 'AppsController@getAppsList');

    Route::post('application/ajax/{param}', 'AppsController@ajaxRequest');

    ### advance search
    Route::post('application/search-result', 'AppsController@searchResult');

    Route::resource('apps', 'AppsController');
});	