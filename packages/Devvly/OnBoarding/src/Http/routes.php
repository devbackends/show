<?php

Route::group(['middleware' => ['web','admin']], function () {
    Route::prefix('admin/on-boarding')->group(function () {
        Route::post('/create_app', 'Devvly\OnBoarding\Http\Controllers\OnBoardingController@createApp')->name('onboarding.create_app');
        Route::get('/general_data', 'Devvly\OnBoarding\Http\Controllers\OnBoardingController@generalData')->name('onboarding.general_data');
        Route::post('/submit' , 'Devvly\OnBoarding\Http\Controllers\OnBoardingController@submitApp')->name('onboarding.submit');
        Route::post('/documents/upload' , 'Devvly\OnBoarding\Http\Controllers\DocumentsController@upload')->name('onboarding.documents.upload');
        Route::post('/documents/voided_check' , 'Devvly\OnBoarding\Http\Controllers\DocumentsController@voidedCheck')->name('onboarding.documents.voided_check');
        Route::put('/terms/signatures', 'Devvly\OnBoarding\Http\Controllers\TermsController@updateSignatures')->name('onboarding.terms.signatures');
        Route::get('/terms/{id}', 'Devvly\OnBoarding\Http\Controllers\TermsController@merchantTerms')->name('onboarding.terms');

        Route::get(
            '/equipments/products/{term?}',
            'Devvly\OnBoarding\Http\Controllers\EquipmentsController@products'
        )->name('onboarding.equipments.products');
        Route::get(
            '/equipments/survey/{productName}',
            'Devvly\OnBoarding\Http\Controllers\EquipmentsController@getSurvey'
        )->name('onboarding.equipments.survey');

        Route::resource(
            '/business_information',
            'Devvly\OnBoarding\Http\Controllers\BusinessInformationController', [
                'as' => 'onboarding',
                'only' => ['show','update']
            ]
        );
        Route::resource(
            '/sales_profile',
            'Devvly\OnBoarding\Http\Controllers\SalesProfileController', [
                'as' => 'onboarding',
                'only' => ['show','update']
            ]
        );
        Route::resource(
            '/banking',
            'Devvly\OnBoarding\Http\Controllers\BankAccountController', [
                'as' => 'onboarding',
                'only' => ['show','update']
            ]
        );
    });

    Route::prefix('admin')->group(function () {
      // Configuration routes
      Route::get('configuration/{slug?}/{slug2?}', 'Devvly\OnBoarding\Http\Controllers\ConfigurationController@index')
        ->defaults('_config', ['view' => 'admin::configuration.index'])
        ->name('admin.configuration.index');
    });
});