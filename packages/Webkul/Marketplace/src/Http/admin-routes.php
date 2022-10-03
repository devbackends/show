<?php

Route::group(['middleware' => ['web']], function () {

    Route::prefix('admin/marketplace')->group(function () {

        Route::group(['middleware' => ['admin']], function () {

            // User help request routes
            Route::view('user-help-requests', 'marketplace::admin.user-help-requests.index')->name('admin.user-help-requests.index');

            Route::get('user-help-requests/handle/{id}', 'Webkul\Marketplace\Http\Controllers\Admin\UserHelpRequestController@handle')->defaults('_config', [
                'view' => 'marketplace::admin.user-help-requests.handle'
            ])->name('admin.user-help-requests.handle');

            Route::put('user-help-requests/handle/{id}', 'Webkul\Marketplace\Http\Controllers\Admin\UserHelpRequestController@update')
                ->name('admin.user-help-requests.update');

            //Seller routes
            Route::get('sellers', 'Webkul\Marketplace\Http\Controllers\Admin\SellerController@index')->defaults('_config', [
                'view' => 'marketplace::admin.sellers.index'
            ])->name('admin.marketplace.sellers.index');

            Route::post('sellers/delete/{id}', 'Webkul\Marketplace\Http\Controllers\Admin\SellerController@destroy')
                ->name('admin.marketplace.sellers.delete');

            Route::post('sellers/massdelete', 'Webkul\Marketplace\Http\Controllers\Admin\SellerController@massDestroy')->defaults('_config', [
                'redirect' => 'admin.marketplace.sellers.index'
            ])->name('admin.marketplace.sellers.massdelete');

            Route::post('sellers/massupdate', 'Webkul\Marketplace\Http\Controllers\Admin\SellerController@massUpdate')->defaults('_config', [
                'redirect' => 'admin.marketplace.sellers.index'
            ])->name('admin.marketplace.sellers.massupdate');

            Route::get('sellers/{id}/orders', 'Webkul\Marketplace\Http\Controllers\Admin\OrderController@index')->defaults('_config', [
                'view' => 'marketplace::admin.orders.index'
            ])->name('admin.marketplace.sellers.orders.index');

            Route::get('orders', 'Webkul\Marketplace\Http\Controllers\Admin\OrderController@index')->defaults('_config', [
                'view' => 'marketplace::admin.orders.index'
            ])->name('admin.marketplace.orders.index');

            Route::post('orders', 'Webkul\Marketplace\Http\Controllers\Admin\OrderController@pay')->defaults('_config', [
                'redirect' => 'admin.marketplace.orders.index'
            ])->name('admin.marketplace.orders.pay');

            Route::get('transactions', 'Webkul\Marketplace\Http\Controllers\Admin\TransactionController@index')->defaults('_config', [
                'view' => 'marketplace::admin.transactions.index'
            ])->name('admin.marketplace.transactions.index');


            //Seller products routes
            Route::get('products', 'Webkul\Marketplace\Http\Controllers\Admin\ProductController@index')->defaults('_config', [
                'view' => 'marketplace::admin.products.index'
            ])->name('admin.marketplace.products.index');

            Route::get('products/delete/{id}', 'Webkul\Marketplace\Http\Controllers\Admin\ProductController@destroy')
                ->name('admin.marketplace.products.delete');

            Route::post('products/massdelete', 'Webkul\Marketplace\Http\Controllers\Admin\ProductController@massDestroy')->defaults('_config', [
                'redirect' => 'admin.marketplace.products.index'
            ])->name('admin.marketplace.products.massdelete');

            Route::post('products/massupdate', 'Webkul\Marketplace\Http\Controllers\Admin\ProductController@massUpdate')->defaults('_config', [
                'redirect' => 'admin.marketplace.products.index'
            ])->name('admin.marketplace.products.massupdate');

            Route::get('seller/product/search/{id}', 'Webkul\Marketplace\Http\Controllers\Admin\SellerController@search')->defaults('_config', [
                'view' => 'marketplace::admin.sellers.products.search'
            ])->name('admin.marketplace.seller.product.search');

            Route::get('seller/product/assign/{seller_id}/{product_id?}', 'Webkul\Marketplace\Http\Controllers\Admin\SellerController@assignProduct')->defaults('_config', [
                'view' => 'marketplace::admin.sellers.products.assign'
            ])->name('admin.marketplace.seller.product.create');

            Route::post('seller/product/assign/{seller_id}/{product_id?}', 'Webkul\Marketplace\Http\Controllers\Admin\SellerController@saveAssignProduct')->defaults('_config', [
                'redirect' => 'admin.marketplace.sellers.index'
            ])->name('admin.marketplace.seller.product.store');

            //Seller review routes
            Route::get('reviews', 'Webkul\Marketplace\Http\Controllers\Admin\ReviewController@index')->defaults('_config', [
                'view' => 'marketplace::admin.reviews.index'
            ])->name('admin.marketplace.reviews.index');

            Route::post('reviews/massupdate', 'Webkul\Marketplace\Http\Controllers\Admin\ReviewController@massUpdate')->defaults('_config', [
                'redirect' => 'admin.marketplace.reviews.index'
            ])->name('admin.marketplace.reviews.massupdate');

            //customer edit routes
            Route::put('customers/edit/{id}', 'Webkul\Marketplace\Http\Controllers\Admin\CustomerController@update')->defaults('_config', [
                'redirect' => 'admin.customer.index'
            ])->name('admin.marketplace.customer.update');

        });

    });

});
