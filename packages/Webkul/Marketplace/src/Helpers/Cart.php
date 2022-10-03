<?php

namespace Webkul\Marketplace\Helpers;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Config;
use Webkul\Checkout\Models\Cart as CartModel;
use Webkul\Marketplace\Repositories\SellerRepository;

class Cart
{

    /**
     * @var array
     */
    protected $items = [
        'company' => [], // contains main products from marketplace and products from other merchants
        'seller' => [], // contains seller's products from marketplace
    ];

    /**
     * @param array $data
     * @return int
     */
    public function getCartSeller(array $data): int
    {
        // Take care of parameters
        if (!isset($data['product_id'])) return false;

        $productId = $data['product_id'];
        $sellerId = 0;
        if (isset($data['seller_info'])) {
            if (isset($data['seller_info']['seller_id'])) {
                $sellerId = $data['seller_info']['seller_id'];
            }
        }

        // Check if product exist with this seller
        if (!$this->checkSellerProduct($sellerId, $productId)) return false;

        // Return seller id
        return $sellerId;
    }

    /**
     * Main helper method that should return parsed cart items
     *
     * @return array
     * @throws Exception
     */
    public function getAllCartsItems(): array
    {
        // Fetch all carts for customer
        $carts = $this->getCustomerCarts();

        // Set carts sellers
        $cartsTotal = 0;
        $carts = $carts->map(function ($cart) use (&$cartsTotal) {
            $cart->selected_shipping_rate=$cart->selected_shipping_rate;
            $cart->selected_ffl_shipping_rate=$cart->selected_ffl_shipping_rate;
            $cart->items = $cart->items->map(function ($item) {
                $item->images = $item->product->getTypeInstance()->getBaseImage($item);
                $item->url_key = $item->product_flat->url_key;
                if($item->type=='booking') {
                    $bookingProduct = app('Webkul\BookingProduct\Type\Booking')->getBookingProduct($item->product_id);
                    if($bookingProduct->type=='event'){
                        $typeHelper = app(app('Webkul\BookingProduct\Helpers\Booking')->getTypeHepler($bookingProduct->type));

                        $isCartItemExceedNumberOfAvailableTickets=$typeHelper->isCartItemExceedNumberOfAvailableTickets($item,$bookingProduct);
                        if($isCartItemExceedNumberOfAvailableTickets){
                            $item->note='Item Exceed number of available ticket';
                        }


                        $isCartItemExceedMaximumTicketsPerBooking=$typeHelper->isCartItemExceedMaximumTicketsPerBooking($item,$bookingProduct);
                        if($isCartItemExceedMaximumTicketsPerBooking){
                            $item->note='Item Exceed Maximum Ticket per booking';
                        }
                        $isCartItemExceedMaximumEventSize=$typeHelper->isCartItemExceedMaximumEventSize($item,$bookingProduct);
                        if($isCartItemExceedMaximumEventSize){
                            $item->note='Item Exceed Event Size';
                        }
                    }
                }

            });
            $cart->seller = app(SellerRepository::class)->find($cart->seller_id);
            $cart->seller->parsed_payment_methods = array_map(function ($code) {
                return Config::get('paymentmethods')[$code] ?? [];
            }, explode(',', $cart->seller->payment_methods));
            $cartsTotal += $cart->base_sub_total;
            return $cart;
        });

        return [
            'carts' => $carts,
            'cartsDetails' => [
                'cartsTotal' => core()->currency($cartsTotal),
            ]
        ];
    }

    /**
     * @param int $sellerId
     * @param int $productId
     * @return bool
     */
    protected function checkSellerProduct(int $sellerId, int $productId): bool
    {
        return true;
    }

    /**
     * @return Builder[]|Collection|CartModel[]
     */
    protected function getCustomerCarts()
    {
        // Set wheres
        if ($customer = auth()->guard('customer')->user()) {
            return \Webkul\Checkout\Models\Cart::query()->where([
                'customer_id' => $customer->id,
                'is_active' => 1
            ])->get();
        } else {
            $ids = [];
            if (is_array(session()->get('guest_carts'))) {
                foreach (session()->get('guest_carts') as $id) {
                    array_push($ids, $id);
                }
            }
            return \Webkul\Checkout\Models\Cart::query()->where([
                'is_guest' => 1,
                'is_active' => 1
            ])->whereIn('id', $ids)->get();
        }
    }
}