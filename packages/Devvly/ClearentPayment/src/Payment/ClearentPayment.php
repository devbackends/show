<?php

namespace Devvly\ClearentPayment\Payment;

class ClearentPayment extends Clearent
{
    protected $code = 'clearent';

    /**
     * Get the redirect url for redirecting to
     */
    public function getRedirectUrl()
    {
        return route('clearent.make.payment');
    }

    /**
     * Clearent web URL generic getter
     *
     * @param array $params
     * @return string
     */
    public function getClearentUrl($params = [])
    {
        $this->getRedirectUrl();
    }
}