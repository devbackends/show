<?php

use Webkul\BookingProduct\Http\Controllers\Api\BookingProductController;

Route::prefix('/booking-api')->group(function () {
    Route::get('/booking-slots/{bookingProductId}', [BookingProductController::class, 'getSlots'])->name('booking_product.slots.index');
    Route::get('/week-slot-durations/{bookingProductId}', [BookingProductController::class, 'getWeekSlotDurations']);
    Route::get('/get-tickets/{bookingProductId}', [BookingProductController::class, 'getTickets']);
});