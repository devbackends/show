<?php

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency','customer']], function () {

    //Marketplace routes starts here
    Route::prefix('marketplace/ups')->group(function () {

        // fedex Routes
        Route::get('ups-shippping/', 'Webkul\MarketplaceUpsShipping\Http\Controllers\Shop\Account\UpsController@index')->defaults('_config', [
            'view' => 'marketplace_ups::shop.seller.account.ups.index'
        ])->name('marketplaceups.manage.shipping.show');

        Route::post('/upsshipping/{id}', 'Webkul\MarketplaceUpsShipping\Http\Controllers\Shop\Account\UpsController@store')->defaults('_config', [
            'redirect' => 'marketplace_ups::shop.seller.account.ups.index'
        ])->name('marketplaceups.credentials.store');
    });

});