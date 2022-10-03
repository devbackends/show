<?php

namespace Webkul\Stripe\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use View;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('bagisto.admin.layout.head', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('stripe::checkout.style');
        });

        Event::listen('bagisto.shop.layout.head', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('stripe::checkout.style');
        });

        Event::listen('marketplace.sellers.account.settings.payment.after', function ($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('stripe::shop.sellers.account.settings.payment.stripe');
        });

        Event::listen('marketplace.account.payment.store.after', '\Webkul\Stripe\Listeners\Stripe@storeSellerCreds');

    }
}