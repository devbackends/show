<?php

namespace Webkul\Payment\Payment;

class CashSale extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'cashsale';

    public function getRedirectUrl()
    {

    }
}