<?php

namespace Webkul\MarketplaceUspsShipping\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\MarketplaceUspsShipping\Models\Usps::class
    ];
}