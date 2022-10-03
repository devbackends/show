<?php
namespace Webkul\Braintree\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

/**
* Braintree service provider
*
* @author    Aayush Bhatt <aayush.bhatt172@webkul.com>
* @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
*/
class BraintreeServiceProvider extends ServiceProvider
{
    /**
    * Bootstrap services.
    *
    * @return void
    */
    public function boot()
    {
        include __DIR__ . '/../Http/routes.php';

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'braintree');

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
     * register Config from
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
}