<?php

namespace Webkul\MarketplaceUpsShipping\Carriers;

use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\MarketplaceUpsShipping\Models\Ups;
use Webkul\MarketplaceUpsShipping\Services\UpsShippingApi;
use Webkul\MarketplaceUpsShipping\Services\Xml;
use Webkul\Shipping\Carriers\AbstractShipping;

class MarketplaceUps extends AbstractShipping
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'mpups';

    /**
     * Return Services for Ups Shipping
     *
     * @return array
     */
    protected $services  = [
        '01' => 'UPS - Next Day Air',
        '02' => 'UPS - 2nd Day Air',
        '03' => 'UPS - Ground',
        '07' => 'UPS - Worldwide Express',
        '08' => 'UPS - Worldwide Expedited',
        '12' => 'UPS - 3 Day Select',
        '13' => 'UPS - Next Day Air Saver',
        '14' => 'UPS - Next Day Air Early AM',
        '54' => 'UPS - UPS Worldwide Express Plus',
        '59' => 'UPS - 2nd Day Air AM',
        '65' => 'UPS - World Wide Saver',
    ];

    /**
     * Returns rate for Ups Shipping
     *
     * @param array $items
     * @return array|false
     */
    public function calculate($items = [])
    {
        if (! $this->isAvailable()) return false;

        $cart = app(\Webkul\Checkout\Cart::class)->getCart();
        if (empty($items)) $items = $cart->items;

        // Collect items totals
        $totals = $this->calculateTotals($items);

        // Set address
        $shipToAddress = $cart->shipping_address;
        if (strtolower($items[0]->product->attribute_family->code) == config('app.firearmFamilyCode')) {
            $shipToAddress = $cart->ffl_address;
        }

        // Collect options for api request
        $options = $this->getOptions($totals, $shipToAddress);

        // Convert options to xml
        $xml = (new Xml($options))->execute();

        // Make request
        $result = (new UpsShippingApi($xml))->execute();
        if (!$result['status']) return [];

        // Create shipping rates
        $rates = [];
        foreach ($result['data'] as $code => $service) {

            // Increase all rates with 3.2%
            if ($sa = core()->getConfigData('marketplace.settings.general.shipment_processing')) {
                $service['rate'] = $service['rate'] + ($service['rate'] / 100 * $sa);
            }

            $object = new CartShippingRate;
            $object->carrier = 'mpups';
            $object->carrier_title = $this->getConfigData('title');
            $object->method = 'mpups_'.''.$code;
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
     * Return Services for Ups Shipping
     *
     * @return array
     */
    public function getServices(): array
    {
        $allowed_services = [];
        $config_services = core()->getConfigData('sales.carriers.mpups.services');
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
    public function getOptions(array $totals, $shipToAddress): array
    {
        $cart = app(\Webkul\Checkout\Cart::class)->getCart();
        $seller = app(SellerRepository::class)->find($cart->seller_id);

        $sellerUpsCreds = false;
        if (core()->getConfigData('sales.carriers.mpups.active') && core()->getConfigData('sales.carriers.mpups.allow_seller')) {
            $sellerUpsCreds = Ups::query()->where(['ups_seller_id' => $cart->seller_id])->first();
        }

        return [
            'access_key' => core()->getConfigData('sales.carriers.mpups.access_license_key'),
            'user_id' => ($sellerUpsCreds) ? $sellerUpsCreds->account_id : core()->getConfigData('sales.carriers.mpups.user_id'),
            'password' => ($sellerUpsCreds) ? $sellerUpsCreds->password : core()->getConfigData('sales.carriers.mpups.password'),
            'package_code' => core()->getConfigData('sales.carriers.mpups.container'),
            'weight' => $totals['weight'],
            'shipper' => [
                'name' => $seller->shop_title,
                'number' => (core()->getConfigData('sales.carriers.mpups.shipper_number'))
                    ? core()->getConfigData('sales.carriers.mpups.shipper_number') : '',
                'address' => $seller->address1,
                'city' => $seller->city,
                'zipcode' => $seller->postcode,
                'country_id' => $seller->country,
            ],
            'ship_from' => [
                'company_name' => $seller->shop_title,
                'zone' => $seller->state,
                'address' => $seller->address1,
                'city' => $seller->city,
                'zipcode' => $seller->postcode,
                'country_id' => $seller->country,
            ],
            'ship_to' => [
                'company_name' => $shipToAddress->first_name . ' ' . $shipToAddress->last_name,
                'address' => $shipToAddress->address1,
                'city' => $shipToAddress->city,
                'zipcode' => $shipToAddress->postcode,
                'country_id' => $shipToAddress->country,
            ],
        ];
    }
}
