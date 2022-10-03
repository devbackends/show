<?php

namespace Webkul\MarketplaceFedExShipping\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class MarketplaceFedExShippingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->app->register(ModuleServiceProvider::class);

        include __DIR__ . '/../Http/front-routes.php';

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'marketplace_fedex');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'marketplace_fedex');

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/customers/account/partials' => resource_path('views/vendor/shop/customers/account/partials'),
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/carriers.php', 'carriers'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/menu.php', 'menu.customer'
        );
    }
}
