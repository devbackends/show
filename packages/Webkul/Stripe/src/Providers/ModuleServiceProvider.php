<?php

namespace Webkul\Stripe\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\Stripe\Models\StripeCard::class
    ];
}