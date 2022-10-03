<?php

namespace Webkul\Payment\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Payment
 * @package Webkul\Payment\Facades
 * @mixin \Webkul\Payment\Payment
 */
class Payment extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'payment';
    }
}
