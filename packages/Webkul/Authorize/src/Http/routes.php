<?php

Route::group(['middleware' => ['web']], function () {
    Route::prefix('checkout')->group(function () {
        Route::get('/authorize/card/delete', 'Webkul\Authorize\Http\Controllers\AuthorizeConnectController@deleteCard')->name('authorize.delete.saved.cart');

        Route::post('/authorize/sendtoken', 'Webkul\Authorize\Http\Controllers\AuthorizeConnectController@collectToken')->name('authorize.get.token');
        Route::get('/authorize/get-cards', 'Webkul\Authorize\Http\Controllers\AuthorizeConnectController@getCards')->name('authorize.card.get');
        Route::get('/authorize/create/charge', 'Webkul\Authorize\Http\Controllers\AuthorizeConnectController@createCharge')->name('authorize.make.payment');
    });
});