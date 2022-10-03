<?php

namespace Devvly\DistributorImport\Providers;

use Devvly\DistributorImport\Console\Commands\Import;
use Devvly\DistributorImport\Console\Commands\RemoveUnnecessaryProducts;
use Devvly\DistributorImport\Console\Commands\TmpQueue;
use Devvly\DistributorImport\Console\Commands\Update;
use Devvly\DistributorImport\Console\Commands\RemoveMarketplaceProducts;
use Devvly\DistributorImport\Console\Commands\Descriptions;
use Devvly\DistributorImport\Console\Commands\AddProductAttribute;
use Devvly\DistributorImport\Console\Commands\AddProductsSku;
use Devvly\DistributorImport\Console\Commands\UpdateDistribute;
use Devvly\DistributorImport\Console\Commands\UpdateInventory;
use Devvly\DistributorImport\Console\Commands\ValidateProductUrl;
use Devvly\DistributorImport\Console\Commands\UpdateProductAttributeValuesOptions;
use Devvly\DistributorImport\Console\Commands\AddProductRestrictions;
use Devvly\DistributorImport\Console\Commands\AddProductWarnings;
use Devvly\DistributorImport\Console\Commands\ConvertSimpleToConfigurable;
use Devvly\DistributorImport\Console\Commands\RsrDeleteProducts;
use Devvly\DistributorImport\Console\Commands\MigratingSubcategoryAttribute;
use Devvly\DistributorImport\Services\Distributor;
use Illuminate\Support\ServiceProvider;


class DistributorImportServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Load package migrations
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        // Publish configuration file
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('distimport.php'),
        ]);

        // Set up singeltons
        $this->app->singleton(Distributor::class, function () {
            return new Distributor(config('distimport.diskName'));
        });

        // Register console command
        $this->commands([
            Import::class,
            Update::class,
            UpdateDistribute::class,
            TmpQueue::class,
            Descriptions::class,
            AddProductAttribute::class,
            AddProductsSku::class,
            UpdateInventory::class,
            UpdateProductAttributeValuesOptions::class,
            AddProductRestrictions::class,
            ConvertSimpleToConfigurable::class,
            ValidateProductUrl::class,
            RsrDeleteProducts::class,
            MigratingSubcategoryAttribute::class,
            AddProductWarnings::class,
            RemoveUnnecessaryProducts::class,
            RemoveMarketplaceProducts::class

        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }
}
