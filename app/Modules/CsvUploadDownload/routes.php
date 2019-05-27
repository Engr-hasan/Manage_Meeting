<?php

Route::group(array('module' => 'CsvUploadDownload','middleware' => ['auth','XssProtection'],
    'namespace' => 'App\Modules\CsvUploadDownload\Controllers'), function() {

    Route::get('csv-upload/list', 'CsvUploadDownloadController@index');
    Route::post('csv-upload/list/get-list', 'CsvUploadDownloadController@csvList');
    Route::get('csv-upload/import/', "CsvUploadDownloadController@importRequest");

    Route::post('csv-upload/upload-csv-file', 'CsvUploadDownloadController@uploadCsvFile');
    Route::get('csv-upload/request/{path}/{table}', 'CsvUploadDownloadController@previewDataFromCsv');

    Route::post('csv-upload/alter-table/add-field','CsvUploadDownloadController@addFieldToTable');
    Route::post('do-request/save-data/','CsvUploadDownloadController@saveDataFromCsv');
    Route::post('csv-upload/new-table/create-table','CsvUploadDownloadController@createNewTable');

//*****************************************End of Route Group********************************************
});
