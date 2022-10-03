<?php

namespace Webkul\SAASCustomizer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Company
 * @package Webkul\SAASCustomizer\Facades
 * @mixin \Webkul\SAASCustomizer\Company
 */
class Company extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'company';
    }
}