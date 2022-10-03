<?php

namespace Webkul\Payment\Payment;

class BankTransfer extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'banktransfer';

    public function getRedirectUrl()
    {

    }
}