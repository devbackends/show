<?php

namespace Webkul\MarketplaceUspsShipping\Helpers;

use Exception;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Webkul\Attribute\Models\AttributeFamily;
use Webkul\Checkout\Facades\Cart;
use Webkul\Core\Models\CoreConfig;
use Webkul\Core\Repositories\CountryRepository;
use Webkul\Checkout\Repositories\CartAddressRepository as CartAddress;
use Webkul\Marketplace\Repositories\SellerRepository as SellerRepository;
use Webkul\MarketplaceUspsShipping\Repositories\UspsRepository as UspsRepository;
use Illuminate\Support\Str;

class ShippingMethodHelper
{

    const ONLY_SELLER = true;

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * SellerRepository object
     *
     * @var object
    */
    protected $sellerRepository;

    /**
     * Cart Address Object
     *
     * @var object
     */
    protected $cartAddress;

    /**
     * Usps Repository Object
     *
     * @var object
     */
    protected $uspsRepository;

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
     * @param CartAddress $cartAddress
     * @param UspsRepository $uspsRepository
     */
    public function __construct(
        SellerRepository $sellerRepository,
        CartAddress $cartAddress,
        UspsRepository $uspsRepository
    )
    {
        $this->_config = request('_config');

        $this->sellerRepository = $sellerRepository;

        $this->cartAddress = $cartAddress;

        $this->uspsRepository = $uspsRepository;
    }

    /**
     * display methods
     *
     * @return array|false
     * @throws Exception
     */
    public function getAllCartProducts()
    {
        if (! core()->getConfigData('sales.carriers.mpusps.active')) return false;

        return $this->_createSoapClient();
    }

