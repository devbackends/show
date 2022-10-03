<?php

namespace Webkul\Payment\Payment;

class Check extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'check';

    public function getRedirectUrl()
    {

    }
}