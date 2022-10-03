<?php

namespace Webkul\TableRate\Helpers;

use Exception;
use Webkul\TableRate\Repositories\ShippingRateRepository;
use Webkul\TableRate\Repositories\SuperSetRateRepository;

class ShippingHelper
{
    /**
     * @var array
     */
    protected $items;

    /**
     * @var \Webkul\Checkout\Models\Cart
     */
    protected $cart;

    /**
     * ShippingHelper constructor.
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        $this->cart = app(\Webkul\Checkout\Cart::class)->getCart();

        $this->items = empty($items) ? $this->cart->items : $items;
    }

    /**
     * Get all rates per unit
     *
     * @throws Exception
     */
    public function getPerUnitRates(): array
    {
        $data = $this->getAvailablePerUnitRates();

        $shippingMethodRates    = [];
        $codeWiseMethods        = [];
        $availableRates         = $data['shippingMethods'];
        $cartItemsCount         = $data['itemsCount'];

        if (!empty($availableRates)) {
            foreach ($availableRates as $productId => $shipping_rates) {
                if (!empty($shipping_rates)) {
                    foreach($shipping_rates as $superset_code => $rate_detail) {
                        $codeWiseMethods[$superset_code][] = $rate_detail;
                    }
                } else {
                    return [];
                }
            }

            $count = 0;
            $countCalculated = false;
            foreach ($codeWiseMethods as $superset_code => $commonMethods) {
                if (!$countCalculated) {
                    $count += count($commonMethods);
                    $countCalculated = true;
                }
                $shippingMethodRates[$superset_code] = $commonMethods;
            }
            if ($count != $cartItemsCount) return [];
        }

        return $shippingMethodRates;
    }

    /**
     * Get all rates per order
     *
     * @return array
     * @throws Exception
     */
    public function getPerSectionRates(): array
    {
        $subTotal = 0;
        $availableRates = [];
        if ($this->cart->seller_id === 0) {
            foreach ($this->items as $item) {
                if ($item->type === 'virtual') continue;
                $subTotal += $item->price;
            }
        }

        $supesetRates = app(SuperSetRateRepository::class)->getModel()
            ->leftJoin('tablerate_supersets', 'tablerate_superset_rates.tablerate_superset_id', 'tablerate_supersets.id')
            ->addSelect('tablerate_superset_rates.*')
            ->addSelect(
                'tablerate_supersets.name',
                'tablerate_supersets.code'
            )
            ->where('tablerate_supersets.status', 1)
            ->where('tablerate_superset_rates.price_from', '<=', core()->convertToBasePrice($subTotal))
            ->where('tablerate_superset_rates.price_to', '>=', core()->convertToBasePrice($subTotal))
            ->orderBy('tablerate_superset_rates.created_at')
            ->get();

        if (count($supesetRates) > 0) {
            foreach ($supesetRates as $supesetRate) {
                $availableRates[$supesetRate->code][] = [
                    'price'         => $subTotal,
                    'base_price'    => $subTotal,
                    'shipping_cost' => $supesetRate->price,
                    'superset_name' => $supesetRate->name,
                    'superset_code' => $supesetRate->code,
                    'quantity'      => 1,
                ];
            }
        }

        return $availableRates;
    }

    /**
     * Find Appropriate TableRate Methods
     *
     * @return array
     * @throws Exception
     */
    public function getAvailablePerUnitRates(): array
    {
        $shippingMethods = [];
        $itemsCount = 0;

        if ($this->cart->seller_id == 0) {
            foreach ($this->items as $item) {
                if ($item->type === 'virtual') continue;
                $itemsCount++;
                $shippingRates = $this->getAvailableTableRates($item);

                if (count($shippingRates) > 0) {
                    if ($item->quantity > 1) {
                        foreach ($shippingRates as $code => $rate) {
                            $shippingRates[$code]['shipping_cost'] *= $item->quantity;
                        }
                    }
                    $shippingMethods[$item->product_id] = $shippingRates;
                }
            }
        }

        return [
            'shippingMethods' => $shippingMethods,
            'itemsCount' => $itemsCount,
        ];
    }

