<?php

namespace Webkul\API\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Product
        Event::listen('catalog.product.create.after', 'Webkul\API\Listeners\Product@onProductSaved');
        Event::listen('catalog.product.update.after', 'Webkul\API\Listeners\Product@onProductSaved');
        Event::listen('catalog.product.delete.after', 'Webkul\API\Listeners\Product@onProductDeleted');

        // Marketplace fully new product
/*        Event::listen('catalog.marketplace.product.create.after', 'Webkul\API\Listeners\Product@onProductSaved');*/
        Event::listen('catalog.marketplace.product.update.after', 'Webkul\API\Listeners\Product@onProductSaved');
        Event::listen('catalog.marketplace.product.delete.after', 'Webkul\API\Listeners\Product@onProductDeleted');
    }
}