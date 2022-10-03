<?php

namespace Webkul\MarketplaceSaaS\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class MarketplaceSAASServiceProvider extends ServiceProvider
{
    public function boot(Router $router)
    {
        $this->app->register(EventServiceProvider::class);

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'MarketplaceSaaS');

        $this->publishes([
            dirname(__DIR__) . '/Resources/views/shop/default/sellers/products/index.blade.php' => resource_path('views/vendor/marketplace/shop/default/sellers/products/index.blade.php')
        ]);

        $this->publishes([
            dirname(__DIR__) . '/Resources/views/shop/sellers/products/index.blade.php' => resource_path('views/vendor/marketplace/shop/velocity/products/index.blade.php')
        ]);
    }
}