    /**
     * Get All The Available ShippingRates
     *
     * @param $cartItem
     * @return array
     * @throws Exception
     */
    public function getAvailableTableRates($cartItem): array
    {
        $availableRates = [];
        $supesetRates = app(SuperSetRateRepository::class)->getModel()
            ->leftJoin('tablerate_supersets', 'tablerate_superset_rates.tablerate_superset_id', 'tablerate_supersets.id')
            ->addSelect('tablerate_superset_rates.*')
            ->addSelect(
                'tablerate_supersets.name',
                'tablerate_supersets.code'
            )
            ->where('tablerate_supersets.status', 1)
            ->where('tablerate_superset_rates.price_from', '<=', core()->convertToBasePrice($cartItem['price']))
            ->where('tablerate_superset_rates.price_to', '>=', core()->convertToBasePrice($cartItem['price']))
            ->orderBy('tablerate_superset_rates.created_at')
            ->get();

        if (count($supesetRates) > 0) {
            foreach ($supesetRates as $supesetRate) {
                $availableRates[$supesetRate->code] = [
                    'price'         => $cartItem['price'],
                    'base_price'    => $cartItem['base_price'],
                    'weight'        => $cartItem['weight'],
                    'shipping_cost' => $supesetRate->price,
                    'superset_name' => $supesetRate->name,
                    'superset_code' => $supesetRate->code,
                    'quantity'      => $cartItem['quantity']
                ];
            }
        } else {
            $availableRates = $this->getShippingRates($cartItem);
        }

        return $availableRates;
    }

    /**
     * Get ShippingRate
     *
     * @param $cartItem
     * @return array $shipping_rates
     * @throws Exception
     */
    public function getShippingRates($cartItem): array
    {
        $shipping_rates     = [];
        $shippingAddress    = $this->cart->shipping_address;

        $shippingRates = app(ShippingRateRepository::class)->getModel()
            ->addSelect('tablerate_shipping_rates.*')
            ->addSelect('tablerate_supersets.name', 'tablerate_supersets.code')
            ->leftJoin('tablerate_supersets', 'tablerate_shipping_rates.tablerate_superset_id', 'tablerate_supersets.id')
            ->where('tablerate_supersets.status', 1)
            ->orderBy('tablerate_shipping_rates.created_at')
            ->get();

        if ( count($shippingRates) > 0 ) {
            foreach ($shippingRates as $shippingRate) {
                //Numeric Range
                if ($shippingRate->is_zip_range == 1) {
                    if ( $shippingRate->zip_from <= $shippingAddress->postcode
                        && $shippingRate->zip_to >= $shippingAddress->postcode ) {

                        if ($shippingRate->weight_from <= $cartItem['weight']
                            && $shippingRate->weight_to >= $cartItem['weight']) {
                            $shipping_rates[$shippingRate->code] = [
                                'price'         => $cartItem['price'],
                                'base_price'    => $cartItem['base_price'],
                                'weight'        => $cartItem['weight'],
                                'shipping_cost' => $shippingRate->price,
                                'superset_name' => $shippingRate->name,
                                'superset_code' => $shippingRate->code,
                                'quantity'      => $cartItem['quantity']
                            ];
                        }
                    }
                } else {
                    //Alphanumeric Zip
                    if ( $shippingRate->zip_code == '*'
                        || $shippingRate->zip_code == $shippingAddress->postcode) {
                        if ($shippingRate->weight_from <= $cartItem['weight']
                            && $shippingRate->weight_to >= $cartItem['weight']) {
                            $shipping_rates[$shippingRate->code] = [
                                'price'         => $cartItem['price'],
                                'base_price'    => $cartItem['base_price'],
                                'weight'        => $cartItem['weight'],
                                'shipping_cost' => $shippingRate->price,
                                'superset_name' => $shippingRate->name,
                                'superset_code' => $shippingRate->code,
                                'quantity'      => $cartItem['quantity']
                            ];
                        }
                    }
                }
            }
        }

        return $shipping_rates;
    }
}