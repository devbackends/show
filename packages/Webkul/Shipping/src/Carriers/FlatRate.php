<?php

namespace Webkul\Shipping\Carriers;

use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Marketplace\Models\FlatRateInfo;
use Webkul\Marketplace\Repositories\SellerRepository;

/**
 * Class Rate.
 *
 */
class FlatRate extends AbstractShipping
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'flatrate';

    /**
     * @param array $items
     * @return false|CartShippingRate
     */
    public function calculate($items = [])
    {
        if (!$this->isAvailable()) {
            return false;
        }

        $cart = app(\Webkul\Checkout\Cart::class)->getCart();

        $object = new CartShippingRate;

        $object->carrier = 'flatrate';
        $object->carrier_title = $this->getConfigData('title');
        $object->method = 'flatrate_flatrate';
        $object->method_title = $this->getConfigData('title');
        $object->method_description = $this->getConfigData('description');
        $object->price = 0;
        $object->base_price = 0;

        $cartItems = app(\Webkul\Marketplace\Helpers\Cart::class)->getAllCartsItems($cart);
        $rates = [];

        foreach ($cartItems as $seller => $data) {
            if (strpos($seller, 'seller') !== false) {
                $sellerId = explode('_', $seller)[1];
                $seller = app(SellerRepository::class)->find($sellerId);
                if (in_array('flatrate', explode(',', $seller->shipping_methods))) {
                    $flatRateInfo = FlatRateInfo::where(['seller_id' => $seller->id])->first();
                    if ($flatRateInfo) {
                        $rates[$sellerId] = [
                            'method' => 'Flat Rate',
                            'amount' => 0,
                            'base_amount' => 0,
                        ];
                        if ($flatRateInfo->type == 'per_unit') {
                            foreach ($data['items'] as $item) {
                                $rates[$sellerId]['amount'] += core()->convertPrice($flatRateInfo->rate) * $item['quantity'];
                                $rates[$sellerId]['base_amount'] += $flatRateInfo->rate * $item['quantity'];
                            }
                        } else {
                            $rates[$sellerId]['amount'] = core()->convertPrice($flatRateInfo->rate);
                            $rates[$sellerId]['base_amount'] = $flatRateInfo->rate;
                        }

                        // Increase all rates with 3.2%
                        if ($sa = core()->getConfigData('marketplace.settings.general.shipment_processing')) {
                            $rates[$sellerId]['amount'] = $rates[$sellerId]['amount'] + ($rates[$sellerId]['amount'] / 100 * $sa);
                            $rates[$sellerId]['base_amount'] = $rates[$sellerId]['base_amount'] + ($rates[$sellerId]['base_amount'] / 100 * $sa);
                        }
                    }
                }
            }
        }

        if (!empty($rates)) {
            session()->put('flat_rate_shipping_rates', $rates);
        }

        if ($this->getConfigData('type') == 'per_unit') {
            foreach ($cart->items as $item) {
                if ($item->product->getTypeInstance()->isStockable()) {
                    $object->price += core()->convertPrice($this->getConfigData('default_rate')) * $item->quantity;
                    $object->base_price += $this->getConfigData('default_rate') * $item->quantity;
                }
            }
        } else {
            $object->price = core()->convertPrice($this->getConfigData('default_rate'));
            $object->base_price = $this->getConfigData('default_rate');
        }

        // Increase all rates with 3.2%
        if ($sa = core()->getConfigData('marketplace.settings.general.shipment_processing')) {
            $object->price = $object->price + ($object->price / 100 * $sa);
            $object->base_price = $object->base_price + ($object->base_price / 100 * $sa);
        }

        return $object;
    }

}