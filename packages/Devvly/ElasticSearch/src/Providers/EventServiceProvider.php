<?php

namespace Devvly\ElasticSearch\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Product
        Event::listen('catalog.product.create.after', 'Devvly\ElasticSearch\Listeners\Product@onProductSaved');
        Event::listen('catalog.product.update.after', 'Devvly\ElasticSearch\Listeners\Product@onProductSaved');
        Event::listen('catalog.product.delete.after', 'Devvly\ElasticSearch\Listeners\Product@onProductDeleted');

        // Marketplace fully new product
        /*Event::listen('catalog.marketplace.product.create.after', 'Devvly\ElasticSearch\Listeners\Product@onProductSaved');*/
        Event::listen('catalog.marketplace.product.update.after', 'Devvly\ElasticSearch\Listeners\Product@onProductSaved');
        Event::listen('catalog.marketplace.product.delete.after', 'Devvly\ElasticSearch\Listeners\Product@onProductDeleted');

        // Category
        Event::listen('catalog.category.create.after', 'Devvly\ElasticSearch\Listeners\Category@onCategoryCreated');
        Event::listen('catalog.category.update.after', 'Devvly\ElasticSearch\Listeners\Category@onCategoryUpdated');
        Event::listen('catalog.category.delete.after', 'Devvly\ElasticSearch\Listeners\Category@onCategoryDeleted');

        // Seller
        Event::listen('marketplace.seller.profile.update.after', 'Devvly\ElasticSearch\Listeners\Seller@onSellerSaved');
        Event::listen('marketplace.seller.delete.after', 'Devvly\ElasticSearch\Listeners\Seller@onSellerDeleted');
    }
}