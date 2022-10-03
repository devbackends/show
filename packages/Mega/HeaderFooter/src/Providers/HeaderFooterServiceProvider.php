<?php

namespace Mega\HeaderFooter\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class HeaderFooterServiceProvider extends ServiceProvider
{

    public function boot(Router $router)
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'megaheaderfooter');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'megaheaderfooter');
        $this->app->register(EventServiceProvider::class);

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
            dirname(__DIR__) . '/Config/general.php', 'general'
        );

    }

}