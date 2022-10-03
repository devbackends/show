<?php

namespace Webkul\TableRate\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\TableRate\Models\SuperSet::class,
        \Webkul\TableRate\Models\SuperSetRate::class,
        \Webkul\TableRate\Models\ShippingRate::class
    ];
}