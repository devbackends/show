<?php

namespace Devvly\FluidPayment\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class FluidPaymentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/../Http/routes.php';
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'fluid');
        $this->loadMigrationsFrom(__DIR__ .'/../Database/Migrations');
        $this->publishAssets();

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'fluid');

        Event::listen('marketplace.account.payment.store.after', '\Devvly\FluidPayment\Listeners\Fluid@storeSellerCreds');
        Event::listen('admin.marketplace.customer.update.after', '\Devvly\FluidPayment\Listeners\Fluid@updateIsApproved');

//        Event::listen('bagisto.shop.customers.account.profile.view.after', function ($viewRenderEventManager){
//            $viewRenderEventManager->addTemplate('fluid::shop.cards.index');
//        });
        Event::listen('marketplace.sellers.account.settings.payment.after', function ($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('fluid::shop.sellers.account.settings.payment.fluid');
        });
        Event::listen('bagisto.admin.customer.edit.after', function ($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('fluid::admin.customers.edit');
        });
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
     * Merge the FluidPayment configuration with the admin panel
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
          $assets_path => public_path('vendor/devvly/fluidpayment/assets'),
      ], 'public');
    }
}