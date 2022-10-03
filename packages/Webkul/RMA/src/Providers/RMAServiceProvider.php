<?php

namespace Webkul\RMA\Providers;

use Webkul\Core\Tree;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class RMAServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        include __DIR__ . '/../Http/front-routes.php';
        include __DIR__ . '/../Http/admin-routes.php';

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'rma');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'rma');

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/velocity/customers/account/index.blade.php' => resource_path('themes/velocity/views/customers/account/index.blade.php'),
        ]);

        $this->app->register(RepositoryServiceProvider::class);
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
            dirname(__DIR__) . '/Config/system.php', 'core'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/menu.php', 'menu.customer'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php', 'menu.admin'
        );
    }
}
