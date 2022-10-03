<?php

namespace Webkul\MarketplaceFedExShipping\Helpers;

use SoapFault;
use Webkul\Checkout\Cart as CartService;
use Webkul\Marketplace\Repositories\SellerRepository as SellerRepository;
use Webkul\MarketplaceFedExShipping\Repositories\FedExRepository as FedExRepository;


class ShippingMethodHelper
{

    const ONLY_SELLER = true;

    /**
     * SellerRepository object
     *
     * @var object
    */
    protected $sellerRepository;

    /**
     * Fedex Repository Object
     *
     * @var object
     */
    protected $fedexRepository;

    /**
     * RateServiceWsdl
     *
     * @var string
     */
    protected $rateServiceWsdl;

    /**
     * ShipServiceWsdl
     *
     * @var string
     */
    protected $shipServiceWsdl;

    /**
     * Create a new controller instance.
     *
     * @param SellerRepository $sellerRepository
     * @param FedExRepository $fedexRepository
     */
    public function __construct(
        SellerRepository $sellerRepository,
        FedexRepository $fedexRepository
    )
    {
        $this->sellerRepository = $sellerRepository;
        $this->fedexRepository = $fedexRepository;
        $this->rateServiceWsdl = __DIR__ .'/Wsdl/' . 'RateService_v26.wsdl';
        $this->shipServiceWsdl = __DIR__ .'/Wsdl/' . 'ShipService_v19.wsdl';
    }

    /**
     * display methods
     *
     * @return array|false
     * @throws SoapFault
     */
    public function getAllCartProducts()
    {
        if (!core()->getConfigData('sales.carriers.mpfedex.active')) return false;

        return $this->_createSoapClient();
    }