    /**
     * Soap client for wsdl
     *
     * @return array[]|false
     * @throws Exception
     */
    protected function _createSoapClient()
    {
        $cart = app(\Webkul\Checkout\Cart::class)->getCart();

        $errorResponse = [];
        $allServices = [];

        $cartItems = $this->uspsRepository->getSellerAdminData($cart->items()->get(), 'postcode');

        $sellerAdminServices = [];
        foreach ($cartItems as $item) {

            if ($item->type === 'virtual' || $item->type === 'booking') continue;

            if ( isset($item->marketplace_seller_id) ) {
                $seller = $this->sellerRepository->find($item->marketplace_seller_id);

                if ($seller && isset($seller->seller_carriers) ) {
                    $seller_carriers = json_decode($seller->seller_carriers, true);
                    if ( isset($seller_carriers['methods']['mpusps']) && $seller_carriers['methods']['mpusps'] ) {
                        $sellerAdminServices[$seller->id] = $seller_carriers['methods']['mpusps'];
                    }
                }
            } else {
                $sellerAdminServices[0] = explode(",", core()->getConfigData('sales.carriers.mpusps.services'));
            }
        }

        foreach ($cartItems as $item) {

            if ($item->type === 'virtual' || $item->type === 'booking') continue;

            if (self::ONLY_SELLER && $item->marketplace_seller_id === 0) continue;

            $type = $this->getItemType($item);

            $address = ($type === 'firearm') ? $cart->ffl_address : $cart->shipping_address;

            $options = $this->getOptionsForXml($item, $type, $address);

            $xml = (new XmlHelper($options))->getXml();
            $api = ($options['isUs']) ? 'RateV4' : 'IntlRateV2';

            try {
                $response = $this->curlRequest($xml, $api);

                $uspsServiceArray = simplexml_load_string($response);

                $uspsServices = json_decode(json_encode($uspsServiceArray));

                $sellerId = 0;
                if ($type === 'firearm') {
                    $sellerId = config('app.firearmFamilyCode');
                } elseif ($type === 'seller') {
                    $sellerId = $item->marketplace_seller_id;
                }

                if (isset($uspsServices->Package->Error) && $response) {
                    array_push($errorResponse, $uspsServices->Package->Error);
                }

                if ($response) {
                    foreach ($uspsServices as $services) {
                        if (!isset($uspsServices->Package->Error)) {
                            if (isset($services->Prohibitions)) {
                                //INTL Rate Request
                                foreach ($services->Service as $key => $reply) {
                                    foreach ($reply as $key => $id) {
                                        if ($key == '@attributes')
                                            $classId = 'INT_' . $id->ID;
                                    }

                                    $manipulateafter = Str::before($reply->SvcDescription , '&lt');
                                    $manipulatebefore =  Str::after($reply->SvcDescription , '/sup&gt;');

                                    $serviceType = $manipulateafter . "" . $manipulatebefore;

                                    $matchResult = $this->uspsRepository->validateAllowedMethods($classId, $sellerAdminServices);

                                    if ($matchResult) {
                                        $cartProductServices[$serviceType] = [
                                            'classId' => $classId,
                                            'rate' => $reply->Postage,
                                            'pounds' => $reply->Pounds,
                                            'ounces' => $reply->Ounces,
                                            'country' => $reply->Country,
                                            'marketplace_seller_id' => $sellerId,
                                            'itemQuantity' => $item->quantity
                                        ];
                                    }
                                }
                            } else {
                                $originZip = $services->ZipOrigination;
                                $destinationZip = $services->ZipDestination;
                                $pounds = $services->Pounds;
                                $ounces = $services->Ounces;
                                $machnable = $services->Machinable;
                                $zone = $services->Zone;

                                foreach ($services->Postage as $reply) {
                                    $classId = 0;
                                    foreach ($reply as $key => $id) {

                                        if ($key == '@attributes')
                                            $classId = $id->CLASSID;
                                    }

                                    $manipulateafter = Str::before($reply->MailService , '&lt');
                                    $manipulatebefore =  Str::after($reply->MailService , '/sup&gt;');

                                    $serviceType = $manipulateafter . "" . $manipulatebefore;

                                    $matchResult = $this->uspsRepository->validateAllowedMethods($classId, $sellerAdminServices);

                                    if ($matchResult) {
                                        $cartProductServices[$serviceType] = [
                                            'classId' => $classId,
                                            'rate' => $reply->Rate,
                                            'originationZip' => $originZip,
                                            'destinationZip' => $destinationZip,
                                            'originZip' => $originZip,
                                            'pounds' => $pounds,
                                            'ounces' => $ounces,
                                            'machnable' => $machnable,
                                            'zone' => $zone,
                                            'marketplace_seller_id' => $sellerId,
                                            'itemQuantity' => $item->quantity
                                        ];
                                    }
                                }
                            }
                        } else {
                            $errorResponse[] = $response;
                        }
                    }

                    if (!empty($cartProductServices)) {
                        $allServices[] = $cartProductServices;
                    }
                } else {
                    $errorResponse[] = $uspsServices->Package->Error;
                }
            } catch (Exception $e) {
                $errorResponse[] = $e->getMessage();
            }
        }

        if (empty($allServices) && isset($uspsServices->Package->Error)) {
            $errorResponse[] = $uspsServices->Package->Error;
        }

        return [
            'response' => $allServices,
            'errorResponse' => $errorResponse
        ];
    }

