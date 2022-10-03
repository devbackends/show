<?php

namespace Webkul\BookingProduct\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\BookingProduct\Models\BookingProduct::class,
        \Webkul\BookingProduct\Models\BookingProductDefaultSlot::class,
        \Webkul\BookingProduct\Models\BookingProductEventTicket::class,
        \Webkul\BookingProduct\Models\BookingProductRentalSlot::class,
        \Webkul\BookingProduct\Models\Booking::class,
    ];
}