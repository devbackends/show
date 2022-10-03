<?php

namespace Webkul\Core\Providers;

use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\AliasLoader;
use Webkul\Core\Console\Command\Backup;
use Webkul\Core\Core;
use Webkul\Core\Facades\Core as CoreFacade;
use Webkul\Core\Models\SliderProxy;
use Webkul\Core\Observers\SliderObserver;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Core\Repositories\CoreConfigRepository;
use Webkul\Core\Repositories\CountryRepository;
use Webkul\Core\Repositories\CountryStateRepository;
use Webkul\Core\Repositories\CurrencyRepository;
use Webkul\Core\Repositories\ExchangeRateRepository;
use Webkul\Core\Repositories\LocaleRepository;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/../Http/helpers.php';

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->registerEloquentFactoriesFrom(__DIR__ . '/../Database/Factories');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'core');

        Validator::extend('slug', 'Webkul\Core\Contracts\Validations\Slug@passes');

        Validator::extend('code', 'Webkul\Core\Contracts\Validations\Code@passes');

        Validator::extend('decimal', 'Webkul\Core\Contracts\Validations\Decimal@passes');

        Validator::extend('SkuByCompany', 'Webkul\Core\Contracts\Validations\SkuByCompany@passes');

        $this->publishes([
            dirname(__DIR__) . '/Config/concord.php' => config_path('concord.php'),
        ]);

        SliderProxy::observe(SliderObserver::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFacades();
        $this->registerCommands();
    }

    /**
     * Register Bouncer as a singleton.
     *
     * @return void
     */
    protected function registerFacades()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('core', CoreFacade::class);

        $this->app->singleton(Core::class, function ($app) {
            return new Core(
                $app->make(ChannelRepository::class),
                $app->make(CurrencyRepository::class),
                $app->make(ExchangeRateRepository::class),
                $app->make(CountryRepository::class),
                $app->make(CountryStateRepository::class),
                $app->make(LocaleRepository::class),
                $app->make(CoreConfigRepository::class)
            );
        });
    }

    /**
     * Register factories.
     *
     * @param string $path
     *
     * @return void
     */
    protected function registerEloquentFactoriesFrom($path): void
    {
        $this->app->make(EloquentFactory::class)->load($path);
    }

    /**
     * Register the console commands of this package
     *
     * @return void
     */
    protected function registerCommands(): void
    {
        $this->commands([
            Backup::class,
        ]);
        if ($this->app->runningInConsole()) {

        }
    }
}
