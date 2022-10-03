<?php

namespace Devvly\FflOnBoarding\Providers;

use Illuminate\Support\ServiceProvider;

class FflOnBoardingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->register();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }
}
