<?php

namespace Devvly\ClearentPayment\Payment;

use Webkul\Payment\Payment\Payment;

abstract class Clearent extends Payment
{

    /**
     * To redirect to the stripe payment page
     */
    public function getClearentUrl()
    {
        return route('clearent.make.payment');
    }
}