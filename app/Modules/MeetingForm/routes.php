<?php

Route::group(array('module' => 'MeetingForm', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'],
    'namespace' => 'App\Modules\MeetingForm\Controllers'), function () {

    Route::get('meeting-form/add', 'MeetingFormController@applicationForm');
    Route::post('meeting-form/add', 'MeetingFormController@appStore');

    //Loan Locator view/edit and application download route
    Route::get('meeting-form/view/{id}', "MeetingFormController@applicationViewEdit");
    Route::get('meeting-form/view/{id}/board-meeting', "MeetingFormController@applicationViewEdit");
    Route::get('space-allocation/application/{openMode}/{id}', 'SpaceAllocationController@applicationViewEdit');
    Route::get('space-allocation/download/{id}', 'SpaceAllocationController@applicationDownload');
    Route::get('space-allocation/certificate/{app_id}/{process_type_id}', 'SpaceAllocationController@certificateAndOther');
    Route::get('loan-locator/updateAD/{app_id}/{process_type_id}', 'LoanLocatorController@updateADInfo');
    Route::get('loan-locator/verify_history/{process_type_id}/{process_list_id}', 'LoanLocatorController@verifyProcessHistory');
    Route::get('space-allocation/desk_form', 'SpaceAllocationController@AdddeskForm');
    Route::get('meeting-form/met-min-pdf/{app_id}','MeetingFormController@appsDownloadPDF');
    Route::post('meeting-form/previous-month-data','MeetingFormController@getPreviousMonthData');

    /*pdf agenda*/

    Route::get('meeting-form/agenda-generate/{app_id?}','MeetingFormController@agendaPdf');
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

    /* download as pdf */
    Route::get('meeting-form/pdf/{app_id}', 'MeetingFormController@downloadAsPdf');


    /*     *********************************End of Route group****************************** */

});



// Route group without XssProtection so that the data from Rich text editor in the process bar do not get pursed
Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {
    Route::get('meeting-form/list/{process_id}', 'ProcessPathController@processListById');

    Route::get('space-allocation/view-remark/{id}', 'SpaceAllocationController@remarksView');

    Route::get('space-allocation/view-negative-remarks/{id}/{service_id}', 'SpaceAllocationController@viewNegativeRemarks');
    /*     * ********************************End of Route group****************************** */
});
