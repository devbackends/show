<?php

namespace Webkul\Authorize\Payment;

/**
 * AuthorizePayment method class
 *
 * @author    Shaiv Roy <shaiv.roy361@webkul.com>
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AuthorizePayment extends Authorize
{
    protected $code = 'authorize';

    /**
     * Get the redirect url for redirecting to
     */
    public function getRedirectUrl()
    {
        return route('authorize.make.payment');
    }

    /**
     * Mp authorize Net web URL generic getter
     *
     * @param array $params
     * @return string
     */
    public function getAuthorizeUrl($params = [])
    {
        $this->getRedirectUrl();
    }
}