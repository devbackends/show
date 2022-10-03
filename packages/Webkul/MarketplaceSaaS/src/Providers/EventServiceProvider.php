<?php

namespace Webkul\MarketplaceSaaS\Providers;

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
        Event::listen('saas.company.register.after', 'Webkul\MarketplaceSaaS\Listeners\MarketplaceDataSeeder@handle');
    }
}