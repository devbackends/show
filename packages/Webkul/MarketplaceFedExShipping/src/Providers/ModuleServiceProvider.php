<?php

namespace Webkul\MarketplaceFedExShipping\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\MarketplaceFedExShipping\Models\FedEx::class
    ];
}