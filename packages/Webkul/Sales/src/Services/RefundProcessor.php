<?php

namespace Webkul\Sales\Services;

use Webkul\Sales\Models\Order;

abstract class RefundProcessor
{

    /**
     * @var Order
     */
    protected $order;

    /**
     * RefundProcessor constructor.
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public abstract function isAvailable(): bool;

    /**
     * @param float $amount
     * @return bool
     */
    public abstract function refund(float $amount): bool;

}