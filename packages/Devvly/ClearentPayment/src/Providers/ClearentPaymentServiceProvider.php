<?php

namespace Devvly\ClearentPayment\Providers;

use Devvly\Clearent\Client;
use Illuminate\Log\Logger;
use Illuminate\Support\ServiceProvider;

class ClearentPaymentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
      include __DIR__ . '/../Http/routes.php';

      include __DIR__ . '/../Http/front-routes.php';

      $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'clearent');
      $this->loadMigrationsFrom(__DIR__ .'/../Database/Migrations');
      $this->loadFactoriesFrom(__DIR__ .'/../Database/Factories');
      $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'clearent');
      $this->app->register(EventServiceProvider::class);
      $this->publishAssets();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
      $this->registerConfig();
      $this->app->singleton(Client::class, function($app) {
        $logger = $this->app->get(Logger::class);
        $private_key = core()->getConfigData('sales.paymentmethods.clearent.private_key');
        $public_key = core()->getConfigData('sales.paymentmethods.clearent.public_key');
        $dependencies = [
            'clearentApiUrl' => env('CLEARENT_API_URL'),
            'clearentApiPrivateKey' => $private_key,
            'clearentApiPublicKey' => $public_key,
            'clearentSoftwareType' => env('CLEARENT_API_SOFTWARE_TYPE'),
            'clearentSoftwareVersion' => env('CLEARENT_API_SOFTWARE_VERSION'),
            'logger' => $logger,
        ];
        $dependencies = array_values($dependencies);
        $api = new Client(...$dependencies);
        return $api;
      });
    }

    /**
     * Merge the ClearentPayment configuration with the admin panel
     */
    public function registerConfig()
    {
      $this->mergeConfigFrom(
          dirname(__DIR__) . '/Config/system.php', 'core'
      );

      $this->mergeConfigFrom(
          dirname(__DIR__) . '/Config/paymentmethods.php', 'paymentmethods'
      );
    }

    public function publishAssets()
    {
      $assets_path = __DIR__ . '/../../publishable/assets';
      $this->publishes([
          $assets_path => public_path('vendor/devvly/clearentpayment/assets'),
      ], 'public');
    }
}