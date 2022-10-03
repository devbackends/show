<?php

Route::group(['middleware' => ['web']], function () {

    Route::prefix('checkout')->group(function () {
        Route::get('/stripe/card/delete', 'Webkul\Stripe\Http\Controllers\StripeConnectController@deleteCard')->name('stripe.delete.saved.cart');

        Route::get('/sendtoken/{cartId}', 'Webkul\Stripe\Http\Controllers\StripeConnectController@collectToken')->name('stripe.get.token');

        Route::get('/create/charge', 'Webkul\Stripe\Http\Controllers\StripeConnectController@createCharge')->name('stripe.make.payment');

        Route::post('/save/card', 'Webkul\Stripe\Http\Controllers\StripeConnectController@saveCard')->name('stripe.save.card');

        Route::post('/saved/card/payment', 'Webkul\Stripe\Http\Controllers\StripeConnectController@savedCardPayment')->name('stripe.saved.card.payment');

        Route::get('/redirect/stripe', 'Webkul\Stripe\Http\Controllers\StripeConnectController@createCharge')->name('stripe.standard.redirect');

        Route::get('/payment/cancel', 'Webkul\Stripe\Http\Controllers\StripeConnectController@paymentCancel')->name('stripe.payment.cancel');

        Route::get('/stripe/get-cards', 'Webkul\Stripe\Http\Controllers\StripeConnectController@getCustomerCards')->name('stripe.get-customer-card');

        Route::get('/get-stripe-mode/{sellerId}','Webkul\Stripe\Http\Controllers\StripeConnectController@getStripeMode')->name('stripe.get-stripe-mode');
    });
});
