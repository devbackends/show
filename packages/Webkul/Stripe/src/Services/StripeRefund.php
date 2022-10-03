<?php

namespace Webkul\Stripe\Services;

use Webkul\Stripe\Models\StripeCustomer;
use Webkul\Sales\Services\RefundProcessor;
use Stripe\Stripe as Stripe;
class StripeRefund extends RefundProcessor
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
        if (!empty($this->order->payment->additional)) {
            $orderPaymentAdditional = json_decode($this->order->payment->additional, 1);
            if (isset($orderPaymentAdditional['stripe']) && isset($orderPaymentAdditional['stripe']['charge'])) {
                $charge = $orderPaymentAdditional['stripe']['charge'];
                // Execute refund
                $seller = app('Webkul\Marketplace\Repositories\SellerRepository')->findOneByField('customer_id', auth()->guard('customer')->user()->id);
                $stripeCustomer = app(\Webkul\Stripe\Models\StripeCustomer::class)->where(['seller_id' => $seller->id]);
                if($stripeCustomer){
                    $stripeSecretKey= $stripeCustomer->first()->api_key;
                }
                $stripe = \Stripe\Stripe::setApiKey($stripeSecretKey);
                $stripe = \Stripe\Refund::create(
                    array("charge" => $charge));
                if($stripe->values()[12]=='succeeded'){
                 return true;
                }
            }
        }
        return false;
    }

    /**
     * @return FluidApi
     */
    protected function getApiServiceObject(): FluidApi
    {
        $creds = StripeCustomer::query()->where('seller_id', $this->order->cart->seller_id)->first();
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
            if (isset($orderPaymentAdditional['stripe']) && isset($orderPaymentAdditional['stripe']['transaction_id'])) {
                $transactionId = $orderPaymentAdditional['stripe']['transaction_id'];
            }
        }
        return $transactionId;
    }

}