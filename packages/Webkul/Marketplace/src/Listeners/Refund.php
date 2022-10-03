<?php

namespace Webkul\Marketplace\Listeners;

use Illuminate\Support\Facades\Mail;
use Webkul\Marketplace\Repositories\RefundRepository;

/**
 * Refund event handler
 *
 * @author    Naresh Verma <naresh.verma@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Refund
{
    /**
     * RefundRepository object
     *
     * @var Product
    */
    protected $refund;

    /**
     * Create a new customer event listener instance.
     *
     * @param  Webkul\Marketplace\Repositories\RefundRepository $refund
     * @return void
     */
    public function __construct(
        RefundRepository $refund
    )
    {
        $this->refund = $refund;
    }

    /**
     * After sales refund creation, create marketplace refund
     *
     * @param mixed $refund
     */
    public function afterRefund($refund)
    {
        $this->refund->create(['refund' => $refund]);
    }
}