    /**
     * Soap client for wsdl
     *
     * @return array[]|false
     * @throws SoapFault
     */
    protected function _createSoapClient()
    {
        $cart = app(CartService::class)->getCart();

        $errorResponse = $response = [];

        $cartItems = $this->fedexRepository->getSellerAdminData($cart->items()->get(), 'postcode');

        if (!core()->getConfigData('sales.carriers.mpfedex.sandbox_mode')) {
            $url = 'https://gateway.fedex.com/web-services/rate';
        } else {
            $url = 'https://wsbeta.fedex.com:443/web-services';
        }

        $sellerAdminServices = $allServices = [];
        foreach ($cartItems as $item) {

            if ($item->type === 'virtual' || $item->type === 'booking') continue;

            if ( isset($item->marketplace_seller_id) ) {
                $seller = $this->sellerRepository->find($item->marketplace_seller_id);

                if ( $seller && isset($seller->seller_carriers) ) {
                    $seller_carriers = json_decode($seller->seller_carriers, true);

                    if ( isset($seller_carriers['methods']['mpfedex']) && $seller_carriers['methods']['mpfedex'] ) {

                        $valid_vendor_services = [];
                        $vendor_services = $seller_carriers['methods']['mpfedex'];

                        foreach($vendor_services as $vendor_service) {
                            if ( in_array($vendor_service, explode(",", core()->getConfigData('sales.carriers.mpfedex.services')))) {
                                $valid_vendor_services[] = $vendor_service;
                            }
                        }
                        $sellerAdminServices[$seller->id] = $valid_vendor_services;
                    }
                }
            } else {
                $sellerAdminServices[0] = explode(",", core()->getConfigData('sales.carriers.mpfedex.services'));
            }
        }

        foreach ($cartItems as $item) {

            if ($item->type === 'virtual') continue;

            if (self::ONLY_SELLER && $item->marketplace_seller_id === 0) continue;

            // Get item type
            $type = $this->getItemType($item);

            // Set shipto address
            $shipToAddress = ($type === 'firearm') ? $cart->ffl_address : $cart->shipping_address;

            // Get options for request
            $request = $this->getRequestOptions($type, $item, $shipToAddress);

            $client = new \SoapClient($this->rateServiceWsdl, [
                'trace' => true,
                'stream_context' => stream_context_create([
                    'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
                ])
            ]);
            $client->__setLocation($url);

            try {
                $sellerId = 0;
                if ($type === 'firearm') {
                    $sellerId = config('app.firearmFamilyCode');
                } elseif ($type === 'seller') {
                    $sellerId = $item->marketplace_seller_id;
                }

                $rateReply = '';

                $response = $client->getRates($request);

                if ($response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR') {

                    if ($response->HighestSeverity == 'WARNING' && !isset($response->RateReplyDetails)) {
                        array_push($errorResponse , $response);
                    } elseif ($response->HighestSeverity == 'WARNING' && isset($response->RateReplyDetails)) {
                        $rateReply = $response->RateReplyDetails;
                    } else {
                        $rateReply = $response->RateReplyDetails;
                    }

                    if (isset($rateReply) && $rateReply != null) {

                        $cartProductServices = [];
                        if (gettype($rateReply) == 'array') {

                            foreach ($rateReply as $reply) {
                                if(isset($reply->DeliveryStation)) {
                                    $station = $reply->DeliveryStation;
                                } else {
                                    $station = '';
                                }

                                $matchResult = $this->fedexRepository->validateAllowedMethods($reply->ServiceType, $sellerAdminServices);

                                if ($matchResult) {
                                    $cartProductServices[$reply->ServiceType] = [
                                        'packagingType' => $reply->PackagingType,
                                        'deliveryStation' => $station,
                                        'originDetails' =>[
                                            'CountryCode' => $reply->CommitDetails->DerivedOriginDetail->CountryCode,
                                            'StateOrProvinceCode' => $reply->CommitDetails->DerivedOriginDetail->StateOrProvinceCode,
                                            'PostalCode' => $reply->CommitDetails->DerivedOriginDetail->PostalCode,
                                        ],
                                        'destinationDetails' => [
                                            'CountryCode' => $reply->CommitDetails->DerivedDestinationDetail->CountryCode,
                                            'PostalCode' => $reply->CommitDetails->DerivedDestinationDetail->PostalCode,
                                        ],
                                        'airportId' => $reply->DestinationAirportId,
                                        'billingWeight' => [
                                            'weightMethod' => $reply->RatedShipmentDetails->ShipmentRateDetail->RatedWeightMethod,
                                            'unit' => $reply->RatedShipmentDetails->ShipmentRateDetail->TotalBillingWeight->Units,
                                            'value' => $reply->RatedShipmentDetails->ShipmentRateDetail->TotalBillingWeight->Value,
                                        ],
                                        'totalBaseCharges' => [
                                            'amount' => $reply->RatedShipmentDetails->ShipmentRateDetail->TotalBaseCharge->Amount,
                                            'currency' => $reply->RatedShipmentDetails->ShipmentRateDetail->TotalBaseCharge->Currency
                                        ],
                                        'totalNetCharges' => [
                                            'amount' => $reply->RatedShipmentDetails->ShipmentRateDetail->TotalNetCharge->Amount,
                                            'currency' => $reply->RatedShipmentDetails->ShipmentRateDetail->TotalNetCharge->Currency
                                        ],
                                        'seller' =>[
                                            'marketplace_seller_id' => $sellerId,
                                            'itemQuantity' => $item->quantity
                                        ]
                                    ];
                                }
                            }
                        } else {
                            if (isset($rateReply->DeliveryStation)) {
                                $station = $rateReply->DeliveryStation;
                            } else {
                                $station = '';
                            }

                            $matchResult = $this->fedexRepository->validateAllowedMethods($rateReply->ServiceType, $sellerAdminServices);

                            if ($matchResult) {
                                $cartProductServices[$rateReply->ServiceType] = [
                                    'packagingType' => $rateReply->PackagingType,
                                    'deliveryStation' => $station,

                                    'originDetails' => [
                                        'CountryCode' => $rateReply->CommitDetails->DerivedOriginDetail->CountryCode,
                                        'PostalCode' => $rateReply->CommitDetails->DerivedOriginDetail->PostalCode,
                                    ],
                                    'destinationDetails' => [
                                        'CountryCode' => $rateReply->CommitDetails->DerivedDestinationDetail->CountryCode,
                                        'PostalCode' => $rateReply->CommitDetails->DerivedDestinationDetail->PostalCode,
                                    ],

                                    'airportId' => $rateReply->DestinationAirportId,
                                    'billingWeight' => [
                                        'weightMethod' => $rateReply->RatedShipmentDetails->ShipmentRateDetail->RatedWeightMethod,
                                        'unit' => $rateReply->RatedShipmentDetails->ShipmentRateDetail->TotalBillingWeight->Units,
                                        'value' => $rateReply->RatedShipmentDetails->ShipmentRateDetail->TotalBillingWeight->Value,
                                    ],
                                    'totalBaseCharges' => [
                                        'amount' => $rateReply->RatedShipmentDetails->ShipmentRateDetail->TotalBaseCharge->Amount,
                                        'currency' => $rateReply->RatedShipmentDetails->ShipmentRateDetail->TotalBaseCharge->Currency
                                    ],
                                    'totalNetCharges' => [
                                        'amount' => $rateReply->RatedShipmentDetails->ShipmentRateDetail->TotalNetCharge->Amount,
                                        'currency' => $rateReply->RatedShipmentDetails->ShipmentRateDetail->TotalNetCharge->Currency
                                    ],
                                    'seller' =>[
                                        'marketplace_seller_id' => $sellerId,
                                        'itemQuantity' => $item->quantity
                                    ]
                                ];
                            }
                        }

                        if ( !empty($cartProductServices)) {
                            $allServices[] = $cartProductServices;
                        }
                    }

                } else {
                    $errorResponse[] = $response;
                }
            } catch (SoapFault $exception) {
                $errorResponse[] = $exception->getMessage();
            }
        }

        if (empty($allServices)) {
            $errorResponse[] = 'Empty';
        }

        return [
            'response' => $allServices,
            'errorResponse' => $errorResponse
        ];
    }

