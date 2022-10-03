<?php

namespace Webkul\Authorize\Providers;

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
        Event::listen('bagisto.shop.checkout.payment-method.after', function($viewRenderEventManager){
            $viewRenderEventManager->addTemplate('authorize::checkout.card');
        });

        Event::listen('bagisto.shop.layout.body.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('authorize::checkout.card-script');
        });

        Event::listen('marketplace.account.payment.store.after', '\Webkul\Authorize\Listeners\Authorize@storeSellerCreds');
    }
}