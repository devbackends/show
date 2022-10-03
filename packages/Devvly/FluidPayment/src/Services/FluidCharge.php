<?php

namespace Devvly\FluidPayment\Services;

use Illuminate\Support\Str;

class FluidCharge
{
    /**
     * @param array $options
     * @return bool
     */
    public function charge(array $options): bool
    {
        $options = $this->prepareOptions($options);
        if (empty($options)) return false;
        $apiKey = core()->getConfigData('sales.paymentmethods.fluid.api_key');
        $apiUrl = config('services.2acommerce.gateway_url');

        $response = (new FluidApi($apiKey, $apiUrl))->createTransaction($options);
        return $response['status'] === 'success' || $response['status'] === 'pending_settlement';
    }

    /**
     * @param array $options
     * @return array
     */
    protected function prepareOptions(array $options): array
    {
        if (!isset($options['amount']) || !isset($options['sellerPaymentInfo'])) return [];

        $paymentMethod = [];
        if (isset($options['sellerPaymentInfo']['card'])) {
            $paymentMethod['card'] = [
                'entry_type' => 'keyed',
                'number' => $options['sellerPaymentInfo']['card']['number'],
                'expiration_date' => $options['sellerPaymentInfo']['card']['expiration_date'],
                'cvc' => $options['sellerPaymentInfo']['card']['cvc'],
            ];
        } elseif (isset($options['sellerPaymentInfo']['customer'])) {
            $paymentMethod['customer'] = [
                'id' => $options['sellerPaymentInfo']['customer']['id'],
                'payment_method_id' => $options['sellerPaymentInfo']['customer']['paymentMethodId'],
                'payment_method_type' => 'card',
            ];
        }
        if (empty($paymentMethod)) return [];

        return [
            'type' => 'sale',
            'amount' => (int)round(round($options['amount'], 2) * 100), // in cents
            'order_id' => Str::random(15),
            'payment_method' => $paymentMethod,
            'ip_address' => isset($_SERVER["HTTP_CF_CONNECTING_IP"]) ? $_SERVER["HTTP_CF_CONNECTING_IP"] :  $this->getRealIpAddr(),
        ];
    }
    function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}