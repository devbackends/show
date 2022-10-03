<?php

namespace Devvly\FluidPayment\Services;

use Devvly\FluidPayment\Models\FluidCustomer;
use Webkul\Sales\Services\RefundProcessor;

class FluidRefund extends RefundProcessor
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
        $amount = (int)($amount * 100);

        // Execute refund
        $result = $this->getApiServiceObject()->createRefund($this->getTransactionIdFromOrder(), $amount);
        if(isset($result['data']['status'])){
            return ($result['data']['status'] === 'success' || $result['data']['status'] === 'pending_settlement');
        }
        return true;
    }

    /**
     * @return FluidApi
     */
    protected function getApiServiceObject(): FluidApi
    {
        $creds = FluidCustomer::query()->where('seller_id', $this->order->cart->seller_id)->first();
        return new FluidApi($creds->api_key, core()->getConfigData('sales.paymentmethods.fluid.api_url'));
    }

    /**
     * @return string
     */
    protected function getTransactionIdFromOrder(): string
    {
        $transactionId = '';
        if (!empty($this->order->payment->additional)) {
            $orderPaymentAdditional = json_decode($this->order->payment->additional, 1);
            if (isset($orderPaymentAdditional['fluid']) && isset($orderPaymentAdditional['fluid']['transaction_id'])) {
                $transactionId = $orderPaymentAdditional['fluid']['transaction_id'];
            }
        }
        return $transactionId;
    }

}