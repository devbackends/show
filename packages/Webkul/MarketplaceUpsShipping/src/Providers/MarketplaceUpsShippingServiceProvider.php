<?php

namespace Webkul\MarketplaceUpsShipping\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class MarketplaceUpsShippingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        include __DIR__ . '/../Http/front-routes.php';

        $this->app->register(ModuleServiceProvider::class);

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'marketplace_ups');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'marketplace_ups');

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
