<?php

namespace Webkul\Braintree\Payment;
use Webkul\Payment\Payment\Payment;

/**
 * Braintree Method class
 *
 * @author Aayush Bhatt <aayush.bhatt172@webkul.com>
 * @copyright 2019 Webkul Software Pvt Ltd (https://www.webkul.com)
 */
class BraintreePayment extends Payment {

    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'braintree';

    /**
     * Return Braintree redirect url
     *
     * @var string
     */
    public function getRedirectUrl()
    {
        return route('braintree.payment.redirect');
    }
}