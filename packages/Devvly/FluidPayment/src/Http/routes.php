<?php

use Devvly\FluidPayment\Http\Controllers\API\CardController;
use Devvly\FluidPayment\Http\Controllers\API\FluidController;
use Devvly\FluidPayment\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web']], function () {
    Route::prefix('/fluid')->group(function () {

        // Api
        Route::prefix('/api')->group(function () {

            Route::get('/get-tokenizer-info/{sellerId}', [FluidController::class, 'getTokenizerinfo']);

            Route::prefix('/card')->group(function () {
                Route::get('/{customerId}/{sellerId}', [CardController::class, 'get'])->name('fluid.api.cards.get');
                Route::post('/{cardId}', [CardController::class, 'update'])->name('fluid.api.cards.update');
                Route::delete('/{cardId}', [CardController::class, 'delete'])->name('fluid.api.cards.delete');
            });
        });

        Route::get('/transaction', [PaymentController::class, 'createTransaction'])->name('fluid.make.transaction');
    });
});