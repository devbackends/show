<?php

namespace Devvly\ElasticSearch\Providers;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Platformsh\ConfigReader\Config;
use Illuminate\Support\ServiceProvider;
use Devvly\ElasticSearch\Console\Commands\Elasticsearch;
use Devvly\ElasticSearch\Repositories\ProductFlatRepository;
use Webkul\Product\Repositories\ProductFlatRepository as MainProductFlatRepository;
use Devvly\ElasticSearch\Repositories\CategoryRepository;
use Webkul\Category\Repositories\CategoryRepository as MainCategoryRepository;
use Devvly\ElasticSearch\Repositories\SellerRepository;
use Webkul\Marketplace\Repositories\SellerRepository as MainSellerRepository;
use Webkul\Marketplace\Repositories\OrderItemRepository;


class ElasticSearchServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Register console command
        $this->commands([
            Elasticsearch::class
        ]);

        app()->register(EventServiceProvider::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/services.php', 'services'
        );

        $this->registerElasticsearchClient();

        $this->registerRepositories();
    }

    protected function registerElasticsearchClient()
    {
        if (config('app.env') !== 'local') {
            $credentials = (new Config())->credentials('essearch');
            $hosts = [
                [
                    'scheme' => $credentials['scheme'],
                    'host' => $credentials['host'],
                    'port' => $credentials['port'],
                ]
            ];
        } else {
            $hosts = config('services.elastic-search.hosts');
        }
        app()->bind(Client::class, function () use ($hosts) {
            return ClientBuilder::create()
                ->setHosts($hosts)
                ->build();
        });
    }

    protected function registerRepositories()
    {
        app()->bind(MainProductFlatRepository::class, function ($app) {
            return config('services.elastic-search.enabled')
                ? new ProductFlatRepository($app->make(Client::class), $app)
                : new MainProductFlatRepository($app);
        });
        app()->bind(MainCategoryRepository::class, function ($app) {
            return config('services.elastic-search.enabled')
                ? new CategoryRepository($app->make(Client::class), $app)
                : new MainCategoryRepository($app);
        });
        app()->bind(MainSellerRepository::class, function ($app) {
            return config('services.elastic-search.enabled')
                ? new SellerRepository($app->make(Client::class), $app)
                : new MainSellerRepository($app->make(OrderItemRepository::class), $app);
        });
    }
}
