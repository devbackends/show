<?php

namespace Webkul\MarketplaceFedExShipping\Service;

use SoapClient;

class FedexApi
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * FedexApi constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        // Set url
        if (!core()->getConfigData('sales.carriers.mpfedex.sandbox_mode')) {
            $this->apiUrl = 'https://gateway.fedex.com/web-services/rate';
        } else {
            $this->apiUrl = 'https://wsbeta.fedex.com:443/web-services';
        }

        // Set options
        $this->options = $options;
    }

    public function execute(): array
    {
        $allowedServices = explode(",", core()->getConfigData('sales.carriers.mpfedex.services'));

        $response = $this->request();

        if ($response && $response['HighestSeverity'] === 'SUCCESS') {
            if (!empty($response['RateReplyDetails'])) {
                $result = [
                    'status' => true,
                    'data' => [],
                ];
                foreach ($response['RateReplyDetails'] as $responseRate) {
                    if (!in_array($responseRate['ServiceType'], $allowedServices)) continue;
                    //dump($responseRate);

                    $result['data'][$responseRate['ServiceType']] = [
                        'title' => $responseRate['ServiceDescription']['Names'][0]['Value'],
                        'rate' => $responseRate['RatedShipmentDetails']['ShipmentRateDetail']['TotalNetCharge']['Amount'],
                    ];
                }

                return $result;
            }

        }

        return [
            'status' => false,
        ];
    }

    protected function request()
    {
        try {
            $client = new SoapClient(__DIR__ . '/FedexApiWsdls/RateService_v26.wsdl', [
                'trace' => true,
                'stream_context' => stream_context_create([
                    'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
                ])
            ]);
            $client->__setLocation($this->apiUrl);
            return json_decode(json_encode($client->getRates($this->options)), 1);
        } catch (\SoapFault $e) {
            return false;
        }

    }

}