    /**
     * Get The Weight
     *
     * @param $weight
     * @param $stored_in
     *
     * @return float|string
     */
    public function convertWeight($weight, $stored_in = 'LB') {
        $coreWeightUnit = strtoupper(core()->getConfigData('general.general.locale_options.weight_unit'));
        $fedexWeightUnit = strtoupper(core()->getConfigData('sales.carriers.mpfedex.weight_unit'));
        $coreWeightUnit = $coreWeightUnit === 'KGS'? 'KG': 'LB';
        if ($stored_in === 'OZ') {
            // since it's stored in ounces, convert ounces to pounds:
            $weight = $weight/16;
            // then convert pounds to the target unit:
            $convertedWeight = $this->fromUnitToUnit('LB', $fedexWeightUnit, $weight);
        }
        else {
            $convertedWeight = $this->fromUnitToUnit($coreWeightUnit, $fedexWeightUnit, $weight);
        }
        return $convertedWeight;
    }

    /**
     * Converts a value from unit to another.
     *
     * @param $from
     * @param $to
     * @param $weight
     *
     * @return float
     */
    public function fromUnitToUnit($from, $to, $weight){
        $value = null;
        switch ($from) {
            case "LB":
                if ($to === "LB") {
                    $value = $weight;
                }
                if ($to === "KG") {
                    $value = $weight/2.2046;
                }
                break;
            case "KG":
                if ($to === "KG") {
                    $value = $weight;
                }
                if ($to === "LB") {
                    $value = $weight/0.45359237;
                }
                break;

        }
        return $value;
    }

    protected function getItemType($item)
    {
        if (strtolower($item->product->attribute_family->code) == config('app.firearmFamilyCode')) {
            return config('app.firearmFamilyCode'); // Firearm
        } elseif (isset($item->marketplace_seller_id) && $item->marketplace_seller_id) {
            return 'seller';
        } else {
            return 'default';
        }
    }

