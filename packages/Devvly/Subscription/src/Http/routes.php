<?php




Route::group(['middleware' => ['web', 'admin']], function () {
    Route::post('subscription/hooks', 'Devvly\Subscription\Http\Controllers\SubscriptionController@hooks')->name('subscription.hooks');

    Route::prefix('admin/subscription')->group(function () {
        Route::get('/index', 'Devvly\Subscription\Http\Controllers\SubscriptionController@index')->name('subscription.index');
        Route::get('/create', 'Devvly\Subscription\Http\Controllers\SubscriptionController@create')->name('subscription.create');
        Route::post('/store', 'Devvly\Subscription\Http\Controllers\SubscriptionController@store')->name('subscription.store');
        Route::post('/store_card', 'Devvly\Subscription\Http\Controllers\SubscriptionController@storeCard')->name('subscription.store_card');
        Route::get('/settings', 'Devvly\Subscription\Http\Controllers\SubscriptionController@settings')->name('subscription.settings');
        Route::post('/check-coupon', 'Devvly\Subscription\Http\Controllers\SubscriptionController@checkCoupon')->name('subscription.check-coupon');

        Route::get('/subscribe', 'Devvly\Subscription\Http\Controllers\SubscriptionController@subscribe')->name('subscription.subscribe');
        Route::get('/subscription-detail', 'Devvly\Subscription\Http\Controllers\SubscriptionController@subscribtionDetail')->name('subscription.subscription-detail');
    });
});