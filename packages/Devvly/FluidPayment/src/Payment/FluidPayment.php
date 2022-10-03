<?php

namespace Devvly\FluidPayment\Payment;

use Webkul\Payment\Payment\Payment;

class FluidPayment extends Payment
{
    protected $code = 'fluid';

    /**
     * Get the redirect url for redirecting to
     */
    public function getRedirectUrl(): string
    {
        return route('fluid.make.transaction');
    }
}