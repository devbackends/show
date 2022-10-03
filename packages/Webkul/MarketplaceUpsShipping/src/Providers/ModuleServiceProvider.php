<?php

namespace Webkul\MarketplaceUpsShipping\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\MarketplaceUpsShipping\Models\Ups::class
    ];
}