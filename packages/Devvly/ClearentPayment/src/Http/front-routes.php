<?php

Route::group(['middleware' => ['web','customer','theme', 'locale', 'currency']], function () {
    Route::prefix('clearent')->group(function () {
        Route::get('/account/cards', 'Devvly\ClearentPayment\Http\Controllers\AccountController@cards')->name('clearent.account.cards');
        Route::get('/account/make/card/default', 'Devvly\ClearentPayment\Http\Controllers\AccountController@cardDefault')->name('clearent.account.make.card.default');
    });
});