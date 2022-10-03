<?php

namespace Webkul\Authorize\Payment;

use Webkul\Payment\Payment\Payment;

/**
 * Authorize class
 *
 * @author    Shaiv Roy <shaiv.roy361@webkul.com>
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
abstract class Authorize extends Payment
{

    /**
     * To redirect to the stripe payment page
     */
    public function getAuthorizeUrl()
    {
        return route('authorize.make.payment');
    }
}