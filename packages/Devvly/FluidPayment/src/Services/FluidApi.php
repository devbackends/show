<?php

namespace Devvly\FluidPayment\Services;

use Carbon\Carbon;
use Devvly\FluidPayment\Models\FluidCard;

class FluidApi
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $apiUrl;

    /**
     * Fluid constructor.
     * @param string $apiKey
     * @param string $apiUrl
     */
    public function __construct(string $apiKey, string $apiUrl)
    {
        $this->apiKey = $apiKey;
        $this->apiUrl = $apiUrl;
    }

    /**
     * Create fluid transaction
     *
     * @param array $requestData
     * @return array|null
     */
    public function createTransaction(array $requestData): ?array
    {
        // Make request
        $response = $this->curlRequest('POST', 'transaction', $requestData);
        // Handle card decline case
        if (!isset($response['data'])) {
            return [
                'status' => 'error',
                'msg' => 'Something went wrong, '.$response['msg']
            ];
        }
        if (!isset($response['data']) || $response['data']['status'] !== 'success') {

            $response['status'] = $response['data']['status'];
            if ($response['status'] === 'declined') {
                $response['msg'] = 'Your card was declined';
            }
       }


        if ($response['status'] === 'success' || $response['status'] === 'pending_settlement') {
            // Save card
            $this->saveCard($requestData, $response['data']);
        }

        return $response;
    }

    /**
     * @param string $transactionId
     * @param int $amount
     * @return mixed
     */
    public function createRefund(string $transactionId, int $amount)
    {
        return $this->curlRequest('POST', 'transaction/'.$transactionId.'/refund', [
            'amount' => $amount
        ]);
    }

    /**
     * @param string $token
     * @param array $billingInfo
     * @return array
     */
    public function createCustomer(string $token, array $billingInfo): array
    {
        $response = $this->curlRequest('POST', 'vault/customer', [
            'default_payment' => [
                'token' => $token,
            ],
            'default_billing_address' => $billingInfo
        ]);
        if ($response['status'] === 'success') {
            return [
                'id' => $response['data']['id'],
                'payment_method_id' => $response['data']['data']['customer']['defaults']['payment_method_id'],
                'card_details' => $response['data']['data']['customer']['payments']['cards'][0],
            ];
        }
        return [];
    }

    public function updateCustomer(string $customerId, string $paymentMethodId, string $token): array
    {
        $url = 'vault/customer/'.$customerId.'/token/'.$paymentMethodId;
        $response = $this->curlRequest('POST', $url, [
            'token' => $token,
        ]);
        if ($response['status'] === 'success') {
            return [
                'id' => $response['data']['id'],
                'payment_method_id' => $response['data']['data']['customer']['defaults']['payment_method_id'],
                'card_details' => $response['data']['data']['customer']['payments']['cards'][0],
            ];
        }
        return [];
    }

    /**
     * @param string $subscriptionId
     * @return array
     */
    public function getSubscription(string $subscriptionId): array
    {
        $response = $this->curlRequest('GET', 'recurring/subscription/'.$subscriptionId);
        if ($response['status'] === 'success') {
            return $response['data'];
        }
        return [];
    }

    /**
     * @param array $options
     * @return array
     */
    public function createSubscription(array $options): array
    {
        $options = [
            'customer' => [
                'id' => $options['customer_id'],
                'payment_method_type' => 'card',
                'payment_method_id' => $options['payment_method_id'],
            ],
            'amount' => $options['amount'],
            'billing_cycle_interval' => 1,
            'billing_frequency' => 'monthly',
            'duration' => 0,
            'billing_days' => Carbon::today()->format('d'),
            'next_bill_date' => Carbon::today()->addMonth()->format('Y-m-d'),
            'ip_address' => $options['ip_address']
        ];
        $response = $this->curlRequest('POST', 'recurring/subscription', $options);

        if ($response['status'] === 'success') {
            return $response['data'];
        }
        return [];
    }

    /**
     * @param string $subscriptionId
     * @return bool
     */
    public function deleteSubscription(string $subscriptionId): bool
    {
        $response = $this->curlRequest('DELETE', 'recurring/subscription/'.$subscriptionId);
        return $response['status'] === 'success';
    }

    /**
     * Store customer card in database
     *
     * @param array $requestData
     * @param array $responseData
     */
    protected function saveCard(array $requestData, array $responseData): void
    {
        // Create if it is first tiem when user uses this card
        if (isset($requestData['payment_method']['token'])
            && !empty($responseData['customer_payment_ID'])
            && !empty($responseData['customer_id'])) {
            $card = new FluidCard();
            $card->nickname = $requestData['card_nickname'];
            $card->last_four = $responseData['response_body']['card']['last_four'];
            $card->expiration_date = $responseData['response_body']['card']['expiration_date'];
            $card->fluid_card_id = $responseData['customer_payment_ID'];
            $card->fluid_customer_id = $responseData['customer_id'];
            $card->customer_id = $requestData['customer_id'];
            $card->seller_id = $requestData['seller_id'];
            $card->save();
        }
    }

    /**
     * Make a HTTP request to specidied api endpoint via curl library
     *
     * @param string $type
     * @param string $url
     * @param array $options
     * @return mixed
     */
    protected function curlRequest(string $type, string $url, array $options = [])
    {
        $curl = curl_init();

        $optArray = [
            CURLOPT_URL => $this->apiUrl.'/api/'.$url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => [
                'Authorization: '.$this->apiKey,
                'Content-Type: application/json'
            ],
        ];
        if ($type === 'POST') {
            $optArray[CURLOPT_CUSTOMREQUEST] = 'POST';
            $optArray[CURLOPT_POSTFIELDS] = json_encode($options);
        } elseif ($type === 'DELETE') {
            $optArray[CURLOPT_CUSTOMREQUEST] = 'DELETE';
        }

        curl_setopt_array($curl, $optArray);

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, 1);
    }
}