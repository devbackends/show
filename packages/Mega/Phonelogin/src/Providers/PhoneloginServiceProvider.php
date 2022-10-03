<?php

namespace Mega\Phonelogin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Mega\Phonelogin\Exception\WrongVerificationCodeHandler;

class PhoneloginServiceProvider extends ServiceProvider
{
    protected $command = ['Mega\Phonelogin\Console\Commands\DeleteOldCodesCommand'];

    public function boot(Router $router)
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/routes.php');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'megaPhoneLogin');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'megaPhoneLogin');
        $this->app->register(EventServiceProvider::class);
        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('vendor/mega/phonelogin/assets'),
        ], 'public');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->app->bind(
            ExceptionHandler::class,
            WrongVerificationCodeHandler::class
        );
        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('web', \Mega\Phonelogin\Http\Middleware\ValidatePhone::class);
        $this->commands($this->command);
    }



    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/general.php', 'general'
        );
        $this->app->singleton('mega.phonelogin.console.kernel', function($app) {
            $dispatcher = $app->make(\Illuminate\Contracts\Events\Dispatcher::class);
            return new \Mega\Phonelogin\Console\Kernel($app, $dispatcher);
        });

        $this->app->make('mega.phonelogin.console.kernel');
    }
}