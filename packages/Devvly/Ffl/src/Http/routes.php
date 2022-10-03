<?php


Route::group(['prefix' => 'ffl-signup'], function () {
    Route::get('/', 'Devvly\Ffl\Http\Controllers\FflOnBoardingController@index')->defaults('_config', [
        'view' => 'ffl::fflonboarding.landing',
    ])->name('ffl.onboarding.landing');
    Route::get('finish', 'Devvly\Ffl\Http\Controllers\FflOnBoardingController@finish')->defaults('_config', [
        'view' => 'ffl::fflonboarding.finish',
    ])->name('ffl.onboarding.finish');
    Route::get('/form', 'Devvly\Ffl\Http\Controllers\FflOnBoardingController@form')->defaults('_config', [
        'view' => 'ffl::fflonboarding.form',
    ])->name('ffl.onboarding.form');
    Route::post('/form', 'Devvly\Ffl\Http\Controllers\FflOnBoardingController@store')->name('ffl.onboarding.form.store');
});


Route::group([
    'prefix'     => 'super/ffl',
    'middleware' => ['web', 'super-locale', 'super-admin'],
    'namespace'  => 'Devvly\Ffl\Http\Controllers\Admin\Super',
], function () {

});

Route::group(['prefix' => 'api/ffl', 'middleware' => ['web', 'locale', 'currency']], function () {
    Route::get('find/{id}', 'Devvly\Ffl\Http\Controllers\Api\FflController@findById')->name('ffl.by_id');
    Route::post('find/closest', 'Devvly\Ffl\Http\Controllers\Api\FflController@findClosest')->name('ffl.get_closest_locations');
    Route::get('find/{zip}/{lat}/{lng}/{offset}/{state}', 'Devvly\Ffl\Http\Controllers\Api\FflController@findZip')->name('ffl.get_by_zip');
});

Route::group([
    'prefix'     => 'admin',
    'middleware' => ['admin', 'web'],
    'namespace'  => 'Devvly\Ffl\Http\Controllers\Admin',
], function () {
    Route::get('configuration/sales/ffl', 'FflConfigurationController@index')->name('ffl.admin.configuration');
    Route::post('ffl/disable/', 'FflConfigurationController@fflDisable')->name('ffl.admin.disable');
    Route::get('ffl', 'FflConfigurationController@getFfl')->name('ffl.get.current');



    Route::post('/ffl/data-set', 'DataSetController@store')->name('ffl.store_data_set');
    Route::get('/ffl/data-set', 'DataSetController@index')->defaults('_config', [
        'view' => 'ffl::admin.ffl.upload_data_set',
    ])->name('ffl.store_data_set');


    Route::get('/ffl/review', 'Review@index')->defaults('_config', [
        'view' => 'ffl::admin.ffl.review.index',
    ])->name('ffl.review.list');
    Route::get('/ffl/review/{id}', 'Review@show')->defaults('_config', [
        'view' => 'ffl::admin.ffl.review.item',
    ])->name('ffl.review.show');
    Route::post('/ffl/review/{id}', 'Review@approve')->name('ffl.review.approve');




});
Route::get('super/ffl/confirm-preferred-ffl-form/{token}', 'Devvly\Ffl\Http\Controllers\Admin\Super\Review@ConfirmPreferredFflForm')
    ->defaults('_config', ['view' => 'ffl::super.admin.review.confirm-preferred-ffl-message'])
    ->name('confirm-preferred-ffl-form');
