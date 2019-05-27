<?php

Route::group(array('module' => 'Dashboard', 'middleware' => ['auth'],'namespace' => 'App\Modules\Dashboard\Controllers'), function() {

    Route::resource('dashboard', 'DashboardController');
    
});	