    protected function getRequestOptions($type, $item, $shipToAddress)
    {
        if ($type === 'seller') {
            $request = $this->getSellerOptions($item);
        } else {
            $request = $this->getDefaultOptions($item);
        }

        $date = time();
        $day = date('l', $date);
        if ($day == 'Saturday') {
            $date += 172800;
        } elseif ($day == 'Sunday') {
            $date += 86400;
        }

        $request['TransactionDetail'] = ['CustomerTransactionId' => ' *** Rate Request using PHP ***'];
        $request['Version'] = [
            'ServiceId' => 'crs',
            'Major' => '26',
            'Intermediate' => '0',
            'Minor' => '0'
        ];
        $request['ReturnTransitAndCommit'] = true;
        $request['RequestedShipment']['ShipTimestamp'] = date('c', $date);
        $request['RequestedShipment']['TotalInsuredValue'] = [
            'Ammount' => $item->price,
            'Currency' => session()->get('currency')
        ];
        $request['RequestedShipment']['Recipient'] = [
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
                'Residential' => $shipToAddress->company ? 'true' : 'false'
            ]
        ];
        if (in_array($shipToAddress->state, ['US', 'CA'])) {
            $request['RequestedShipment']['Recipient']['Address']['StateOrProvinceCode'] = $shipToAddress->state;
        }
        $request['RequestedShipment']['ShippingChargesPayment'] = [
            'PaymentType' => 'SENDER',
            'Payor' => [
                'ResponsibleParty' => [
                    'AccountNumber' => core()->getConfigData('sales.carriers.mpfedex.account_id'),
                    'CountryCode' => 'US'
                ]
            ]
        ];
        $request['CustomsClearanceDetail'] = [
            'CustomsValue' => [
                'Amount' => $item->price * $item->quantity,
                'Currency' => session()->get('currency')
            ],
            'CommercialInvoice' => [
                'Purpose' => 'SOLD',
            ]
        ];
        $request['RequestedShipment']['PackageCount'] = '1';
        $request['RequestedShipment']['RequestedPackageLineItems']['SequenceNumber'] = 1;
        $request['RequestedShipment']['RequestedPackageLineItems']['GroupPackageCount'] = 1;
        $weight = $item->weight;
        $stored_in = "OZ";
        if(!$weight || empty($weight) || (int)$weight === 0){
            // get the weight value from the LBS field
            $weight = $item->product->weight_lbs;
            $stored_in = "LB";
        }
        $request['RequestedShipment']['RequestedPackageLineItems']['Weight']['Value'] = $this->convertWeight($weight, $stored_in);
        return $request;
    }

    protected function getDefaultOptions($item)
    {
        return [
            'WebAuthenticationDetail' => [
                'UserCredential' => [
                    'Key' => core()->getConfigData('sales.carriers.mpfedex.key'),
                    'Password' => core()->getConfigData('sales.carriers.mpfedex.password')
                ]
            ],
            'ClientDetail' => [
                'AccountNumber' => core()->getConfigData('sales.carriers.mpfedex.account_id'),
                'MeterNumber' => core()->getConfigData('sales.carriers.mpfedex.meter_number')
            ],
            'RequestedShipment' => [
                'DropoffType' => core()->getConfigData('sales.carriers.mpfedex.dropoff_type'),
                'PackagingType' => core()->getConfigData('sales.carriers.mpfedex.packaging_type'),
                'Shipper' => [
                    'Contact' => [
                        'PersonName' => core()->getCurrentChannel()->name,
                        'CompanyName' => core()->getCurrentChannel()->hostname,
                    ],
                    'Address' => [
                        'StreetLines' => [core()->getConfigData('sales.shipping.origin.address1')],
                        'City' => core()->getConfigData('sales.shipping.origin.city'),
                        'StateOrProvinceCode' => core()->getConfigData('sales.shipping.origin.state'),
                        'PostalCode' => core()->getConfigData('sales.carriers.mpfedex.postcode'),
                        'CountryCode' => core()->getConfigData('sales.shipping.origin.country')
                    ],
                ],
                'RequestedPackageLineItems' => [
                    'Weight' => [
                        'Units' => core()->getConfigData('sales.carriers.mpfedex.weight_unit')
                    ],
                    'Dimensions' => [
                        'Length' => $item->product->depth,
                        'Width' => $item->product->width,
                        'Height' => $item->product->height,
                        'Units' => 'IN',
                    ]
                ],
            ],
        ];
    }

    protected function getSellerOptions($item)
    {
        $seller = $this->sellerRepository->find($item->marketplace_seller_id);
        $sellerCredential = $this->fedexRepository->findOneWhere(['marketplace_seller_id' => $item->marketplace_seller_id]);
        if ($sellerCredential && core()->getConfigData('sales.carriers.mpfedex.allow_seller') == 1) {
            $userCredKey = $sellerCredential->key;
            $userCredPassword = $sellerCredential->password;
            $clientDetailAccN = $sellerCredential->account_id;
            $clientDetailMeterN = $sellerCredential->meter_number;
        } else {
            $userCredKey = core()->getConfigData('sales.carriers.mpfedex.key');
            $userCredPassword = core()->getConfigData('sales.carriers.mpfedex.password');
            $clientDetailAccN = core()->getConfigData('sales.carriers.mpfedex.account_id');
            $clientDetailMeterN = core()->getConfigData('sales.carriers.mpfedex.meter_number');
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
                        'CountryCode' => $seller->country
                    ],
                ],
                'RequestedPackageLineItems' => [
                    'Weight' => [
                        'Units' => core()->getConfigData('sales.carriers.mpfedex.weight_unit')
                    ],
                    'Dimensions' => [
                        'Length' => $item->depth,
                        'Width' => $item->width,
                        'Height' => $item->height,
                        'Units' => 'IN'
                    ]
                ],
            ],
        ];
    }
}
