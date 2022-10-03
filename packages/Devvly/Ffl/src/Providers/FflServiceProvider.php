<?php

namespace Devvly\Ffl\Providers;


use Devvly\Ffl\Console\Commands\AddZerosToLicenseNumbers;
use Devvly\Ffl\Console\Commands\CheckLicenseFflEzCheck;
use Devvly\Ffl\Services\FflEzCheck\FflEzCheck;
use Devvly\Ffl\Services\NutShell\Api;
use Devvly\Ffl\Services\NutShell\DiscoverUrl;
use DOMDocument;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use PHPHtmlParser\Dom;
use function foo\func;

class FflServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/../Http/routes.php';
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'ffl');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'ffl');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/super-menu.php', 'menu.super-admin'
        );
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/menu.php', 'menu.admin'
        );
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );
        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('vendor/devvly/ffl/assets'),
        ], 'public');
        $this->commands([
            AddZerosToLicenseNumbers::class,
            CheckLicenseFflEzCheck::class,
        ]);
        $this->register();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FflEzCheck::class, function () {
            return new FflEzCheck(new Client([
                'base_url' => FflEzCheck::URL,
            ]), new Dom());
        });
        $this->app
            ->when(Api::class)
            ->needs(ClientInterface::class)
            ->give(function () {
                return (new DiscoverUrl(new Client([
                    'base_url' => config('nutshell.base_url'),
                    'headers'  => [
                        'Authorization' => 'Basic ' . base64_encode(config('nutshell.user_name') . ':' . config('nutshell.api_key')),
                    ],
                    'curl'     => [
                        CURLOPT_CAINFO => Storage::disk('local')->path('certs/nutshell-ssl-roots.pem'),
                    ],
                ]), config('nutshell.user_name')))->discoverUrl();
            });
    }
}
