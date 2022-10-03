<?php

namespace Webkul\MarketplaceUspsShipping\Carriers;

use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Core\Repositories\CountryRepository;
use Webkul\Marketplace\Models\Seller;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\MarketplaceUspsShipping\Models\Usps;
use Webkul\MarketplaceUspsShipping\Services\UspsShippingApi;
use Webkul\MarketplaceUspsShipping\Services\Xml;
use Webkul\Shipping\Carriers\AbstractShipping;
use Webkul\Checkout\Facades\Cart;

/**
 * Marketplace Table Rate Shipping.
 *
 */
class MarketplaceUsps extends AbstractShipping
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'mpusps';

    /**
     * Payment method services
     *
     * @var string
     */
    protected $services  = [
        '0_FCLE' => 'USPS - First-Class Mail Large Envelope',
        '0_FCL' => 'USPS - First-Class Mail Letter',
        '0_FCP' => 'USPS - First-Class Package Service - Retail',
        '0_FCPC' => 'USPS - First-Class Mail Postcards',
        '1' => 'USPS - Priority Mail',
        '2' => 'USPS - Priority Mail Express Hold For Pickup',
        '3' => 'USPS - Priority Mail Express',
        '4' => 'USPS - Retail Ground',
        '6' => 'USPS - Media Mail',
        '7' => 'USPS - Library Mail',
        '13' => 'USPS - Priority Mail Express Flat Rate Envelope',
        '15' => 'USPS - First-Class Mail Large Postcards',
        '16' => 'USPS - Priority Mail Flat Rate Envelope',
        '17' => 'USPS - Priority Mail Medium Flat Rate Box',
        '22' => 'USPS - Priority Mail Large Flat Rate Box',
        '23' => 'USPS - Priority Mail Express Sunday/Holiday Delivery',
        '25' => 'USPS - Priority Mail Express Sunday/Holiday Delivery Flat Rate Envelope',
        '27' => 'USPS - Priority Mail Express Flat Rate Envelope Hold For Pickup',
        '28' => 'USPS - Priority Mail Small Flat Rate Box',
        '29' => 'USPS - Priority Mail Padded Flat Rate Envelope',
        '30' => 'USPS - Priority Mail Express Legal Flat Rate Envelope',
        '31' => 'USPS - Priority Mail Express Legal Flat Rate Envelope Hold For Pickup',
        '32' => 'USPS - Priority Mail Express Sunday/Holiday Delivery Legal Flat Rate Envelope',
        '33' => 'USPS - Priority Mail Hold For Pickup',
        '34' => 'USPS - Priority Mail Large Flat Rate Box Hold For Pickup',
        '35' => 'USPS - Priority Mail Medium Flat Rate Box Hold For Pickup',
        '36' => 'USPS - Priority Mail Small Flat Rate Box Hold For Pickup',
        '37' => 'USPS - Priority Mail Flat Rate Envelope Hold For Pickup',
        '38' => 'USPS - Priority Mail Gift Card Flat Rate Envelope',
        '39' => 'USPS - Priority Mail Gift Card Flat Rate Envelope Hold For Pickup',
        '40' => 'USPS - Priority Mail Window Flat Rate Envelope',
        '41' => 'USPS - Priority Mail Window Flat Rate Envelope Hold For Pickup',
        '42' => 'USPS - Priority Mail Small Flat Rate Envelope',
        '43' => 'USPS - Priority Mail Small Flat Rate Envelope Hold For Pickup',
        '44' => 'USPS - Priority Mail Legal Flat Rate Envelope',
        '45' => 'USPS - Priority Mail Legal Flat Rate Envelope Hold For Pickup',
        '46' => 'USPS - Priority Mail Padded Flat Rate Envelope Hold For Pickup',
        '47' => 'USPS - Priority Mail Regional Rate Box A',
        '48' => 'USPS - Priority Mail Regional Rate Box A Hold For Pickup',
        '49' => 'USPS - Priority Mail Regional Rate Box B',
        '50' => 'USPS - Priority Mail Regional Rate Box B Hold For Pickup',
        '53' => 'USPS - First-Class Package Service Hold For Pickup',
        '57' => 'USPS - Priority Mail Express Sunday/Holiday Delivery Flat Rate Boxes',
        '58' => 'USPS - Priority Mail Regional Rate Box C',
        '59' => 'USPS - Priority Mail Regional Rate Box C Hold For Pickup',
        '61' => 'USPS - First-Class Package Service',
        '62' => 'USPS - Priority Mail Express Padded Flat Rate Envelope',
        '63' => 'USPS - Priority Mail Express Padded Flat Rate Envelope Hold For Pickup',
        '64' => 'USPS - Priority Mail Express Sunday/Holiday Delivery Padded Flat Rate Envelope',
        'INT_1' => 'USPS - Priority Mail Express International',
        'INT_2' => 'USPS - Priority Mail International',
        'INT_4' => 'USPS - Global Express Guaranteed (GXG)',
        'INT_5' => 'USPS - Global Express Guaranteed Document',
        'INT_6' => 'USPS - Global Express Guaranteed Non-Document Rectangular',
        'INT_7' => 'USPS - Global Express Guaranteed Non-Document Non-Rectangular',
        'INT_8' => 'USPS - Priority Mail International Flat Rate Envelope',
        'INT_9' => 'USPS - Priority Mail International Medium Flat Rate Box',
        'INT_10' => 'USPS - Priority Mail Express International Flat Rate Envelope',
        'INT_11' => 'USPS - Priority Mail International Large Flat Rate Box',
        'INT_12' => 'USPS - GXG Envelopes',
        'INT_13' => 'USPS - First-Class Mail International Letter',
        'INT_14' => 'USPS - First-Class Mail International Large Envelope',
        'INT_15' => 'USPS - First-Class Package International Service',
        'INT_16' => 'USPS - Priority Mail International Small Flat Rate Box',
        'INT_17' => 'USPS - Priority Mail Express International Legal Flat Rate Envelope',
        'INT_18' => 'USPS - Priority Mail International Gift Card Flat Rate Envelope',
        'INT_19' => 'USPS - Priority Mail International Window Flat Rate Envelope',
        'INT_20' => 'USPS - Priority Mail International Small Flat Rate Envelope',
        'INT_21' => 'USPS - First-Class Mail International Postcard',
        'INT_22' => 'USPS - Priority Mail International Legal Flat Rate Envelope',
        'INT_23' => 'USPS - Priority Mail International Padded Flat Rate Envelope',
        'INT_24' => 'USPS - Priority Mail International DVD Flat Rate priced box',
        'INT_25' => 'USPS - Priority Mail International Large Video Flat Rate priced box',
        'INT_27' => 'USPS - Priority Mail Express International Padded Flat Rate Envelope'
    ];

    /**
     * Returns rate for flatrate
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
        $result = (new UspsShippingApi($xml))->execute();
        if (!$result['status']) return [];

        // Create shipping rates
        $rates = [];
        foreach ($result['data'] as $code => $service) {

            // Increase all rates with 3.2%
            if ($sa = core()->getConfigData('marketplace.settings.general.shipment_processing')) {
                $service['rate'] = $service['rate'] + ($service['rate'] / 100 * $sa);
            }

            $object = new CartShippingRate;
            $object->carrier = 'mpusps';
            $object->carrier_title = $this->getConfigData('title');
            $object->method = 'mpusps_'.''.$code;
            $object->method_title = $service['title'];
            $object->method_description = $service['title'];
            $object->price = core()->convertPrice($service['rate']);
            $object->base_price = $service['rate'];
            $object->cart_address_id = $shipToAddress->id;
            $object->save();
            $rates[] = $object;
        }

        return $rates;
    }

    /**
     * get the allowed services
     *
     * @return array
     */
    public function getServices(): array
    {
        $allowed_services = [];
        $config_services = core()->getConfigData('sales.carriers.mpusps.services');
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

        $userId = core()->getConfigData('sales.carriers.mpusps.user_id');
        if (core()->getConfigData('sales.carriers.mpusps.allow_seller') == 1) {
            $creds = Usps::query()->where('usps_seller_id', '=', $cart->seller_id)->first();
            if ($creds) {
                $userId = $creds->account_id;
            }
        }

        return [
            'userId' => $userId,
            'flat_rate_services' => (core()->getConfigData('sales.carriers.mpusps.services') == 'FLAT RATE BOX' || core()->getConfigData('sales.carriers.mpusps.services') == 'FLAT RATE ENVELOPE'),
            'first_class_services' => (core()->getConfigData('sales.carriers.mpusps.services') == 'FIRST CLASS' || core()->getConfigData('sales.carriers.mpusps.services') == 'FIRST CLASS HFP COMMERCIAL'),
            'container' => core()->getConfigData('sales.carriers.mpusps.container'),
            'machinable' => core()->getConfigData('sales.carriers.mpusps.machinable'),
            //'size' => 'LARGE',
            'width' => $totals['width'],
            'length' => $totals['length'],
            'height' => $totals['height'],
            'ship_from' => [
                'zipcode' => $seller->postcode,
            ],
            'ship_to' => [
                'zipcode' => $shipToAddress->postcode,
            ],
            'isUs' => true,
            'id' => 0,
            'weight_in_pounds' => $totals['weight'],
            'weight_in_ounces' => $totals['weight']*16,
            'price' => 0,
            'country_name' => 'United State',
        ];
    }
}