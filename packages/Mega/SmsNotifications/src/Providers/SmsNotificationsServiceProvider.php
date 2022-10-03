<?php


namespace Mega\SmsNotifications\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;


class SmsNotificationsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/routes.php');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'megaSmsNotifications');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'megaSmsNotifications');
        $this->app->register(EventServiceProvider::class);
        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('vendor/mega/smsnotification/assets'),
        ], 'public');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

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