    protected function curlRequest($xml, $api)
    {
        $url = ('DEVELOPMENT' ==  core()->getConfigData('sales.carriers.mpusps.mode'))
            ? 'http://production.shippingapis.com/ShippingAPI.dll'
            : 'https://secure.shippingapis.com/ShippingAPI.dll';

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-type: text/xml",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "API=" . $api . "&XML=" . $xml);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
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

    protected function getOptionsForXml($item, $type, $shipToAddress) {
        $options = $this->getDefaultAndSellerOptions($type, $item);

        $options['isUs'] = $this->_isUSCountry($shipToAddress->country);
        $options['id'] = ($options['isUs']) ? 0 : '1ST';
        $current_unit = "OZ";
        $weight = $item->weight;
        if (!$weight || empty($weight) || (int)$weight === 0) {
            $current_unit = "LBS";
            $weight = $item->product->weight_lbs;
        }
        $options['weight_in_pounds'] = $this->getPoundWeight($weight, $current_unit);
        $options['weight_in_ounces'] = $this->getOunceWeight($weight, $current_unit);
        $options['price'] = $item->price;
        if (!$options['isUs']) {
            $country = app(CountryRepository::class)->findOneWhere(['code' => $shipToAddress->country]);
            $options['country_name'] = $country->name;
        }
        $options['ship_to'] = [
            'zipcode' => $shipToAddress->postcode,
        ];
        return $options;
    }

    protected function getDefaultAndSellerOptions($type, $item)
    {
        $userId = core()->getConfigData('sales.carriers.mpusps.user_id');
        if ($type === 'seller') {
            $seller = app(SellerRepository::class)->findOneWhere(['id' => $item->marketplace_seller_id]);
            $zipcode = $seller->postcode;
            if (core()->getConfigData('sales.carriers.mpusps.allow_seller') == 1) {
                $sellerCredential = $this->uspsRepository->findOneWhere(['usps_seller_id' => $item->marketplace_seller_id]);
                if ($sellerCredential) {
                    $userId = $sellerCredential->account_id;
                }
            }
        } else {
            $zipcode = core()->getConfigData('sales.shipping.origin.zipcode');
        }

        $options = [
            'userId' => $userId,
            'flat_rate_services' => (core()->getConfigData('sales.carriers.mpusps.services') == 'FLAT RATE BOX' || core()->getConfigData('sales.carriers.mpusps.services') == 'FLAT RATE ENVELOPE'),
            'first_class_services' => (core()->getConfigData('sales.carriers.mpusps.services') == 'FIRST CLASS' || core()->getConfigData('sales.carriers.mpusps.services') == 'FIRST CLASS HFP COMMERCIAL'),
            'container' => core()->getConfigData('sales.carriers.mpusps.container'),
            'machinable' => core()->getConfigData('sales.carriers.mpusps.machinable'),
            'size' => strtoupper($item->product->shipping_size),
            'ship_from' => [
                'zipcode' => $zipcode,
            ],
        ];
        if ($options['size'] === 'LARGE') {
            $options['width'] = $item->product->width;
            $options['length'] = $item->product->depth;
            $options['height'] = $item->product->height;
        }
        return $options;
    }

    /**
     * define the country code
     *
     * @param $countyId
     * @return bool
     */
    protected function _isUSCountry($countyId)
    {
        switch ($countyId) {
            case 'AS': // Samoa American
            case 'GU': // Guam
            case 'MP': // Northern Mariana Islands
            case 'PW': // Palau
            case 'PR': // Puerto Rico
            case 'VI': // Virgin Islands US
            case 'US'; // United States
            return true;
        }

        return false;
    }

    /**
     * convert currrent weight unit to
     *
     * @param $weight
     * @param $stored_in
     *
     * @return float|string
     */
    public function getPoundWeight($weight, $stored_in = 'OZ')
    {
        if ($stored_in === 'OZ') {
            return $weight/16;
        }
        return (strtoupper(core()->getConfigData('general.general.locale_options.weight_unit')) == 'LBS')
            ? $weight : $weight/0.45359237;
    }

    /**
     * convert current weight unit to ounce
     *
     * @param $weight
     * @param $stored_in
     *
     * @return float|int
     */
    public function getOunceWeight($weight, $stored_in = "OZ")
    {
        if ($stored_in === 'OZ') {
            return $weight;
        }
        return (strtoupper(core()->getConfigData('general.general.locale_options.weight_unit')) == 'LBS')
            ? $weight * 16 : $weight * 35.274;
    }

    /**
     * Get The Current Error
     *
     * @param $errors
     * @param $sellerId
     * @return bool
     */
    public function getErrorLog($errors, $sellerId)
    {
        $status = 'ERROR';
        $exception[] = $errors->Description;

        $log = ['status' => $status, 'description' => $exception , 'sellerId' => $sellerId];

        $shippingLog = new Logger('shipping');
        $shippingLog->pushHandler(new StreamHandler(storage_path('logs/usps.log')), Logger::INFO);
        $shippingLog->info('shipping', $log);

        return true;
    }

}