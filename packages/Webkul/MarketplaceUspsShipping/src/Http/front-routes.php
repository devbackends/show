<?php

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency','customer']], function () {

    //Marketplace routes starts here
    Route::prefix('marketplace/usps')->group(function () {

        // fedex Routes
        Route::get('manage-shippping/', 'Webkul\MarketplaceUspsShipping\Http\Controllers\Shop\Account\UspsController@index')->defaults('_config', [
            'view' => 'marketplace_usps::shop.seller.account.usps.index'
        ])->name('marketplaceusps.manage.shipping.show');

        Route::post('/shipping/{id}', 'Webkul\MarketplaceUspsShipping\Http\Controllers\Shop\Account\UspsController@store')->defaults('_config', [
            'redirect' => 'marketplace_usps::shop.seller.account.usps.index'
        ])->name('marketplaceusps.credentials.store');
    });

});