<?php

Route::group(array('module' => 'LoanLocator', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'],
    'namespace' => 'App\Modules\LoanLocator\Controllers'), function () {

    //New desk User Registraion Route
    Route::get('loan-locator/add', 'LoanLocatorController@applicationForm');
    Route::post('loan-locator/add', 'LoanLocatorController@appStore');

    Route::get('loan-locator/get-branch-by-bank', 'LoanLocatorController@getBranch');
    //Route for Agency List Route
//    Route::get('space-allocation/list', 'spaceAllocationController@viewList');
//    Route::post('space-allocation/get-list', 'spaceAllocationController@getList');


    //Route for Status wise Agency List Route
    Route::get('space-allocation/list-status-wise/{status_id}', 'SpaceAllocationController@viewStatusWiseAppList');
    Route::post('space-allocation/get-list-by-status/', 'SpaceAllocationController@getListByStatus');
    //Route for Upload Document
    Route::any('loan-locator/upload-document', 'LoanLocatorController@uploadDocument');

    //Loan Locator view/edit and application download route
    Route::get('loan-locator/view/{id}', "LoanLocatorController@applicationViewEdit");
    Route::get('loan-locator/view/{id}/board-meeting', "LoanLocatorController@applicationViewEdit");
    Route::get('space-allocation/application/{openMode}/{id}', 'SpaceAllocationController@applicationViewEdit');
    Route::get('space-allocation/download/{id}', 'SpaceAllocationController@applicationDownload');
    Route::get('space-allocation/certificate/{app_id}/{process_type_id}', 'SpaceAllocationController@certificateAndOther');
    Route::get('loan-locator/updateAD/{app_id}/{process_type_id}', 'LoanLocatorController@updateADInfo');
    Route::get('loan-locator/verify_history/{process_type_id}/{process_list_id}', 'LoanLocatorController@verifyProcessHistory');
    Route::get('space-allocation/desk_form', 'SpaceAllocationController@AdddeskForm');

    // storing challan    
    Route::post('space-allocation/challan-store/{id}', 'SpaceAllocationController@challanStore');

    //Process related urls
    Route::post('space-allocation/ajax/{param}', 'SpaceAllocationController@ajaxRequest');
    Route::patch('/space-allocation/updateProcess', "SpaceAllocationController@updateProcess");


    Route::get('space-allocation/view-comment/{id}', 'SpaceAllocationController@remarksView');
    Route::get('space-allocation/view-negative-remarks/{id}/{service_id}', 'SpaceAllocationController@viewNegativeRemarks');

    // Get EIA color
    Route::get('space-allocation/colour-change', 'SpaceAllocationController@colorChange');
    Route::get('space-allocation/discard-certificate/{id}', 'SpaceAllocationController@discardCertificate');
    Route::get('space-allocation/project-cer-re-gen/{id}', 'SpaceAllocationController@certificate_re_gen');

    //it will be enable after duplicate certificate functionality is on
    //Route::post('space-allocation/download-certificate/{certificateLink}', 'spaceAllocationController@downloadCertificate');


    Route::get('space-allocation/form', 'SpaceAllocationController@form');


    /*     *********************************End of Route group****************************** */

});



// Route group without XssProtection so that the data from Rich text editor in the process bar do not get pursed
Route::group(array('module' => 'registration', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {
    Route::get('loan-locator/list/{process_id}', 'ProcessPathController@processListById');

    Route::get('space-allocation/view-remark/{id}', 'SpaceAllocationController@remarksView');

    Route::get('space-allocation/view-negative-remarks/{id}/{service_id}', 'SpaceAllocationController@viewNegativeRemarks');
    /*     * ********************************End of Route group****************************** */
});
