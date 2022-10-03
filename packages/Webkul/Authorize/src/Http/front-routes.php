<?php

Route::group(['middleware' => ['web','customer','theme', 'locale', 'currency']], function () {
    Route::prefix('authorize')->group(function () {
        Route::get('/get-authorize-mode/{sellerId}','Webkul\Authorize\Http\Controllers\AuthorizeConnectController@getAuthorizeMode')->name('authorize.get-mode');
        Route::get('/account/save/card', 'Webkul\Authorize\Http\Controllers\AuthorizeAccountController@saveCard')->defaults('_config', [
            'view' => 'authorize::shop.customer.account.savecard.savecard'
        ])->name('authorize.account.save.card');

        Route::post('/account/store/card', 'Webkul\Authorize\Http\Controllers\AuthorizeAccountController@storeCard')->name('authorize.account.store.card');

        Route::get('/account/make/card/default', 'Webkul\Authorize\Http\Controllers\AuthorizeAccountController@cardDefault')->name('authorize.account.make.card.default');
    });
});