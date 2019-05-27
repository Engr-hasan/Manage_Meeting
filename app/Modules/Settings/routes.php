<?php

Route::group(array('module' => 'Settings', 'middleware' => ['auth', 'checkAdmin'], 'namespace' => 'App\Modules\Settings\Controllers'), function() {

    //****** Area Info ****//
    Route::get('settings/area-list', "SettingsController@areaList");
    Route::get('settings/document', "SettingsController@document");
    Route::get('settings/create-area', "SettingsController@createArea");
    Route::get('settings/edit-area/{id}', "SettingsController@editArea");

    Route::post('settings/store-area', "SettingsController@storeArea");
    Route::patch('settings/update-area/{id}', "SettingsController@updateArea");

    Route::post('settings/get-area-data', "SettingsController@getAreaData");

    //****** Bank List ****//
    Route::get('settings/bank-list', "SettingsController@bank");
    Route::get('settings/create-bank', "SettingsController@createBank");
    Route::get('settings/edit-bank/{id}', "SettingsController@editBank");
    Route::get('settings/view-bank/{id}', "SettingsController@viewBank");

    Route::patch('settings/store-bank', "SettingsController@storeBank");
    Route::patch('settings/update-bank/{id}', "SettingsController@updateBank");

    //****** Whats New ****//
    Route::get('settings/whats-new', "SettingsController@whatsNew");
    Route::get('settings/create-whats_new', "SettingsController@whatsNewCreate");
    Route::post('settings/store-whats-new', "SettingsController@whatsNewStore");
    Route::get('settings/get-whats-new-details-data', "SettingsController@getWhatsNew");
    Route::get('settings/edit-whats-new/{id}', "SettingsController@editWhatsNew");
    Route::patch('settings/update-whats-new/{id}', "SettingsController@updateWhatsNew");

    // ***** Home Page Slider ******/

    Route::get('settings/home-page-slider', "SettingsController@HomePageSlider");
    Route::get('settings/create-home-page-slider', "SettingsController@HomePageSliderCreate");
    Route::post('settings/store-home-page-slider', "SettingsController@homePageSliderStore");
    Route::get('settings/get-home-page-slider-details-data', "SettingsController@getHomePageSlider");
    Route::get('settings/edit-home-page-slider/{id}', "SettingsController@editHomePageSlider");
    Route::patch('settings/update-home-page-slider/{id}', "SettingsController@updateHomePageSlider");

    //****** Branch List ****//
    Route::get('settings/branch-list', "SettingsController@branch");
    Route::get('settings/create-branch', "SettingsController@createBranch");
    Route::get('settings/edit-branch/{id}', "SettingsController@editBranch");
    Route::get('settings/view-branch/{id}', "SettingsController@viewBranch");
    Route::patch('settings/store-branch', "SettingsController@storeAndUpdateBranch");
    Route::patch('settings/store-branch/{id}', "SettingsController@storeAndUpdateBranch");
    /* Company Information */

    Route::get('settings/company-info','SettingsController@companyInfo');
    Route::get('settings/create-company','SettingsController@createCompany');
    Route::post('settings/company-store','SettingsController@storeCompany');
    Route::get('settings/company-info-action/{id}','SettingsController@companyInfoAction');
    Route::get('settings/company-change-status/{id}/{status_id}','SettingsController@companyChangeStatus');
    Route::get('settings/approved-change-status/{company_id}','SettingsController@companyApprovedStatus');
    Route::get('settings/rejected-change-status/{company_id}','SettingsController@companyRejectedStatus');
    Route::post('settings/get-company-data','SettingsController@getCompanyData');

    //****** Currency  ****//
    Route::get('settings/currency', "SettingsController@Currency");
    Route::get('settings/create-currency', "SettingsController@createCurrency");
    Route::get('settings/edit-currency/{id}', "SettingsController@editCurrency");

    Route::post('settings/store-currency', "SettingsController@storeCurrency");
    Route::patch('settings/update-currency/{id}', "SettingsController@updateCurrency");

    //****** Config List ****//
    Route::get('settings/configuration', "SettingsController@configuration");
    Route::get('settings/edit-config/{id}', "SettingsController@editConfiguration");
    Route::patch('settings/update-config/{id}', "SettingsController@updateConfig");

    //****** Document List ****//
    Route::get('settings/document', "SettingsController@document");
    Route::post('settings/get-document-data', "SettingsController@getDocData");
    Route::get('settings/create-document', "SettingsController@createDocument");
    Route::post('settings/store-document', "SettingsController@storeDocument");
    Route::get('settings/edit-document/{id}', "SettingsController@editDocument");
    Route::patch('settings/update-document/{id}', "SettingsController@updateDocument");

    //****** Economic Zone List ****//
    Route::get('settings/park-info', "SettingsController@parks");
    Route::post('settings/get-eco-park-data', "SettingsController@getEcoParkData");
    Route::get('settings/create-park-info', "SettingsController@createPark");
    Route::post('settings/store-eco-zone', "SettingsController@storeEcoZone");
    Route::get('settings/edit-park-info/{id}', "SettingsController@editEcoZone");
    Route::patch('settings/update-park/{id}', "SettingsController@updatePark");

    //****** FAQ Category List ****//
    Route::get('settings/faq-cat', "SettingsController@faqCat");
    Route::get('settings/create-faq-cat', "SettingsController@createFaqCat");
    Route::get('settings/edit-faq-cat/{id}', "SettingsController@editFaqCat");

    //****** High Commission  ****//
    Route::get('settings/high-commission', "SettingsController@highCommission");
    Route::get('settings/create-high-commission', "SettingsController@createHighCommission");
    Route::get('settings/edit-high-commission/{id}', "SettingsController@editHighCommission");

    Route::post('settings/store-high-commission', "SettingsController@storeHighCommission");
    Route::patch('settings/update-high-commission/{id}', "SettingsController@updateHighCommission");

    Route::post('settings/get-high-commission-data', "SettingsController@getHighCommissionData");

    //****** HS Code  ****//
    Route::get('settings/hs-codes', "SettingsController@HsCodes");
    Route::get('settings/create-hs-code', "SettingsController@createHsCode");
    Route::get('settings/edit-hs-code/{id}', "SettingsController@editHsCode");

    Route::post('settings/store-hs-code', "SettingsController@storeHsCode");
    Route::patch('settings/update-hs-code/{id}', "SettingsController@updateHsCode");

    //****** Notice ****//
    Route::get('settings/notice', "SettingsController@Notice");
    Route::get('settings/create-notice', "SettingsController@createNotice");
    Route::get('settings/create-notice/board-meeting', "SettingsController@createNotice");
    Route::get('settings/edit-notice/{id}', "SettingsController@editNotice");
    Route::patch('settings/store-notice', "SettingsController@storeNotice");
    Route::patch('settings/update-notice/{id}', "SettingsController@updateNotice");

    Route::post('settings/get-notice-details-data', "SettingsController@getNoticeDetailsData");

    //****** Ports  ****//
    Route::get('settings/ports', "SettingsController@Ports");
    Route::get('settings/create-port', "SettingsController@createPort");
    Route::get('settings/edit-port/{id}', "SettingsController@editPort");

    Route::post('settings/store-port', "SettingsController@storePort");
    Route::patch('settings/update-port/{id}', "SettingsController@updatePort");

    //****** Industrial Category  ****//
    Route::get('settings/indus-cat', "SettingsController@IndusCat");
    Route::get('settings/create-indus-cat', "SettingsController@createIndusCat");
    Route::get('settings/edit-indus-cat/{id}', "SettingsController@editIndusCat");

    Route::post('settings/store-indus-cat', "SettingsController@storeIndusCat");
    Route::patch('settings/update-indus-cat/{id}', "SettingsController@updateIndusCat");

    //****** Notify List ****//
    Route::get('settings/notification', "SettingsController@notification");
    Route::get('settings/view-notify/{id}', "SettingsController@viewNotify");

    //****** Logo List ****//
    Route::get('settings/logo', "SettingsController@logo");
    Route::patch('settings/update-logo', "SettingsController@storeLogo");
    Route::get('settings/edit-logo', "SettingsController@editLogo");
//    Route::patch('settings/update-logo/{id}', "SettingsController@storeLogo");
//    Route::get('settings/edit-logo/{id}', "SettingsController@editLogo");

    //****** Security List ****//
    Route::get('settings/security', "SettingsController@security");
    Route::patch('settings/store-security', "SettingsController@storeSecurity");
    Route::get('settings/edit-security/{id}', "SettingsController@editSecurity");
    Route::post('settings/get-security-data', "SettingsController@getSecurityData");
    Route::patch('settings/update-security/{id}', "SettingsController@updateSecurity");

    //****** Stakeholder List ****//
    Route::get('settings/stakeholder', "SettingsController@stakeholder");
    Route::post('settings/get-details-data', "SettingsController@getDetailsData");
    Route::get('settings/create-stakeholder', "SettingsController@createStakeholder");
    Route::get('settings/edit-stakeholder/{id}', "SettingsController@editStakeholder");
    Route::patch('settings/store-stakeholder', "SettingsController@storeStakeholder");
    Route::patch('settings/update-stakeholder/{id}', "SettingsController@updateStakeholder");

    /*     * *******************Units*********************** */
    Route::get('settings/units', "SettingsController@Units");
    Route::get('settings/create-unit', "SettingsController@createUnit");
    Route::get('settings/edit-unit/{id}', "SettingsController@editUnit");

    Route::post('settings/store-unit', "SettingsController@storeUnit");
    Route::patch('settings/update-unit/{id}', "SettingsController@updateUnit");

    //************************************Soft Delete *******************************/
    Route::get('settings/delete/{model}/{id}', "SettingsController@softDelete");
    Route::get('settings/delete-new/{model}/{id}', "SettingsController@softDeleteNew");
    Route::get('settings/delete-is-archive/{model}/{id}', "SettingsController@softDeleteIsArchive");

    //****** User Desk  ****//
    Route::get('settings/user-desk', "SettingsController@userDesk");
    Route::get('settings/create-user-desk', "SettingsController@createUserDesk");
    Route::get('settings/edit-user-desk/{id}', "SettingsController@editUserDesk");

    Route::patch('settings/store-user-desk', "SettingsController@storeUserDesk");
    Route::patch('settings/update-user-desk/{id}', "SettingsController@updateUserDesk");

    Route::get('settings/get-user-desk-data', "SettingsController@getUserDeskData");

    /*     * *********User Types ***************** */
    Route::get('settings/user-type', "SettingsController@userType");
    Route::get('settings/edit-user-type/{id}', "SettingsController@editUserType");
    Route::patch('settings/update-user-type/{id}', "SettingsController@updateUserType");

    /*     * *********Service info ***************** */
    Route::get('settings/service-info', "SettingsController@serviceInfo");
    Route::get('settings/create-service-info-details', "SettingsController@createServiceInfoDetails");
    Route::get('settings/edit-service-info-details/{id}', "SettingsController@editServiceInfoDetails");
    Route::patch('settings/update-service-info-details/{id}', "SettingsController@updateServiceDetails");
    Route::post('settings/service-details-save', "SettingsController@serviceSave");

    Route::patch('settings/store-faq-cat', "SettingsController@storeFaqCat");
    Route::patch('settings/update-faq-cat/{id}', "SettingsController@updateFaqCat");

    Route::get('settings/get-faq-cat-details-data', "SettingsController@getFaqCatDetailsData");

    Route::resource('settings/', "SettingsController");

    /*     * ***********************End of Group Route file***************************** */
});


// some route which are used in different module
Route::group(array('module' => 'Settings', 'middleware' => ['auth'], 'namespace' => 'App\Modules\Settings\Controllers'), function() {

    Route::get('/settings/get-district-by-division-id', 'SettingsController@get_district_by_division_id');
    Route::get('settings/get-police-stations', 'SettingsController@getPoliceStations');
    Route::get('settings/get-district-user', 'SettingsController@getDistrictUser');
});
