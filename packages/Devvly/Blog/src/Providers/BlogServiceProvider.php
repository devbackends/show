<?php

namespace Devvly\Blog\Providers;

use Devvly\Blog\Models\Post;
use Devvly\Blog\Models\PostCategory;
use Devvly\Blog\Models\PostCategoryTranslation;
use Devvly\Blog\Models\PostTranslation;
use Devvly\Blog\Models\Tag;
use Devvly\Blog\Models\TagTranslation;
use Devvly\Blog\Observers\PostCategoryObserver;
use Devvly\Blog\Observers\PostCategoryTranslationObserver;
use Devvly\Blog\Observers\PostObserver;
use Devvly\Blog\Observers\PostTranslationObserver;
use Devvly\Blog\Observers\TagObserver;
use Devvly\Blog\Observers\TagTranslationObserver;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Support\ServiceProvider;

/**
 * Class BlogServiceProvider
 * @package Devvly\Blog\Providers
 */
class BlogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        include __DIR__ . '/../Http/routes.php';
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'blog');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'blog');
        $this->loadMigrationsFrom(__DIR__ .'/../Database/Migrations');
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/menu.php', 'menu.admin'
        );
        $this->publishes([
          __DIR__ . '/../../publishable/assets' => public_path('vendor/devvly/blog/assets'),
        ], 'public');
        $this->app->register(ModuleServiceProvider::class);
    }

    /**
     * Register services.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register()
    {
        $this->registerEloquentFactoriesFrom(__DIR__ . '/../Database/Factories');
    }

    /**
     * Register factories.
     *
     * @param string $path
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function registerEloquentFactoriesFrom($path): void
    {
        $this->app->make(EloquentFactory::class)->load($path);
    }
}