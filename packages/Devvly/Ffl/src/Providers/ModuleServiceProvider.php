<?php

namespace Devvly\Ffl\Providers;

use Devvly\Ffl\Models\Ffl;
use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Ffl::class,
    ];
}
