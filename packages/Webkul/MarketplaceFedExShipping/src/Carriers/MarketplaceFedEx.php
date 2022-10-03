<?php

namespace Webkul\MarketplaceFedExShipping\Carriers;

use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Marketplace\Models\Seller;
use Webkul\MarketplaceFedExShipping\Models\FedEx;
use Webkul\MarketplaceFedExShipping\Service\FedexApi;
use Webkul\Shipping\Carriers\AbstractShipping;
use Webkul\Checkout\Facades\Cart;

/**
 * Marketplace Table Rate Shipping.
 *
 */
class MarketplaceFedEx extends AbstractShipping
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code = 'mpfedex';

    /**
     * Payment method services
     *
     * @var string
     */
    protected $services = [
        'EUROPE_FIRST_INTERNATIONAL_PRIORITY' => 'FEDEX - Europe First Priority',
        'FEDEX_1_DAY_FREIGHT' => 'FEDEX - 1 Day Freight',
        'FEDEX_2_DAY_FREIGHT' => 'FEDEX - 2 Day Freight',
        'FEDEX_2_DAY' => 'FEDEX - 2 Day',
        'FEDEX_2_DAY_AM' => 'FEDEX - 2 Day AM',
        'FEDEX_3_DAY_FREIGHT' => 'FEDEX - 3 Day Freight',
        'FEDEX_EXPRESS_SAVER' => 'FEDEX - Express Saver',
        'FEDEX_GROUND' => 'FEDEX - Ground',
        'FIRST_OVERNIGHT' => 'FEDEX - First Overnight',
        'GROUND_HOME_DELIVERY' => 'FEDEX - Ground Home Delivery',
        'INTERNATIONAL_ECONOMY' => 'FEDEX - International Economy',
        'INTERNATIONAL_ECONOMY_FREIGHT' => 'FEDEX - International Economy Freight',
        'INTERNATIONAL_FIRST' => 'FEDEX - International First',
        'INTERNATIONAL_GROUND' => 'FEDEX - International Ground',
        'INTERNATIONAL_PRIORITY' => 'FEDEX - International Priority',
        'INTERNATIONAL_PRIORITY_FREIGHT' => 'FEDEX - International Priority Freight',
        'PRIORITY_OVERNIGHT' => 'FEDEX - Priority Overnight',
        'SMART_POST' => 'FEDEX - Smart Post',
        'STANDARD_OVERNIGHT' => 'FEDEX - Standard Overnight',
        'FEDEX_FREIGHT' => 'FEDEX - Freight',
        'FEDEX_NATIONAL_FREIGHT' => 'FEDEX - National Freight'
    ];

    /**
     * Returns rate for flatrate
     *
     * @param array $items
     * @return array|false
     */
    public function calculate($items = [])
    {
        if (!$this->isAvailable()) return false;

        $cart = app(\Webkul\Checkout\Cart::class)->getCart();
        if (empty($items)) $items = $cart->items;

        // Get address
        $shipToAddress = $cart->shipping_address;
        if (strtolower($items[0]->product->attribute_family->code) == config('app.firearmFamilyCode')) {
            $shipToAddress = $cart->ffl_address;
        }

        // Collect items totals
        $totals = $this->calculateTotals($items);

        // Collect options for api request
        $options = $this->getOptions($totals, $shipToAddress);

        // Make request
        $result = (new FedexApi($options))->execute();
        if (!$result['status']) return [];

        // Create shipping rates
        $rates = [];
        foreach ($result['data'] as $code => $service) {

            // Increase all rates with 3.2%
            if ($sa = core()->getConfigData('marketplace.settings.general.shipment_processing')) {
                $service['rate'] = $service['rate'] + ($service['rate'] / 100 * $sa);
            }

            $object = new CartShippingRate;
            $object->carrier = 'mpfedex';
            $object->carrier_title = $this->getConfigData('title');
            $object->method = 'mpfedex_'.''.$code;
            $object->method_title = $service['title'];
            $object->method_description = $this->getConfigData('title') .' - ' .$service['title'];
            $object->price = core()->convertPrice($service['rate']);
            $object->base_price = $service['rate'];
            $object->cart_address_id = $shipToAddress->id;
            $object->save();
            $rates[] = $object;
        }
        return $rates;
    }

    /**
     * get the services
     *
     * @return array $allowed_services
     */
    public function getServices(): array
    {
        $allowed_services = [];
        $config_services = core()->getConfigData('sales.carriers.mpfedex.services');
        $services = explode(",", $config_services);

        foreach ($services as $service_code) {
            if ( isset($this->services[$service_code]) ) {
                $allowed_services[$service_code] = $this->services[$service_code];
            }
        }
        return $allowed_services;
    }

    /**
     * @param array $totals
     * @param $shipToAddress
     * @return array
     */
    protected function getOptions(array $totals, $shipToAddress): array
    {
        $cart = app(\Webkul\Checkout\Cart::class)->getCart();
        $seller = Seller::query()->find($cart->seller_id);

        $sellerCreds = false;
        if (core()->getConfigData('sales.carriers.mpfedex.allow_seller') == 1) {
            $creds = FedEx::query()->where('marketplace_seller_id', '=', $cart->seller_id)->first();
            if ($creds) {
                $sellerCreds = true;
            }
        }

        if ($sellerCreds) {
            $userCredKey = $creds->key;
            $userCredPassword = $creds->password;
            $clientDetailAccN = $creds->account_id;
            $clientDetailMeterN = $creds->meter_number;
        } else {
            $userCredKey = core()->getConfigData('sales.carriers.mpfedex.key');
            $userCredPassword = core()->getConfigData('sales.carriers.mpfedex.password');
            $clientDetailAccN = core()->getConfigData('sales.carriers.mpfedex.account_id');
            $clientDetailMeterN = core()->getConfigData('sales.carriers.mpfedex.meter_number');
        }

        $date = time();
        $day = date('l', $date);
        if ($day == 'Saturday') {
            $date += 172800;
        } elseif ($day == 'Sunday') {
            $date += 86400;
        }
        return [
            'WebAuthenticationDetail' => [
                'UserCredential' => [
                    'Key' => $userCredKey,
                    'Password' => $userCredPassword
                ]
            ],
            'ClientDetail' => [
                'AccountNumber' => $clientDetailAccN,
                'MeterNumber' => $clientDetailMeterN
            ],
            'RequestedShipment' => [
                'DropoffType' => core()->getConfigData('sales.carriers.mpfedex.dropoff_type'),
                'PackagingType' => core()->getConfigData('sales.carriers.mpfedex.packaging_type'),
                'ShipTimestamp' => date('c', $date),
                'Shipper' => [
                    'Contact' => [
                        'PersonName' => $seller->first_name.' '.$seller->last_name,
                        'CompanyName' => $seller->shop_title,
                        'PhoneNumber' => $seller->phone
                    ],
                    'Address' => [
                        'StreetLines' => [$seller->address1],
                        'City' => $seller->city,
                        'StateOrProvinceCode' => $seller->state,
                        'PostalCode' => $seller->postcode,
                        'CountryCode' => 'US'
                    ],
                ],
                'RequestedPackageLineItems' => [
                    'Weight' => [
                        'Units' => 'LB',
                        'Value' => $totals['weight']
                    ],
                    'Dimensions' => [
                        'Length' => $totals['length'],
                        'Width' => $totals['width'],
                        'Height' => $totals['height'],
                        'Units' => 'IN'
                    ],
                    'SequenceNumber' => 1,
                    'GroupPackageCount' => 1
                ],
                'TotalInsuredValue' => [
                    'Ammount' => $cart->base_sub_total,
                    'Currency' => session()->get('currency')
                ],
                'Recipient' => [
                    'Contact' => [
                        'PersonName' => $shipToAddress->first_name . ' ' . $shipToAddress->last_name,
                        'CompanyName' => $shipToAddress->email,
                        'PhoneNumber' => $shipToAddress->phone
                    ],
                    'Address' => [
                        'StreetLines' => [$shipToAddress->address1],
                        'StateOrProvinceCode' => $shipToAddress->state,
                        'City' => $shipToAddress->city,
                        'PostalCode' => $shipToAddress->postcode,
                        'CountryCode' => $shipToAddress->country,
                        'Residential' => $shipToAddress->company ? 'true' : 'false',
                    ]
                ],
                'ShippingChargesPayment' => [
                    'PaymentType' => 'SENDER',
                    'Payor' => [
                        'ResponsibleParty' => [
                            'AccountNumber' => core()->getConfigData('sales.carriers.mpfedex.account_id'),
                            'CountryCode' => 'US'
                        ]
                    ]
                ],
                'PackageCount' => '1',
            ],
            'TransactionDetail' => [
                'CustomerTransactionId' => ' *** Rate Request using PHP ***'
            ],
            'Version' => [
                'ServiceId' => 'crs',
                'Major' => '26',
                'Intermediate' => '0',
                'Minor' => '0'
            ],
            'ReturnTransitAndCommit' => true,
            'CustomsClearanceDetail' => [
                'CustomsValue' => [
                    'Amount' => $cart->base_sub_total,
                    'Currency' => session()->get('currency')
                ],
                'CommercialInvoice' => [
                    'Purpose' => 'SOLD',
                ]
            ]
        ];
    }
}