<?php

namespace Webkul\PWA\Http\Controllers\Shop;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Webkul\API\Http\Controllers\Shop\Controller;
use Webkul\API\Http\Resources\Checkout\CartShippingRate as CartShippingRateResource;
use Webkul\Checkout\Cart as CartService;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Checkout\Repositories\CartItemRepository;
use Webkul\PWA\Http\Resources\Checkout\Cart as CartResource;
use Cart;
use Webkul\PWA\Http\Resources\Checkout\ShippingRates;
use Webkul\Shipping\Facades\Shipping;

/**
 * Cart controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CartController extends Controller
{
    /**
     * Contains current guard
     *
     * @var array
     */
    protected $guard;

    /**
     * CartRepository object
     *
     * @var Object
     */
    protected $cartRepository;

    /**
     * CartItemRepository object
     *
     * @var Object
     */
    protected $cartItemRepository;

    /**
     * Controller instance
     *
     * @param CartRepository $cartRepository
     * @param CartItemRepository $cartItemRepository
     */
    public function __construct(
        CartRepository $cartRepository,
        CartItemRepository $cartItemRepository
    )
    {
        $this->guard = request()->has('token') ? 'api' : 'customer';

        auth()->setDefaultDriver($this->guard);

        $this->_config = request('_config');

        $this->cartRepository = $cartRepository;

        $this->cartItemRepository = $cartItemRepository;
    }

    /**
     * @return JsonResponse
     */
    public function get()
    {
        $customer = auth($this->guard)->user();

        $cart = app(CartService::class)->getCart();

        return response()->json([
            'data' => $cart ? new CartResource($cart) : null
        ]);
    }

    public function getShippingRates()
    {
        return response()->json([
            'data' => Shipping::collectRates((bool)request()->get('refresh'))
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param $id
     * @return JsonResponse
     */
 /*   public function store($id)
    {
        Event::dispatch('checkout.cart.item.add.before', $id);

        $result = Cart::add($id, request()->except('_token'));

        if (! $result) {
            $message = session()->get('warning') ?? session()->get('error');
            return response()->json([
                'error' => session()->get('warning')
            ], 400);
        }

        Event::dispatch('checkout.cart.item.add.after', $result);

        Cart::collectTotals();

        $cart = Cart::getCart();

        return response()->json([
            'message' => 'Product added to cart successfully.',
            'data' => $cart ? new CartResource($cart) : null
        ]);
    }*/

    /**
     * Update the specified resource in storage.
     *
     * @return JsonResponse
     */
    public function update()
    {
        foreach (request()->get('qty') as$qty) {
            if ($qty <= 0) {
                return response()->json([
                    'message' => trans('shop::app.checkout.cart.quantity.illegal')
                ], 401);
            }
        }

        foreach (request()->get('qty') as $itemId => $qty) {
            $item = $this->cartItemRepository->findOneByField('id', $itemId);

            Event::dispatch('checkout.cart.item.update.before', $itemId);

            Cart::updateItem($item->product_id, ['quantity' => $qty], $itemId);

            Event::dispatch('checkout.cart.item.update.after', $item);
        }

        Cart::collectTotals();

        $cart = Cart::getCart();

        return response()->json([
            'message' => 'Cart updated successfully.',
            'data' => $cart ? new CartResource($cart) : null
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return JsonResponse
     */
    public function destroy()
    {
        Event::dispatch('checkout.cart.delete.before');

        Cart::deActivateCart();

        Event::dispatch('checkout.cart.delete.after');

        $cart = Cart::getCart();

        return response()->json([
            'message' => 'Cart removed successfully.',
            'data' => $cart ? new CartResource($cart) : null
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroyItem($id)
    {
        Event::dispatch('checkout.cart.item.delete.before', $id);

        Cart::removeItem($id);

        Event::dispatch('checkout.cart.item.delete.after', $id);

        Cart::collectTotals();

        $cart = Cart::getCart();

        return response()->json([
            'message' => 'Cart removed successfully.',
            'data' => $cart ? new CartResource($cart) : null
        ]);
    }

    /**
     * Function to move a already added product to wishlist
     * will run only on customer authentication.
     *
     * @param instance cartItem $id
     * @return JsonResponse
     */
    public function moveToWishlist($id)
    {
        Event::dispatch('checkout.cart.item.move-to-wishlist.before', $id);

        Cart::moveToWishlist($id);

        Event::dispatch('checkout.cart.item.move-to-wishlist.after', $id);

        Cart::collectTotals();

        $cart = Cart::getCart();

        return response()->json([
            'message' => 'Cart item moved to wishlist successfully.',
            'data' => $cart ? new CartResource($cart) : null
        ]);
    }

    /**
     * Apply coupon to the cart
     *
     * @return JsonResponse
    */
    public function applyCoupon()
    {
        $couponCode = request()->get('code');

        try {
            if (strlen($couponCode)) {
                Cart::setCouponCode($couponCode)->collectTotals();

                if (Cart::getCart()->coupon_code == $couponCode) {
                    return response()->json([
                        'success' => true,
                        'message' => trans('shop::app.checkout.total.success-coupon'),
                        'data' => [
                            'cart' => new CartResource(Cart::getCart())
                        ]
                    ], 200);
                }
            }

            return response()->json([
                'success' => false,
                'message' => trans('shop::app.checkout.total.invalid-coupon')
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => trans('shop::app.checkout.total.coupon-apply-issue')
            ], 400);
        }
    }

    /**
     * Apply coupon to the cart
     *
     * @return JsonResponse
    */
    public function removeCoupon()
    {
        Cart::removeCouponCode()->collectTotals();

        return response()->json([
            'success' => true,
            'message' => trans('shop::app.checkout.total.remove-coupon'),
            'data' => [
                'cart' => new CartResource(Cart::getCart())
            ]
        ]);
    }
}