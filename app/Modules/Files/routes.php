<?php

Route::group(array('module' => 'Files', 'namespace' => 'App\Modules\Files\Controllers'), function() {

    Route::post('files/store/{type}', 'FilesController@store');

    Route::resource('files', 'FilesController');
    
});	