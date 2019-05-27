<?php

Route::group(array('module' => 'AddOnForm', 'middleware' => ['auth','XssProtection'],'namespace' => 'App\Modules\AddOnForm\Controllers'), function() {

    Route::post('add-on-form/request-form-content', "AddOnFormController@requestFormContent");
    
});	