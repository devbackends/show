<?php

namespace Webkul\API\Listeners;

use Webkul\API\Jobs\CacheProductsFeed;
use Webkul\Product\Models\Product as ProductModel;

class Product
{

    public function __construct()
    {
    }

    public function onProductSaved(ProductModel $product)
    {
        $this->cache();
    }

    public function onProductDeleted(string $productId)
    {
        $this->cache();
    }

    protected function cache() {
        CacheProductsFeed::dispatch();
    }

}