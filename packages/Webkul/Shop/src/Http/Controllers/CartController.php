<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\View\View;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\Marketplace\Helpers\Cart as CartHelper;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Checkout\Contracts\Cart as CartModel;
use Illuminate\Support\Facades\Event;
use Exception;
use Cart;

class  CartController extends Controller
{
    /**
     * WishlistRepository Repository object
     *
     * @var WishlistRepository
     */
    protected $wishlistRepository;

    /**
     * ProductRepository object
     *
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * Create a new controller instance.
     *
     * @param WishlistRepository $wishlistRepository
     * @param ProductRepository $productRepository
     */
    public function __construct(
        WishlistRepository $wishlistRepository,
        ProductRepository $productRepository
    )
    {
        $this->middleware('customer')->only(['moveToWishlist']);

        $this->wishlistRepository = $wishlistRepository;

        $this->productRepository = $productRepository;

        parent::__construct();
    }

    /**
     * Method to populate the cart page which will be populated before the checkout process.
     *
     * @return View
     * @throws Exception
     */
    public function index()
    {
        app(\Webkul\Checkout\Cart::class)->collectTotals();

        $cart = app(\Webkul\Checkout\Cart::class)->getCart();
        $cartItems = [];
        if ($cart) {
            $cartItems = app(CartHelper::class)->getAllCartsItems($cart);
        }

        return view($this->_config['view'])->with([
            'cart' => $cart,
            'cartItems' => $cartItems
        ]);
    }

    /**
     * Function for guests user to add the product in the cart.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add($id)
    {

        try {
            $result = app(\Webkul\Checkout\Cart::class)->addProduct($id, request()->all());

            if ($this->onWarningAddingToCart($result)) {
                session()->flash('warning', $result['warning']);

                return redirect()->back();
            }

            if ($result instanceof CartModel) {
                session()->flash('success', trans('shop::app.checkout.cart.item.success'));

                if ($customer = auth()->guard('customer')->user()) {
                    $this->wishlistRepository->deleteWhere(['product_id' => $id, 'customer_id' => $customer->id]);
                }

                if (request()->get('is_buy_now')) {
                    Event::dispatch('shop.item.buy-now', $id);

                    return redirect()->route('shop.checkout.onepage.index');
                }
            }
        } catch(\Exception $e) {
            session()->flash('error', trans($e->getMessage()));

            $product = $this->productRepository->find($id);

            return redirect()->route('shop.product.index', $product->url_key);
        }

        return redirect()->back();
    }

    /**
     * Removes the item from the cart if it exists
     *
     * @param  int  $itemId
     * @return Response
     */
    public function remove($itemId)
    {
        $result = app(\Webkul\Checkout\Cart::class)->removeItem($itemId);

        if ($result) {
            session()->flash('success', trans('shop::app.checkout.cart.item.success-remove'));
        }

        return redirect()->back();
    }

    /**
     * Updates the quantity of the items present in the cart.
     *
     * @return Response
     */
    public function updateBeforeCheckout()
    {
        try {
            $result = app(\Webkul\Checkout\Cart::class)->updateItems(request()->all());

            if ($result) {
                session()->flash('success', trans('shop::app.checkout.cart.quantity.success'));
            }
        } catch(\Exception $e) {
            session()->flash('error', trans($e->getMessage()));
        }

        return redirect()->back();
    }

    /**
     * Function to move a already added product to wishlist will run only on customer authentication.
     *
     * @param  int  $id
     * @return Response
     */
    public function moveToWishlist($id)
    {
        $result = app(\Webkul\Checkout\Cart::class)->moveToWishlist($id);

        if ($result) {
            session()->flash('success', trans('shop::app.checkout.cart.move-to-wishlist-success'));
        } else {
            session()->flash('warning', trans('shop::app.checkout.cart.move-to-wishlist-error'));
        }

        return redirect()->back();
    }

    /**
     * Apply coupon to the cart
     *
     * @return Response
    */
    public function applyCoupon()
    {
        $couponCode = request()->get('code');
        $seller_id = request()->get('seller_id');

        try {
            if (strlen($couponCode)) {
                app(\Webkul\Checkout\Cart::class)->setCouponCode($couponCode,$seller_id)->collectTotals();
                $cart_coupon_code=  app(\Webkul\Checkout\Cart::class)->getCart($seller_id)->coupon_code;
                if ($cart_coupon_code == $couponCode) {
                    return response()->json([
                        'success' => true,
                        'message' => trans('shop::app.checkout.total.success-coupon'),
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => trans('shop::app.checkout.total.invalid-coupon'),
            ]);
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => trans('shop::app.checkout.total.coupon-apply-issue'),
            ]);
        }
    }

    /**
     * Apply coupon to the cart
     *
     * @return Response
    */
    public function removeCoupon()
    {
        app(\Webkul\Checkout\Cart::class)->removeCouponCode()->collectTotals();

        return response()->json([
            'success' => true,
            'message' => trans('shop::app.checkout.total.remove-coupon'),
        ]);
    }

    /**
     * Returns true, if result of adding product to cart is an array and contains a key "warning"
     *
     * @param  array  $result
     * @return boolean
     */
    private function onWarningAddingToCart($result): bool
    {
        return is_array($result) && isset($result['warning']);
    }
}
