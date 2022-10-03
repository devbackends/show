<?php

namespace Devvly\OnBoarding\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * HelloWorld service provider
 *
 * @author    Jane Doe <janedoe@gmail.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OnBoardingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/../Http/routes.php';
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'OnBoarding');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'OnBoarding');
        $this->loadMigrationsFrom(__DIR__ .'/../Database/Migrations');
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/menu.php', 'menu.admin'
        );
        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('vendor/devvly/onboarding/assets'),
        ], 'public');
        $this->register();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
      $stop = null;
    }
}