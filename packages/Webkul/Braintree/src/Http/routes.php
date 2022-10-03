<?php
Route::group(['middleware' => ['web']], function () {
    Route::prefix('braintree/payment')->group(function () {

        Route::get('/direct', 'Webkul\Braintree\Http\Controllers\BraintreeController@redirect')->name('braintree.payment.redirect');

        Route::get('/transaction','Webkul\Braintree\Http\Controllers\BraintreeController@transaction')->name('braintree.payment.transaction');
    });
});