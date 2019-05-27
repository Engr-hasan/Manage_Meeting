<?php

// With Authorization (Login is required)

Route::group(array('module' => 'Users', 'middleware' => ['auth','checkAdmin'], 'namespace' => 'App\Modules\Users\Controllers'), function() {

    /* User related */
    Route::get('/users/lists', "UsersController@lists");
    Route::get('/users/delegation/{id}', "UsersController@delegation");
    Route::get('/users/delegations/{id}', "UsersController@delegations");

    Route::post('/users/get-delegate-userinfo', "UsersController@getDeligatedUserInfo");
    Route::post('/users/get-delegate-userinfos', "UsersController@getDeligatedUserInfos");
    Route::patch('/users/process-deligation', "UsersController@processDeligation");
    Route::patch('/users/store-delegation', "UsersController@storeDelegation");
    Route::get('users/view/{id}', "UsersController@view");
    Route::patch('/users/update/{id}', "UsersController@update");

    Route::get('/users/edit/{id}', "UsersController@edit");
    Route::get('/users/activate/{id}', "UsersController@activate");
    Route::get('/users/isApproved/{id}', "UsersController@isApproved");
    Route::get('/users/make-sub-admin/{id}', "UsersController@makeSubAdmin");

    /* Assign desk */
    Route::get('users/assign-desk/{id}', "UsersController@assignDesk");
    Route::post('users/assign-desk-save', "UsersController@assignDeskSave");

    /* Company Associated */
    Route::get('users/company-associated/{id}', "UsersController@companyAssociated");
    Route::post('users/company-associated-save', "UsersController@CompanyAssociatedSave");


    /* Assign Park */
    Route::get('users/assign-park/{id}', "UsersController@assignPark");
    Route::post('users/assign-park-save', "UsersController@assignParkSave");

    Route::get('users/access-log/{id}',"UsersController@accessLogHist");
    Route::get('users/get-access-log-data',"UsersController@accessLogHist");

    Route::get('users/failedLogin-history/{id}', "UsersController@failedLoginHist");
    Route::post('users/get-failed-login-data', "UsersController@getRowFailedData");
    Route::post('users/failed-login-data-resolved', "UsersController@FailedDataResolved");
    /* End of User related */

    /* New User Creation by Admin */
    Route::get('/users/force-logout/{id}', 'UsersController@forceLogout');
    Route::get('users/create-new-user', "UsersController@createNewUser");
    Route::patch('/users/store-new-user', "UsersController@storeNewUser");
    /* End of New User Creation by Admin */

    Route::get('/users/logout', "UsersController@logout");


    /* Company Info */
    Route::get('users/company-associate/', "UsersController@companyAssociatedByUser");
    Route::post('users/company-info-save/', "UsersController@companyInfoSave");

    /* Reset Password from profile and Admin list */
    Route::patch('users/update-password-from-profile', "UsersController@updatePassFromProfile");
    Route::get('users/reset-password/{confirmationCode}', [
        'as' => 'confirmation_path',
        'uses' => 'UsersController@resetPassword'
    ]);

    /*
     * datatable
     */


//    user approval or reject
    Route::post('/users/approve/{id}', "UsersController@approveUser");
    Route::post('/users/reject/{id}', "UsersController@rejectUser");

    /*   To step Verification */
    Route::get('/users/two-step', 'UsersController@twoStep');
    Route::patch('/users/check-two-step', 'UsersController@checkTwoStep');
    Route::patch('/users/verify-two-step', 'UsersController@verifyTwoStep');
});

// Only Login User can do it.
Route::group(array('module' => 'Users', 'middleware' => ['auth'], 'namespace' => 'App\Modules\Users\Controllers'), function() {

    /* User related */
    Route::get('/users/delegate', "UsersController@delegate");
    Route::get('/users/remove-deligation/{id?}', "UsersController@removeDeligation");
    Route::get('users/profileinfo', "UsersController@profileInfo");

    /* 100% profile completed related issue */
    /* User profile update */

    Route::patch('users/profile_update', [
        'uses' => 'UsersController@profile_update'
    ]);
    Route::post('users/get-row-details-data', "UsersController@getRowDetailsData");
    Route::post('users/get-access-log-data/{id}', "UsersController@getAccessLogData");
    Route::post('users/get-access-log-data-for-self', "UsersController@getAccessLogDataForSelf");
    Route::post('users/get-access-log-failed', "UsersController@getAccessLogFailed");
    Route::post('users/get-last-50-action', "UsersController@getLast50Action");
    Route::post('users/get-server-time', "UsersController@getServerTime");


});





// Without Authorization (Login is not required)

Route::group(array('module' => 'Users', 'namespace' => 'App\Modules\Users\Controllers'), function() {

    Route::get('/users/login', function () {
        return redirect('login');
    });

    Route::get('/users/message', "UsersController@message");
    Route::get('users/message/{confirmationCode}', [
        'as' => 'confirmation_path',
        'uses' => 'UsersController@message'
    ]);

    Route::get('/users/create', [
        'as' => 'user_create_url',
        'uses' => 'UsersController@create'
    ]);
    Route::patch('/users/store', "UsersController@store");

    // verification
    Route::get('/users/verify-created-user/{encrypted_token}', "UsersController@verifyCreatedUser");
    Route::patch('/users/created-user-verification/{encrypted_token}', "UsersController@createdUserVerification");

    Route::get('users/verification/{confirmationCode}', [
        'as' => 'confirmation_path',
        'uses' => 'UsersController@verification'
    ]);
    Route::patch('users/verification_store/{confirmationCode}', [
        'as' => 'confirmation_path',
        'uses' => 'UsersController@verification_store'
    ]);

    Route::get('/users/get-userdesk-by-type', 'UsersController@getUserDeskByType');

    //Mail Re-sending
    Route::get('users/reSendEmail', "UsersController@reSendEmail");
    Route::patch('users/reSendEmailConfirm', "UsersController@reSendEmailConfirm");

    Route::get('users/support', "UsersController@support");
    Route::get('users/helpdesk-contact', "UsersController@helpdeskContact");

    Route::get('/users/get-district-by-division', 'UsersController@getDistrictByDivision');
    Route::get('/users/get-thana-by-district-id', 'UsersController@get_thana_by_district_id');

    //Forget Password
    Route::get('forget-password', "UsersController@forgetPassword");
    Route::patch('users/reset-forgotten-password', "UsersController@resetForgottenPass");
    Route::get('users/verify-forgotten-pass/{token_no}', "UsersController@verifyForgottenPass");
    Route::get('/users/get-uisc', 'UsersController@getUISClist');
    Route::get('/users/get-bank', 'UsersController@getBanklist');
    Route::post('/users/validateAutoCompleteData/{type}', 'UsersController@validateAutoCompleteData');
    Route::get('/users/get-agency', 'UsersController@getAgencylist');
    Route::get('/users/resendMail', 'UsersController@resendMail');
    Route::get('users/get-user-session', 'UsersController@getUserSession');
    Route::get('users/resendMailByAdmin/{email}', "UsersController@resendMailByAdmin");

    /*     * ********************** End of Route Group *********************** */
});
