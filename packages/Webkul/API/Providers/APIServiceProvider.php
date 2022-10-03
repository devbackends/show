<?php

namespace Webkul\API\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\API\Console\Commands\WikiArmsFeed;

class APIServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
            WikiArmsFeed::class,
        ]);
        app()->register(EventServiceProvider::class);
        $this->loadRoutesFrom(__DIR__.'/../Http/routes.php');
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
