<?php

namespace Webkul\Authorize\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\Authorize\Models\AuthorizeCard::class,
        \Webkul\Authorize\Models\AuthorizeCustomer::class,
    ];
}