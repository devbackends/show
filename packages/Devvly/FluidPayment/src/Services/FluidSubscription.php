<?php

namespace Devvly\FluidPayment\Services;

use Devvly\FluidPayment\Models\FluidCustomer as FluidCustomerModel;
use Illuminate\Support\Str;

class FluidSubscription
{
    /**
     * @var FluidApi
     */
    protected $apiService;

    /**
     * FluidSubscription constructor.
     */
    public function __construct()
    {
        $this->apiService = new FluidApi(
            core()->getConfigData('sales.paymentmethods.fluid.api_key'),
            config('services.2acommerce.gateway_url')
        );
    }

    /**
     * @param FluidCustomerModel $customer
     * @param array $options
     * @return bool
     */
    public function createSubscription(FluidCustomerModel $customer, array $options): bool
    {
        // Initial charge
        $initialChargeResult = $this->initialSubscriptionCharge([
            'amount' => $options['amount'],
            'customer_id' => $customer->customer_id,
            'payment_method_id' => $customer->payment_method_id,
            'ip_address' => $options['ip_address']
        ]);
        if (!$initialChargeResult) return false;

        // Create subscription
        $subscription = $this->apiService->createSubscription([
            'customer_id' => $customer->customer_id,
            'payment_method_id' => $customer->payment_method_id,
            'amount' => (int)round(round($options['amount'], 2) * 100), // in cents,
            'subscription_type' => $options['subscriptionType'],
            'ip_address' => $options['ip_address']
        ]);

        // Update customer with subscripton id on our side
        $customer->subscription_id = $subscription['id'];
        $customer->save();

        return true;
    }

    /**
     * @param array $options
     * @return bool
     */
    public function updateSubscription(array $options): bool
    {
        return true;
    }

    /**
     * @param FluidCustomerModel $customer
     * @return bool
     */
    public function deleteSubscription(FluidCustomerModel $customer): bool
    {
        return $this->apiService->deleteSubscription($customer->subscription_id);
    }

    /**
     * @param array $options
     * @return bool
     */
    protected function initialSubscriptionCharge(array $options): bool
    {
        $options = [
            'type' => 'sale',
            'amount' => (int)round(round($options['amount'], 2) * 100), // in cents
            'order_id' => Str::random(15),
            'payment_method' => [
                'customer' => [
                    'id' => $options['customer_id'],
                    'payment_method_id' => $options['payment_method_id'],
                    'payment_method_type' => 'card',
                ],
            ],
            'ip_address' => $options['ip_address']

        ];
        $response = $this->apiService->createTransaction($options);
        return $response['status'] === 'success' || $response['status'] === 'pending_settlement';
    }

}