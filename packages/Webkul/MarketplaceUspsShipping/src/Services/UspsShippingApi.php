<?php

namespace Webkul\MarketplaceUspsShipping\Services;

use Webkul\MarketplaceUspsShipping\Carriers\MarketplaceUsps;

class UspsShippingApi
{
    protected $xml;

    protected $apiUrl;

    public function __construct(string $xml)
    {
        $this->xml = $xml;

        $this->apiUrl = ('DEVELOPMENT' == core()->getConfigData('sales.carriers.mpusps.mode'))
            ? 'https://stg-production.shippingapis.com/ShippingAPI.dll'
            : 'https://production.shippingapis.com/ShippingAPI.dll';
    }

    public function execute(): array
    {
        $allowedServices = app(MarketplaceUsps::class)->getServices();

        $result = [
            'status' => true,
            'data' => [],
        ];
        try {
            $response = $this->curl();

            foreach ($response['Package']['Postage'] as $service) {
                $code = $service['@attributes']['CLASSID'];
                if (!isset($allowedServices[$code])) continue;

                $result['data'][$code] = [
                    'title' => $allowedServices[$code],
                    'rate' => $service['Rate'],
                ];
            }

        } catch (\Exception $e) {
            $result['status'] = false;
            $result['message']= $e->getMessage();
            return $result;
        }

        return $result;
    }

    protected function curl()
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-type: text/xml",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "API=RateV4&XML=" . $this->xml);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode(json_encode(simplexml_load_string($response)), 1);
    }

}