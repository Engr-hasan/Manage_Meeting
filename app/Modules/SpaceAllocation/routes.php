<?php

Route::group(array('module' => 'SpaceAllocation', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'],
    'namespace' => 'App\Modules\SpaceAllocation\Controllers'), function () {

    //New Agency User Registraion Route
    Route::get('space-allocation/add', 'SpaceAllocationController@applicationForm');
    Route::post('space-allocation/add', 'SpaceAllocationController@appStore');


    //Route for Agency List Route
//    Route::get('space-allocation/list', 'spaceAllocationController@viewList');
//    Route::post('space-allocation/get-list', 'spaceAllocationController@getList');


    //Route for Status wise Agency List Route
    Route::get('space-allocation/list-status-wise/{status_id}', 'SpaceAllocationController@viewStatusWiseAppList');
    Route::post('space-allocation/get-list-by-status/', 'SpaceAllocationController@getListByStatus');

    //Certificate Static
    Route::get('space-allocation/certificate/{id}', 'SpaceAllocationController@openCertificate');





    //Certificate Dynamic
    Route::get('space-allocation/dynamic-certificate/{id}', 'SpaceAllocationController@dynamicCertificate');


    //Route for Upload Document
    Route::any('space-allocation/upload-document', 'SpaceAllocationController@uploadDocument');

    //Route for Preview page
    Route::any('space-allocation/preview', 'SpaceAllocationController@preview');


    //application view/edit and application download route
    Route::get('space-allocation/view/{id}', "SpaceAllocationController@applicationViewEdit");
    Route::get('space-allocation/application/{openMode}/{id}', 'SpaceAllocationController@applicationViewEdit');
    Route::get('space-allocation/download/{id}', 'SpaceAllocationController@applicationDownload');
    Route::get('space-allocation/certificate/{app_id}/{process_type_id}', 'SpaceAllocationController@certificateAndOther');
    Route::get('space-allocation/updateAD/{app_id}/{process_type_id}', 'SpaceAllocationController@updateADInfo');
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
    Route::get('space-allocation/list/{process_id}', 'ProcessPathController@processListById');

    Route::get('space-allocation/view-remark/{id}', 'SpaceAllocationController@remarksView');

    Route::get('space-allocation/view-negative-remarks/{id}/{service_id}', 'SpaceAllocationController@viewNegativeRemarks');
    /*     * ********************************End of Route group****************************** */
});
