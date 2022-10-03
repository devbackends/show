<?php

namespace Devvly\FluidPayment\Services;

use Devvly\FluidPayment\Models\FluidCustomer as FluidCustomerModel;

class FluidCustomer
{
    /**
     * @var FluidApi
     */
    protected $apiService;

    /**
     * @var FluidCustomerModel
     */
    protected $customer;

    /**
     * FluidCustomer constructor.
     */
    public function __construct()
    {
        $this->apiService = new FluidApi(
            core()->getConfigData('sales.paymentmethods.fluid.api_key'),
            config('services.2acommerce.gateway_url')
        );

        $this->customer = FluidCustomerModel::query()
            ->leftJoin('marketplace_sellers', 'marketplace_sellers.id', '=', 'fluid_customers.seller_id')
            ->select('fluid_customers.*')
            ->where('marketplace_sellers.customer_id', '=', auth()->guard('customer')->user()->id)
            ->first();
    }

    /**
     * Get fluid_customers table object, which is related to customer on fluid side
     *
     * @param string $token
     * @param int $sellerId (Why it is -1 by default? Because we have seller with id 0)
     * @param array $billingInfo
     * @return FluidCustomerModel|null|object
     */
    public function getCustomer(string $token = '', int $sellerId = -1, array $billingInfo = []): ?FluidCustomerModel
    {
        // If customer already exist - return
        if ($this->customer) {
            return $this->customer;
        }

        // Create new customer
        if (!empty($token) && $sellerId >= 0 && !empty($billingInfo)) {
            return $this->createCustomer($token, $sellerId, $billingInfo);
        }

        return null;
    }

    /**
     * Create customer first on fluid side and then on our (fluid customers table)
     *
     * @param string $token
     * @param array $billingInfo
     * @param int $sellerId
     * @return FluidCustomerModel|null
     */
    public function createCustomer(string $token, int $sellerId, array $billingInfo): ?FluidCustomerModel
    {
        // Collect optiosn for billing address and user info for customer creation
        $fluidBillingInfo = [
            'first_name' => explode(' ', $billingInfo['name'])[0],
            'last_name' => explode(' ', $billingInfo['name'])[1] ?? '',
            'line_1' => $billingInfo['address'], // by doc it should be `address_line_1`, but it works only with `line_1`
            'city' => $billingInfo['city'],
            'state' => $billingInfo['state'],
            'postal_code' => $billingInfo['zip'],
            'country' => $billingInfo['country'],
            'email' => $billingInfo['email'],
        ];

        // Create customer in fluid
        $fluidCustomer = $this->apiService->createCustomer($token, $fluidBillingInfo);

        // Create customer in our system
        if (! empty($fluidCustomer)) {
            $this->customer = new FluidCustomerModel();
            $this->customer->customer_id = $fluidCustomer['id'];
            $this->customer->payment_method_id = $fluidCustomer['payment_method_id'];
            $this->customer->card_details = $this->collectCustomerCardDetails($fluidCustomer['card_details']);
            $this->customer->seller_id = $sellerId;
            $this->customer->is_approved = 0;
            return $this->customer->save() ? $this->customer : null;
        }

        return null;
    }

    /**
     * @param string $token
     * @return bool
     */
    public function updateCustomerCard(string $token): bool
    {
        $customer = $this->getCustomer();
        if (!$customer) return false;

        // Update card on fluid side
        $result = $this->apiService->updateCustomer($customer->customer_id, $customer->payment_method_id, $token);
        if (empty($result)) return false;

        // Update customer card details on our side
        $customer->card_details = $this->collectCustomerCardDetails($result['card_details']);

        return $customer->save();
    }


    /**
     * @param array $card
     * @return string
     */
    protected function collectCustomerCardDetails(array $card): string
    {
        $lastFour = substr($card['masked_number'], -4);
        $expirationDate = '('.$card['expiration_date'].')';
        return $lastFour . ' ' . $expirationDate;
    }

}