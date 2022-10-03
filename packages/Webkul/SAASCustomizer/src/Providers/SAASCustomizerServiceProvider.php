<?php

namespace Webkul\SAASCustomizer\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Validator;
use Webkul\Sales\Providers\ModuleServiceProvider;
use Webkul\SAASCustomizer\Http\Middleware\Locale;
use Webkul\SAASCustomizer\Http\Middleware\CompanyLocale;
use Webkul\SAASCustomizer\Http\Middleware\RedirectIfNotSuperAdmin;
use Webkul\SAASCustomizer\Http\Middleware\Bouncer as BouncerMiddleware;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Webkul\SAASCustomizer\Exceptions\Handler;
use Webkul\Core\Tree;

class SAASCustomizerServiceProvider extends ServiceProvider
{

    public function boot(Router $router)
    {
        include __DIR__ . '/../Http/helpers.php';

        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'saas');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'saas');

        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('vendor/webkul/saas/assets'),
        ], 'public');

        $router->aliasMiddleware('super-locale', Locale::class);
        $router->aliasMiddleware('company-locale', CompanyLocale::class);
        $router->aliasMiddleware('super-admin', RedirectIfNotSuperAdmin::class);
        $router->aliasMiddleware('superadmins', BouncerMiddleware::class);

        //over ride system's default validation DB presence verifier
        $this->registerValidationFactory();

        Validator::extend('slug', 'Webkul\SAASCustomizer\Contracts\Validations\Host@passes');
        Validator::extend('code', 'Webkul\SAASCustomizer\Contracts\Validations\Code@passes');

        $this->app->bind(
            ExceptionHandler::class,
            Handler::class
        );

        $this->composeView();
    }

    /**
     * Compose View
     */
    public function composeView()
    {
        view()->composer(['saas::super.layouts.nav-left', 'saas::super.layouts.nav-aside', 'saas::super.layouts.tabs'], function ($view) {
            $tree = Tree::create();

            foreach (config('menu.super-admin') as $index => $item) {
                $tree->add($item, 'menu');
            }

            $tree->items = company()->sortItems($tree->items);

            $view->with('menu', $tree);
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(EventServiceProvider::class);

        $this->app->register(ModuleServiceProvider::class);

        $this->registerConfig();

        $this->commands([
            \Webkul\SAASCustomizer\Commands\Console\GenerateSU::class
        ]);
    }

    public function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/purge-pool.php', 'purge-pool'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php', 'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/super-menu.php', 'menu.super-admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/excluded-sites.php', 'excluded-sites'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/super-system.php', 'company'
        );
    }

    /**
     * Register the validation factory.
     *
     * @return void
     */
    protected function registerValidationFactory()
    {
        $this->app->singleton('validator', function ($app) {
            $validator = new \Illuminate\Validation\Factory($app['translator'], $app);

            // The validation presence verifier is responsible for determining the existence of
            // values in a given data collection which is typically a relational database or
            // other persistent data stores. It is used to check for "uniqueness" as well.
            if (isset($app['db'], $app['validation.presence'])) {
                $validator->setPresenceVerifier($app['validation.presence']);
            }

            return $validator;
        });
    }
}