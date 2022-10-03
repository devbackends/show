<?php

namespace Devvly\ClearentPayment\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Devvly\ClearentPayment\Models\ClearentCart::class,
        \Devvly\ClearentPayment\Models\ClearentCard::class,
    ];
}