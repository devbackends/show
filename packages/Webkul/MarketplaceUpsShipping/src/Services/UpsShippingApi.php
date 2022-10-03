<?php

namespace Webkul\MarketplaceUpsShipping\Services;

class UpsShippingApi
{
    const API_URL = 'https://onlinetools.ups.com/ups.app/xml/Rate';

    /**
     * @var string
     */
    protected $xml;

    /**
     * UpsShippingApi constructor.
     * @param string $xml
     */
    public function __construct(string $xml)
    {
        $this->xml = $xml;
    }

    public function execute(): array
    {
        $allowedServices = explode(",", core()->getConfigData('sales.carriers.mpups.services'));

        $result = [
            'status' => true,
            'data' => [],
        ];
        try {
            $response = $this->curl();
            if (isset($response['Response']['ResponseStatusDescription'])
                && $response['Response']['ResponseStatusDescription'] === 'Success') {

                if (isset($response['RatedShipment'])) {
                    foreach ($response['RatedShipment'] as $services) {
                        $code = $services['Service']['Code'];
                        if (!in_array($code, $allowedServices)) continue;

                        $result['data'][$code] = [
                            'title' => $this->getServiceName($code),
                            'rate' => $services['TotalCharges']['MonetaryValue'],
                            'currency' => $services['TotalCharges']['CurrencyCode'],
                            'weight' => $services['BillingWeight']['Weight'],
                            'weightUnit' => $services['BillingWeight']['UnitOfMeasurement']['Code'],
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            $result['status'] = false;
            $result['message']= $e->getMessage();
            return $result;
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function curl(): array
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_URL, self::API_URL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-type: text/xml",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->xml);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode(json_encode(simplexml_load_string($response)), 1);
    }

    /**
     * @param string $code
     * @return string
     */
    protected function getServiceName(string $code): string
    {
        $services = [
            '01'    => 'UPS Next Day Air',
            '02'    => 'UPS 2nd Day Air',
            '03'    => 'UPS Ups Ground',
            '07'    => 'UPS Ups Worldwide Express',
            '08'    => 'UPS Ups Worldwide Expedited',
            '11'    => 'UPS Standard',
            '12'    => 'UPS 3 Day Select',
            '13'    => 'UPS Next Day Air Saver',
            '14'    => 'UPS Next Day Air Early A.M.',
            '54'    => 'UPS Ups Worldwide Express Plus',
            '59'    => 'UPS 2nd Day Air A.M.',
            '65'    => 'UPS World Wide Saver',
            '82'    => 'UPS Today Standard',
            '83'    => 'UPS Today Dedicated Courier',
            '84'    => 'UPS Today Intercity',
            '85'    => 'UPS Today Express',
            '86'    => 'UPS Today Express Saver',
        ];

        return $services[$code] ?? $code;
    }

}