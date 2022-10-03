<?php

namespace Webkul\Velocity\Http\Controllers\Shop;

use Cart;
use Exception;
use Illuminate\Http\JsonResponse;
use Webkul\Checkout\Contracts\Cart as CartModel;
use Webkul\Marketplace\Helpers\Cart as CartHelper;
use Webkul\Product\Repositories\ProductFlatRepository;

class CartController extends Controller
{
    /**
     * Retrives the mini cart details
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function getMiniCartDetails()
    {

        $cart = cart()->getCart();

        if ($cart) {
            $response = [
                'status' => true,
                'mini_cart' => [
                    'cart_items' => app(CartHelper::class)->getAllCartsItems($cart),
                    'cart_details' => [
                        'base_sub_total' => core()->currency($cart->base_sub_total),
                    ],
                ],
            ];
        } else {
            $response = [
                'status' => false,
            ];
        }

        return response()->json($response, 200);
    }

    /**
     * Function for guests user to add the product in the cart.
     *
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function addProductToCart()
    {
        // Get seller id to put it in cart items
        $seller_id = 0;
        if (isset(request()->all()['seller'])) {
            if (!is_array(request()->all()['seller'])) {
                $seller = (array)json_decode(request()->all()['seller']);
                $seller_id = $seller['id'];
            }
            if (isset(request()->all()['seller']['id'])) {
                $seller_id = request()->all()['seller']['id'];
            }
        }

        $id = request()->get('product_id');

        try {
            $cart = Cart::addProduct($id, request()->all(), $seller_id);

            if (is_array($cart) && isset($cart['warning'])) {
                $response = [
                    'status' => 'warning',
                    'message' => $cart['warning'],
                ];
            }

            if ($cart instanceof CartModel) {
                $response = [
                    'status' => 'success',
                    'totalCartItems' => sizeof($cart->items),
                    'message' => trans('shop::app.checkout.cart.item.success'),
                ];

                if ($customer = auth()->guard('customer')->user()) {
                    app('Webkul\Customer\Repositories\WishlistRepository')->deleteWhere(['product_id' => $id, 'customer_id' => $customer->id]);
                }

                if (request()->get('is_buy_now')) {
                    return redirect()->route('shop.checkout.onepage.index');
                }
            }
        } catch (Exception $exception) {
            $product = app(ProductFlatRepository::class)->findOneWhere(['product_id' => $id]);

            $response = [
                'status' => 'danger',
                'message' => trans($exception->getMessage()),
                'redirectionRoute' => route('shop.product.index', $product->url_key),
            ];
        }

        return response()->json($response ?? [
                'status' => 'danger',
                'message' => trans('velocity::app.error.something_went_wrong'),
            ]);
    }

    /**
     * Removes the item from the cart if it exists
     *
     * @param $itemId
     * @return JsonResponse
     */
    public function removeProductFromCart($itemId)
    {
        $result = Cart::removeItem($itemId);

        if ($result) {
            $response = [
                'status' => 'success',
                'label' => trans('velocity::app.shop.general.alert.success'),
                'message' => trans('shop::app.checkout.cart.item.success-remove'),
            ];
        } else {
            $response = [
                'status' => 'danger',
                'label' => trans('velocity::app.shop.general.alert.error'),
                'message' => trans('velocity::app.error.something_went_wrong'),
            ];
        }

        return response()->json($response, 200);
    }
}