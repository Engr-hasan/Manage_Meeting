<?php

Route::group(array('module' => 'Signup', 'namespace' => 'App\Modules\Signup\Controllers'), function() {

    Route::get('signup', 'SignupController@create');
    Route::patch('signup/store', 'SignupController@store');
    Route::get('google_signUp', 'SignupController@google_signUp');
    Route::patch('signup/google/store', 'SignupController@GoogleStore');

    Route::get('signup/verification/{confirmationCode}', [
        'as' => 'confirmation_path',
        'uses' => 'SignupController@verification'
    ]);

    Route::patch('signup/verification_store/{confirmationCode}', [
        'as' => 'confirmation_path',
        'uses' => 'SignupController@verificationStore'
    ]);
    Route::get('/signup/resend-mail', 'SignupController@resendMail');


    Route::resource('Signup', 'SignupController');

});	