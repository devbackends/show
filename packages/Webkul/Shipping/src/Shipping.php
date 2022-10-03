<?php

namespace Webkul\Shipping;

use Illuminate\Support\Facades\Config;
use Webkul\Checkout\Cart as CartService;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Marketplace\Models\Seller;
use Webkul\Product\Models\Product;

/**
 * Class Shipping.
 *
 */
class Shipping
{
    /**
     * Rates
     *
     * @var array
     */
    protected $rates = [];

    /**
     * Collects rate from available shipping methods
     *
     * @param bool $refresh
     * @return bool|array
     */
    public function collectRates($refresh = true)
    {
        if (! $cart = app(CartService::class)->getCart(request()->get('sellerId')))
            return false;

        $seller = Seller::query()->find($cart->seller_id);

        // Collect products with shipping price and other
        $items = $this->getItemsByType($cart->items);

        if ($refresh) {
            $this->removeAllShippingRates();
        } else {
            return $this->getGroupedExistingRates($cart, $items);
        }

        // Calcuate rates for per product
        foreach ($items['perProduct'] as $perProductItem) {
            if (!isset($this->rates['perProduct'])) $this->rates['perProduct'] = [];
            $product = $perProductItem['item']->product;
            $object = new CartShippingRate;
            $object->carrier = 'product_shipping_price';
            $object->carrier_title = 'Product Flat Rate';
            $object->method_title = 'Product Flat Rate';
            $object->method_description = '';
            $object->price = core()->convertPrice($perProductItem['price']);
            $object->base_price = $perProductItem['price'];

            if ($perProductItem['ffl']) {
                $object->cart_address_id = $cart->ffl_address->id;
                $object->method = 'ffl_product_shipping_price_'.$product->id;
                $object->ffl = true;
            } else {
                $object->cart_address_id = $cart->shipping_address->id;
                $object->method = 'product_shipping_price_'.$product->id;
            }

            $object->save();

            $object->product = $product;
            if ($perProductItem['ffl']) {
                $this->rates['perProductFfl'][$product->id] = $object;
            } else {
                $this->rates['perProduct'][$product->id] = $object;
            }
        }

        // Calcuate rates for left
        if (!empty($items['ffl']) || !empty($items['other'])) {

            $ammo_attribute = app('Webkul\Attribute\Repositories\AttributeFamilyRepository')->findwhere(["code" => 'Ammunition'])->first();
            foreach (Config::get('carriers') as $shippingMethod) {
                $addRate=true;
                if ($shippingMethod['code'] === 'mpmultishipping') continue;
                if ($seller->id > 0 && strpos($seller->shipping_methods, $shippingMethod['code']) === false) continue;

                if ($cart->seller_id > 0) {
                    // For sellers products we use Fedex, UPS and USPS
                    if (strpos($shippingMethod['code'], 'mp') === false) continue;
                } else {
                    // For marketplace admin products we use only Table Rate shipping
                    if ($shippingMethod['code'] !== 'tablerate') continue;
                }

                $object = app($shippingMethod['class']);
                if ($object) {

                    // Fll
                    if (!empty($items['ffl']) && $rates = $object->calculate($items['ffl'])) {
                        if (!isset($this->rates['ffl'])) $this->rates['ffl'] = [
                            'products' => implode(', ', array_map(function ($item) {
                                return $item->name;
                            }, $items['ffl'])),
                            'rates' => [],
                        ];
                        //check if cart contains ammo , if yes we need to disable usps , as usps is not allowed to ship ammo products
                        if($ammo_attribute){
                            foreach ($items['ffl'] as $item)
                            {
                                if($item->product->attribute_family_id==$ammo_attribute->id){
                                    if($shippingMethod['code']=='mpusps') $addRate=false;
                                }
                            }
                        }
                        if($addRate) {
                            $this->rates['ffl']['rates'][$shippingMethod['code']] = $rates;
                        }
                    }
                    // Other
                    if (!empty($items['other']) && $rates = $object->calculate($items['other'])) {
                        if (!isset($this->rates['other'])) $this->rates['other'] = [
                            'products' => implode(', ', array_map(function ($item) {
                                return $item->name;
                            }, $items['other'])),
                            'rates' => [],
                        ];
                        //check if cart contains ammo , if yes we need to disable usps , as usps is not allowed to ship ammo products
                        if($ammo_attribute){
                            foreach ($items['other'] as $item)
                            {
                                if($item->product->attribute_family_id==$ammo_attribute->id){
                                    if($shippingMethod['code']=='mpusps') $addRate=false;
                                }
                            }
                        }
                        if($addRate){
                            $this->rates['other']['rates'][$shippingMethod['code']] = $rates;
                        }

                    }
                }
            }
        }

        return $this->rates;
    }

    /**
     * Persist shipping rate to database
     *
     * @return void
     */
    public function removeAllShippingRates()
    {
        if (!$cart = app(CartService::class)->getCart()) {
            return;
        }

        foreach ($cart->shipping_rates()->get() as $rate) {
            $rate->delete();
        }
    }

