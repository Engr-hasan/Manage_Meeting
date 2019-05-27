<?php

Route::group(array('module' => '  GeneralAppsController', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'],
    'namespace' => 'App\Modules\GeneralApps\Controllers'), function () {

    //New Agency User Registraion Route
    Route::get('general-apps/add', 'GeneralAppsController@applicationForm');
    Route::post('general-apps/add', 'GeneralAppsController@appStore');


//    Route::get('general-apps/view/{id}', "GeneralAppsController@applicationViewEdit");
    Route::get('general-apps/view/{id}/board-meeting', "GeneralAppsController@applicationViewEdit");
    Route::any('general-apps/preview', 'GeneralAppsController@preview');

    //update adoneForm Data
    Route::get('general-apps/updateAD/{app_id}/{process_type_id}', 'GeneralAppsController@updateADInfo');
    Route::get('general-apps/verify_history/{process_type_id}/{process_list_id}', 'GeneralAppsController@verifyProcessHistory');

    //Certificate generate-regenerate related
    Route::get('general-apps/discard-certificate/{id}', 'SpaceAllocationController@discardCertificate');
    Route::get('general-apps/project-cer-re-gen/{id}', 'SpaceAllocationController@certificate_re_gen');
    //Route for upload file
    Route::any('general-apps/upload-document', 'GeneralAppsController@uploadDocument');

    // storing challan
    Route::post('general-apps/challan-store/{id}', 'GeneralAppsController@challanStore');


    /*     *********************************End of Route group****************************** */

});



// Route group without XssProtection so that the data from Rich text editor in the process bar do not get pursed
Route::group(array('module' => 'registration', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {
    Route::get('general-apps/list/{process_id}', 'ProcessPathController@processListById');

    /*     * ********************************End of Route group****************************** */
});
