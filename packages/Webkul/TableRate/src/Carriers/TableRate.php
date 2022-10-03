<?php

namespace Webkul\TableRate\Carriers;

use Exception;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Shipping\Carriers\AbstractShipping;
use Webkul\TableRate\Helpers\ShippingHelper;

/**
 * Table Rate Shipping.
 *
 */
class TableRate extends AbstractShipping
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'tablerate';

    /**
     * Returns rate for flatrate
     *
     * @param array $items
     * @return array|false
     * @throws Exception
     */
    public function calculate($items = [])
    {
        if (!$this->isAvailable()) {
            return false;
        }

        $cart = app(\Webkul\Checkout\Cart::class)->getCart();

        $helper = new ShippingHelper($items);

        if ($this->getConfigData('type') !== 'per_unit') {
            $result = $helper->getPerUnitRates();
        } else {
            $result = $helper->getPerSectionRates();
        }

        $rates = [];
        if (!empty($result)) {
            foreach ($result as $code => $method) {
                $methodName = '';
                $internalCost = 0;
                foreach ($method as $item) {
                    $methodName = $item['superset_name'];
                    $internalCost += $item['shipping_cost'];
                }

                // Increase all rates with 3.2%
                if ($sa = core()->getConfigData('marketplace.settings.general.shipment_processing')) {
                    $internalCost = $internalCost + ($internalCost / 100 * $sa);
                }
                
                $object = new CartShippingRate;
                $object->carrier = 'tablerate';
                $object->carrier_title = $this->getConfigData('title');
                $object->method = 'tablerate_' . $code;
                $object->method_title = $methodName;
                $object->method_description = $this->getConfigData('title') . ' - '.$methodName;
                $object->price = round(core()->convertPrice($internalCost), 2);
                if(isset($cart->shipping_address->id)){
                    $object->cart_address_id = $cart->shipping_address->id;
                }
                $object->base_price = round($internalCost, 2);
                $object->save();

                $rates[] = $object;
            }
        }

        return $rates;
    }

    public function getServices()
    {
        return null;
    }

}