    public function getGroupedExistingRates(Cart $cart, array $items): array
    {
        $groupedRates = [];
        foreach ($cart->shipping_rates as $rate) {
            if (in_array($rate->method, ['product_shipping_price_total', 'ffl_product_shipping_price_total'])) continue;
            if ($rate->carrier === 'product_shipping_price') {
                $parts = explode('_', $rate->method);
                $productId = array_pop($parts);
                $rate->product = Product::query()->find($productId);
                if ($rate->ffl) {
                    if (!isset($groupedRates['perProductFfl'])) {
                        $groupedRates['perProductFfl'] = [];
                    }
                    $groupedRates['perProductFfl'][$productId] = $rate;
                } else {
                    if (!isset($groupedRates['perProduct'])) {
                        $groupedRates['perProduct'] = [];
                    }
                    $groupedRates['perProduct'][$productId] = $rate;
                }
            } else {
                if ($rate->ffl) {
                    if (!isset($groupedRates['ffl'])) {
                        $groupedRates['ffl'] = [
                            'products' => implode(', ', array_map(function ($item) {
                                return $item->name;
                            }, $items['ffl'])),
                            'rates' => []
                        ];
                    }
                    if (!isset($groupedRates['ffl']['rates'][$rate->carrier])) {
                        $groupedRates['ffl']['rates'][$rate->carrier] = [];
                    }
                    $groupedRates['ffl']['rates'][$rate->carrier][] = $rate;
                } else {
                    if (!isset($groupedRates['other'])) {
                        $groupedRates['other'] = [
                            'products' => implode(', ', array_map(function ($item) {
                                return $item->name;
                            }, $items['other'])),
                            'rates' => []
                        ];
                    }
                    if (!isset($groupedRates['other']['rates'][$rate->carrier])) {
                        $groupedRates['other']['rates'][$rate->carrier] = [];
                    }
                    $groupedRates['other']['rates'][$rate->carrier][] = $rate;
                }
            }
        }

        return $groupedRates;
    }

    /**
     * Returns active shipping methods
     *
     * @return array
     */
    public function getShippingMethods()
    {
        $methods = [];

        foreach (Config::get('carriers') as $shippingMethod) {
            $object = new $shippingMethod['class'];

            if (!$object->isAvailable()) {
                continue;
            }

            $methods[] = [
                'method'       => $object->getCode(),
                'method_title' => $object->getTitle(),
                'description'  => $object->getDescription(),
            ];
        }

        return $methods;
    }

    /**
     * @param $items
     * @return array[]
     */
    protected function getItemsByType($items): array
    {
        $groupedItems = [
            'perProduct' => [],
            'ffl' => [],
            'other' => [],
        ];
        foreach ($items as $item) {
            $isFreeShipping = (bool)$item->product['free_shipping'];
            $perProductPrice = (float)$item->product['shipping_price'];
            //check shipping price if  it is a configurable product , get the price from parent
            if(!$perProductPrice){
                if($item->product['parent_id']){
                    $shipping_price_attribute = app('Webkul\Attribute\Repositories\AttributeRepository')->findwhere(["code" => 'shipping_price'])->first();
                    if($shipping_price_attribute){
                        $parent_product_shipping_price = app("Webkul\Product\Repositories\ProductAttributeValueRepository")->findwhere(['attribute_id'=> $shipping_price_attribute->id,'product_id' => $item->product['parent_id']])->first();
                        if($parent_product_shipping_price){
                            $perProductPrice=(float)$parent_product_shipping_price->float_value;
                        }
                    }

                }
            }
            if ($perProductPrice > 0 || $isFreeShipping) {
                if ($isFreeShipping) {
                    $price = 0.0;
                } else {
                    $addProductPrice = $item->product['shipping_price_additional'];
                    //check shipping_price_additional price if  it is a configurable product , get the price from parent
                    if(!$addProductPrice){
                        if($item->product['parent_id']){
                            $shipping_price_additional_attribute = app('Webkul\Attribute\Repositories\AttributeRepository')->findwhere(["code" => 'shipping_price_additional'])->first();
                            if($shipping_price_additional_attribute){
                                $parent_shipping_price_additional = app("Webkul\Product\Repositories\ProductAttributeValueRepository")->findwhere(['attribute_id'=> $shipping_price_additional_attribute->id,'product_id' => $item->product['parent_id']])->first();
                                if($parent_shipping_price_additional){
                                    $addProductPrice=(float)$parent_shipping_price_additional->text_value;
                                }
                            }

                        }
                    }

                    if ($addProductPrice && (float)$addProductPrice > 0) {
                        $price = $perProductPrice + (($item->quantity - 1) * $addProductPrice);
                    } else {
                        $price = $perProductPrice * $item->quantity;
                    }
                }
                $groupedItems['perProduct'][] = [
                    'price' => $price,
                    'item' => $item,
                    'ffl' => (strtolower($item->product->attribute_family->code) == config('app.firearmFamilyCode'))
                ];
            } else {
                if (strtolower($item->product->attribute_family->code) == config('app.firearmFamilyCode')) {
                    $groupedItems['ffl'][] = $item;
                } else {
                    $groupedItems['other'][] = $item;
                }
            }
        }
        return $groupedItems;
    }
}
