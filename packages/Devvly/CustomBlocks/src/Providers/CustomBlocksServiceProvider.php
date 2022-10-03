<?php

namespace Devvly\CustomBlocks\Providers;

use Illuminate\Support\ServiceProvider;

class CustomBlocksServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
      include __DIR__ . '/../Http/routes.php';


      $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'customblocks');
      $this->loadMigrationsFrom(__DIR__ .'/../Database/Migrations');
      $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'customblocks');
      $this->publishAssets();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    public function publishAssets()
    {
      $assets_path = __DIR__ . '/../../publishable/assets';
      $this->publishes([
          $assets_path => public_path('vendor/devvly/customblocks/assets'),
      ], 'public');
    }
}