<?php

namespace Devvly\Subscription\Providers;

use Devvly\Clearent\Resources\Resources;
use Devvly\Subscription\Models\Company;
use Devvly\Clearent\Client;
use Devvly\Subscription\Services\SubscriptionManager;
use Illuminate\Log\Logger;
use Illuminate\Support\ServiceProvider;

/**
 * HelloWorld service provider
 *
 * @author    Jane Doe <janedoe@gmail.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SubscriptionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        include __DIR__ . '/../Http/routes.php';
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'subscription');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'subscription');
        $this->loadMigrationsFrom(__DIR__ .'/../Database/Migrations');
        $this->loadFactoriesFrom(__DIR__ .'/../Database/Factories');
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/menu.php', 'menu.admin'
        );
        $this->publishes([
          __DIR__ . '/../../publishable/assets' => public_path('vendor/devvly/subscription/assets'),
        ], 'public');
        $this->publishes([
          __DIR__.'/../../publishable/config/clearent.php' => config_path('clearent.php'),
        ]);
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
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
      $this->app->singleton(Resources::class, function ($app) {
        return new Resources($this->app->get(Client::class));
      });
      $this->app->singleton(SubscriptionManager::class, function($app) {
        $logger = $this->app->get(Logger::class);
        $client = $this->app->get(Client::class);
        $resources = new Resources($client);
        $manager = new SubscriptionManager($logger, $resources);
        return $manager;
      });
    }
}