<?php

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
    Route::get('/mlogin/verifyphone', 'Mega\Phonelogin\Http\Controllers\PhoneController@verifyPhone')
        ->name('mega.phonelogin.verifyphone');
});
Route::post('/mlogin/sendOtp', 'Mega\Phonelogin\Http\Controllers\PhoneController@sendOtp')
    ->name('mega.phonelogin.sendOtp');

Route::prefix('customer')->group(function () {
    // Login form store
    Route::post('login', 'Mega\Phonelogin\Http\Controllers\SessionController@create')
        ->defaults('_config', [
        'redirect' => 'shop.home.index'
        ])
        ->name('customer.session.create');
    Route::post('/forgot-password', 'Mega\Phonelogin\Http\Controllers\\ForgotPasswordController@store')
        ->name('customer.forgot-password.store');
});

Route::post('/mlogin/verifyOtp', 'Mega\Phonelogin\Http\Controllers\PhoneController@verifyOtp')
    ->name('mega.phonelogin.verifyOtp');

