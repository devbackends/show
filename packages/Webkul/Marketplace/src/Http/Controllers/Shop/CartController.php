<?php

namespace Webkul\Marketplace\Http\Controllers\Shop;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Webkul\Checkout\Cart;
use Webkul\Checkout\Contracts\Cart as CartModel;
use Webkul\Marketplace\Helpers\Cart as CartHelper;
use Webkul\Product\Repositories\ProductFlatRepository;

class CartController extends Controller
{

    public function get(): JsonResponse
    {
        $data = app(CartHelper::class)->getAllCartsItems();

        return response()->json([
            'status' => true,
            'data' => $data,
        ], 200);
    }

    public function add(Request $request)
    {



        // Determine to with cart we should add product
        $sellerId = (new CartHelper())->getCartSeller($request->all());

        try {

            // Add product
            $cart = app(Cart::class)
                ->addProduct($request->get('product_id'), $sellerId, $request->all());

            if (is_array($cart) && isset($cart['warning'])) {
                $response = [
                    'status'  => 'warning',
                    'message' => $cart['warning'],
                ];
            }

            if ($cart instanceof CartModel) {
                $response = [
                    'status'         => 'success',
                    'totalCartItems' => sizeof($cart->items),
                    'message'        => trans('shop::app.checkout.cart.item.success'),
                ];

                if ($customer = auth()->guard('customer')->user()) {
                    app('Webkul\Customer\Repositories\WishlistRepository')->deleteWhere(['product_id' => $request->get('product_id'), 'customer_id' => $customer->id]);
                }

                if (request()->get('is_buy_now')) {
                    return redirect()->route('shop.checkout.onepage.index');
                }
            }
        } catch(Exception $exception) {
            $product = app('Webkul\Product\Repositories\ProductFlatRepository')->findOneWhere(['product_id' => $request->get('product_id')]);
            if($product->url_key){
                $response = [
                    'status'           => 'danger',
                    'message'          => trans($exception->getMessage()),
                    'redirectionRoute' => route('shop.product.index', $product->url_key),
                ];
            }else{
                $response = [
                    'status'           => 'danger',
                    'message'          => 'Requested quantity not available.'
                ];
            }

        }

        return response()->json($response ?? [
            'status'  => 'danger',
            'message' => trans('velocity::app.error.something_went_wrong'),
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'itemId' => 'required',
            'data' => 'required',
        ]);

        try {
            $result = app(Cart::class)->updateItem($data['itemId'], $data['data']);

            $response = [
                'status' => $result,
                'message' => ($result)
                    ? 'Cart item has been successfully updated'
                    : 'Something went wrong while updating cart item'
            ];
        } catch (Exception $e) {
            $response = [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }

        return response()->json($response);
    }

    public function remove(int $sellerId, int $itemId): JsonResponse
    {
        $result = app(Cart::class)->removeItem($sellerId, $itemId);

        if ($result) {
            $response = [
                'status'  => 'success',
                'label'   => trans('velocity::app.shop.general.alert.success'),
                'message' => trans('shop::app.checkout.cart.item.success-remove'),
            ];
        } else {
            $response = [
                'status'  => 'danger',
                'label'   => trans('velocity::app.shop.general.alert.error'),
                'message' => trans('velocity::app.error.something_went_wrong'),
            ];
        }

        return response()->json($response, 200);
    }

    public function addCustomerToCart(){
        return app(Cart::class)->addCustomerToCart();
    }

}