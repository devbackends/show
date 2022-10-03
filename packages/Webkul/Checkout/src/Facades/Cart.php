<?php

namespace Webkul\Checkout\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Cart
 * @package Webkul\Checkout\Facades
 * @mixin \Webkul\Checkout\Cart
 */
class Cart extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cart';
    }
}
