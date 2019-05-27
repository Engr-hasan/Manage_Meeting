<?php

Route::group(array('module' => 'CoBrandedCard', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'],
    'namespace' => 'App\Modules\CoBrandedCard\Controllers'), function () {

    //New desk User Registraion Route
    Route::get('co-branded-card/add', 'CoBrandedCardController@applicationForm');
    Route::post('co-branded-card/add', 'CoBrandedCardController@appStore');

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
    Route::get('co-branded-card/view/{id}', "CoBrandedCardController@applicationViewEdit");
    Route::get('loan-locator/view/{id}/board-meeting', "LoanLocatorController@applicationViewEdit");
    Route::get('space-allocation/application/{openMode}/{id}', 'SpaceAllocationController@applicationViewEdit');
    Route::get('space-allocation/download/{id}', 'SpaceAllocationController@applicationDownload');
    Route::get('co-branded-card/certificate/{app_id}/{process_type_id}', 'CoBrandedCardController@certificateAndOther');
    Route::get('co-branded-card/updateAD/{app_id}/{process_type_id}', 'CoBrandedCardController@updateADInfo');
    Route::get('loan-locator/verify_history/{process_type_id}/{process_list_id}', 'LoanLocatorController@verifyProcessHistory');
    Route::get('space-allocation/desk_form', 'SpaceAllocationController@AdddeskForm');


    /*     *********************************End of Route group****************************** */

});



// Route group without XssProtection so that the data from Rich text editor in the process bar do not get pursed
Route::group(array('module' => 'registration', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {
    Route::get('co-branded-card/list/{process_id}', 'ProcessPathController@processListById');

    Route::get('space-allocation/view-remark/{id}', 'SpaceAllocationController@remarksView');

    Route::get('space-allocation/view-negative-remarks/{id}/{service_id}', 'SpaceAllocationController@viewNegativeRemarks');
    /*     * ********************************End of Route group****************************** */
});
