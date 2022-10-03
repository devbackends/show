<?php

Route::group(['middleware' => ['web']], function () {
  Route::prefix('clearent')->group(function(){
    Route::post('/account/store/card', 'Devvly\ClearentPayment\Http\Controllers\AccountController@storeCard')->name('clearent.account.store.card');
    Route::get('/settings', 'Devvly\ClearentPayment\Http\Controllers\SettingsController@settings')->name('clearent.settings.get');
  });
  Route::group(['middleware' => ['customer'], 'prefix' => 'checkout/clearent'], function(){
    Route::get('/card/delete', 'Devvly\ClearentPayment\Http\Controllers\PaymentController@deleteCard')->name('clearent.delete.saved.cart');

    Route::post('/create_cart', 'Devvly\ClearentPayment\Http\Controllers\PaymentController@createCart')->name('clearent.cart.create');

    Route::get('/create/charge', 'Devvly\ClearentPayment\Http\Controllers\PaymentController@createCharge')->name('clearent.make.payment');
  });
});