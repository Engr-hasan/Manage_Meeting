<?php

Route::group(array('module' => 'Support', 'middleware' => ['auth'], 'namespace' => 'App\Modules\Support\Controllers'), function() {

    ###support feedback
    Route::get('support/feedback', "SupportController@feedback");
    Route::get('support/create-feedback', "SupportController@createFeedback");

    ### help support
    Route::get('support/help/{segment}', "SupportController@help");

    ### Feedback details data
    Route::get('support/get-feedback-details-data', "SupportController@getFeedbackDetailsData");
    ### Get feedback details data of assigned to a specific user (admin)
    Route::post('support/get-uncategorized-feedback-data/{flag}', "SupportController@getUncategorizedFeedbackData");

    /* Notice Start */
    Route::get('support/view-notice/{id}', "SupportController@viewNotice");
    /* Notice End */

    Route::resource('support', 'SupportController');
    
});	