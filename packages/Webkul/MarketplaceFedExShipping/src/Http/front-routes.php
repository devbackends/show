<?php

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency','customer']], function () {

    //Marketplace routes starts here
    Route::prefix('marketplace/fedex')->group(function () {

        // fedex Routes
        Route::get('manage-shippping/', 'Webkul\MarketplaceFedExShipping\Http\Controllers\Shop\Account\FedExController@index')->defaults('_config', [
            'view' => 'marketplace_fedex::shop.seller.account.fedex.index'
        ])->name('marketplacefedex.manage.shipping.show');

        Route::post('/shipping/{id}', 'Webkul\MarketplaceFedExShipping\Http\Controllers\Shop\Account\FedExController@store')->defaults('_config', [
            'redirect' => 'marketplace_fedex::shop.seller.account.fedex.index'
        ])->name('marketplacefedex.credentials.store');
    });

});