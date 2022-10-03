<?php
    Route::group(['middleware' => ['web']], function () {
        Route::prefix('admin')->group(function () {
            Route::group(['middleware' => ['admin']], function () {
                Route::prefix('pwa')->group(function () {
                    // PushNotifications routes
                    Route::get('pushnotification/','Webkul\PWA\Http\Controllers\PushNotificationController@index')->defaults('_config', ['view' => 'pwa::PushNotification.index'
                    ])->name('pwa.pushnotification.index');
                    // create
                    Route::get('pushnotification/create','Webkul\PWA\Http\Controllers\PushNotificationController@create')->defaults('_config', ['view' => 'pwa::PushNotification.create'
                    ])->name('pwa.pushnotification.create');
                    // store
                    Route::post('pushnotification/store','Webkul\PWA\Http\Controllers\PushNotificationController@store')->defaults('_config', ['redirect' => 'pwa.pushnotification.index'
                    ])->name('pwa.pushnotification.store');
                    //edit
                    Route::get('pushnotification/edit/{id}','Webkul\PWA\Http\Controllers\PushNotificationController@edit')->defaults('_config', ['view' => 'pwa::PushNotification.edit'
                    ])->name('pwa.pushnotification.edit');
                    // update
                    Route::post('pushnotification/update/{id}','Webkul\PWA\Http\Controllers\PushNotificationController@update')->defaults('_config', ['redirect' => 'pwa.pushnotification.index'
                    ])->name('pwa.pushnotification.update');
                    // delete
                    Route::get('pushnotification/delete/{id}','Webkul\PWA\Http\Controllers\PushNotificationController@destroy')->defaults('_config', ['redirect' => 'pwa.pushnotification.index'
                    ])->name('pwa.pushnotification.delete');

                    //push to firebase
                    Route::get('pushnotification/push/{id}','Webkul\PWA\Http\Controllers\PushNotificationController@pushtofirebase')->defaults('_config', ['redirect' => 'pwa.pushnotification.index'
                    ])->name('pwa.pushnotification.pushtofirebase');
                });
            });
        });
    });

    Route::group(['prefix' => 'api'], function ($router) {

        Route::group(['middleware' => ['locale', 'theme', 'currency']], function ($router) {
            //Product routes
            Route::get('products', 'Webkul\PWA\Http\Controllers\Shop\ProductController@index')->name('api.products');

            Route::get('products/{id}', 'Webkul\PWA\Http\Controllers\Shop\ProductController@get');

            Route::get('product-configurable-config/{id}', 'Webkul\PWA\Http\Controllers\Shop\ProductController@configurableConfig');

            //Order routes
            Route::get('orders', 'Webkul\API\Http\Controllers\Shop\ResourceController@index')->defaults('_config', [
                'repository' => 'Webkul\Sales\Repositories\OrderRepository',
                'resource' => 'Webkul\PWA\Http\Resources\Sales\Order',
                'authorization_required' => true
            ]);

            Route::get('orders/{id}', 'Webkul\API\Http\Controllers\Shop\ResourceController@get')->defaults('_config', [
                'repository' => 'Webkul\Sales\Repositories\OrderRepository',
                'resource' => 'Webkul\PWA\Http\Resources\Sales\Order',
                'authorization_required' => true
            ]);

            //Checkout routes
            Route::group(['namespace' => 'Webkul\PWA\Http\Controllers\Shop', 'prefix' => 'checkout'], function ($router) {
                Route::post('cart/add/{id}', '\Webkul\Marketplace\Http\Controllers\Shop\CartController@add');

                Route::get('cart', 'CartController@get');

                Route::get('shipping-rates','CartController@getShippingRates');

                Route::get('cart/empty', 'CartController@destroy');

                Route::put('cart/update', 'CartController@update');

                Route::get('cart/remove-item/{id}', 'CartController@destroyItem');

                Route::get('cart/move-to-wishlist/{id}', 'CartController@moveToWishlist');

                Route::post('save-billing-address', 'CheckoutController@saveBillingAddress');

                Route::post('save-shipping-address', 'CheckoutController@saveShippingAddress');

                Route::post('save-ffl-address', 'CheckoutController@saveFflShippingAddress');

                Route::post('save-address', 'CheckoutController@saveAddress');

                Route::post('save-shipping', 'CheckoutController@saveShipping');

                Route::post('save-ffl-shipping', 'CheckoutController@saveFflShipping');

                Route::post('save-payment', 'CheckoutController@savePayment');

                Route::post('cart/apply-coupon', 'CartController@applyCoupon');

                Route::post('cart/remove-coupon', 'CartController@removeCoupon');

                Route::post('save-order', 'CheckoutController@saveOrder');
            });
        });
    });

    Route::group(['middleware' => ['web','locale', 'currency']], function ($router) {
        Route::get('/mobile/{any?}', 'Webkul\PWA\Http\Controllers\SinglePageController@index')->where('any', '.*');
    });

    Route::prefix('paypal/standard')->group(function () {
        Route::get('/pwa/success', 'Webkul\PWA\Http\Controllers\StandardController@success')->name('pwa.paypal.standard.success');

        Route::get('/pwa/cancel', 'Webkul\PWA\Http\Controllers\StandardController@cancel')->name('pwa.paypal.standard.cancel');
    });
?>
