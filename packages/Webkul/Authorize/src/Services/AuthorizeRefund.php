<?php

namespace Webkul\Authorize\Services;

use Webkul\Authorize\Models\AuthorizeCustomer;
use Webkul\Sales\Services\RefundProcessor;
use authorizenet\authorizenet as Authorize;

class AuthorizeRefund extends RefundProcessor
{

    /**
     * @return bool
     */
    public function isAvailable(): bool
    {
        return !empty($this->getTransactionIdFromOrder());
    }

    /**
     * @param float $amount
     * @return bool
     */
    public function refund(float $amount): bool
    {
        // Convert amount to cents

        if (!empty($this->order->payment->additional)) {
            $orderPaymentAdditional = json_decode($this->order->payment->additional, 1);
            if (isset($orderPaymentAdditional['authorize']) && isset($orderPaymentAdditional['authorize']['transId'])) {
                $transId = $orderPaymentAdditional['authorize']['transId'];
                $lastFour = $orderPaymentAdditional['lastFour'];
                $expirationDate = $orderPaymentAdditional['expirationDate'];
                $refund=app('Webkul\Authorize\Helpers\Helper')->refundTransaction($transId,$amount,$lastFour,$expirationDate);
                    if(isset($refund->data['status'])){
                    if($refund->data['status']=='success'){
                        return true;
                    }
                }
            }
        }
        return false;
    }


/*    protected function getApiServiceObject()
    {
        $creds = AuthorizeCustomer::query()->where('seller_id', $this->order->cart->seller_id)->first();
        return new FluidApi($creds->api_key, core()->getConfigData('sales.paymentmethods.fluid.api_url'));
    }*/

    /**
     * @return string
     */
    protected function getTransactionIdFromOrder(): string
    {
        $transactionId = '';
        if (!empty($this->order->payment->additional)) {
            $orderPaymentAdditional = json_decode($this->order->payment->additional, 1);
            if (isset($orderPaymentAdditional['authorize']) && isset($orderPaymentAdditional['authorize']['transId'])) {
                $transactionId = $orderPaymentAdditional['authorize']['transId'];
            }
        }
        return $transactionId;
    }

}