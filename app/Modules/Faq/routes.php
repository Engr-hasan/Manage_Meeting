<?php

Route::group(array('module' => 'Faq', 'middleware' => ['auth'], 'namespace' => 'App\Modules\Faq\Controllers'), function() {

//****** FAQ Category List ****//
Route::get('faq/faq-cat', "FaqController@faqCat");
Route::get('faq/get-faq-cat-details-data', "FaqController@getFaqCatDetailsData");

Route::get('faq/create-faq-cat', "FaqController@createFaqCat");
Route::patch('faq/store-faq-cat', "FaqController@storeFaqCat");
Route::get('faq/edit-faq-cat/{id}', "FaqController@editFaqCat");
Route::patch('faq/update-faq-cat/{id}', "FaqController@updateFaqCat");

Route::get('faq/index', "FaqController@index");
Route::get('faq/create-faq-article', "FaqController@createFaqArticle");
Route::patch('faq/store-faq-article', "FaqController@storeFaqArticle");
Route::get('faq/edit-faq-article/{id}', "FaqController@editFaqArticle");
Route::patch('faq/update-faq-article/{id}', "FaqController@updateFaqArticle");


});
