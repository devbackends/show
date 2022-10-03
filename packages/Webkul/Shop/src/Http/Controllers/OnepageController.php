<?php

namespace Webkul\Shop\Http\Controllers;

use Devvly\ClearentPayment\Repositories\ClearentCartRepository;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;
use Webkul\API\Http\Resources\Customer\Customer as CustomerResource;
use Webkul\Marketplace\Models\Seller;
use Webkul\PWA\Http\Resources\Checkout\Cart as CartResource;
use Webkul\Shipping\Facades\Shipping;
use Webkul\Payment\Facades\Payment;
use Webkul\Checkout\Http\Requests\CustomerAddressForm;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Customer\Repositories\CustomerRepository;
use Illuminate\Support\Facades\Storage;
use File;

class OnepageController extends Controller
{
    /**
     * OrderRepository object
     *
     * @var OrderRepository
     */
    protected $orderRepository;

     /**
     * customerRepository instance object
     *
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * Create a new controller instance.
     *
     * @param  OrderRepository  $orderRepository
     * @param  CustomerRepository  $customerRepository
     * @return void
     */
    public function __construct(
        OrderRepository $orderRepository,
        CustomerRepository $customerRepository
    )
    {
        $this->orderRepository = $orderRepository;

        $this->customerRepository = $customerRepository;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @param int $sellerId
     * @return RedirectResponse|View
     */
    public function index($sellerId = -1)
    {

        $cart = app(\Webkul\Checkout\Cart::class)->getCart($sellerId);

        if (app(\Webkul\Checkout\Cart::class)->hasError()) {
            return redirect()->route('shop.checkout.cart.index');
        }

        app(\Webkul\Checkout\Cart::class)->collectTotals();

        $data=[
            'seller' => Seller::query()->find($sellerId),
            'json_cart' => $cart ? json_encode(new CartResource($cart)) : null,
            'cart' => $cart,
        ];
        if(auth()->guard('customer')->check()){
            $data['customer']=json_encode(new CustomerResource(auth()->guard('customer')->user()));
        }else{
            $data['customer']=json_encode(null);
        }
       $data['terms']=File::get('../packages/Webkul/Marketplace/src/Resources/views/shop/market/terms-and-conditions.blade.php');

        return view($this->_config['view'])->with($data);
    }

    /**
     * Return order short summary
     *
     * @return Response
     * @throws Throwable
     */
    public function summary()
    {
        $cart = app(\Webkul\Checkout\Cart::class)->getCart();

        return response()->json([
            'html' => view('shop::checkout.total.summary', compact('cart'))->render(),
        ]);
    }


    /**
     * @param CustomerAddressForm $request
     * @return JsonResponse
     * @throws Exception
     */
    public function saveAddress(CustomerAddressForm $request)
    {
        $data = request()->all();
        if (! auth()->guard('customer')->check() && ! app(\Webkul\Checkout\Cart::class)->getCart()->hasGuestCheckoutItems()) {
            return response()->json(['redirect_url' => route('customer.session.index')], 403);
        }

        $data['billing']['address1'] = implode(PHP_EOL, array_filter($data['billing']['address1']));
        $data['shipping']['address1'] = implode(PHP_EOL, array_filter($data['shipping']['address1']));

        if (app(\Webkul\Checkout\Cart::class)->hasError() || !app(\Webkul\Checkout\Cart::class)->saveCustomerAddress($data)) {
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
        } else {
            $cart = app(\Webkul\Checkout\Cart::class)->getCart();
            app(\Webkul\Checkout\Cart::class)->collectTotals();
            if ($cart->haveStockableItems()) {
                if (!$rates = Shipping::collectRates()) {
                    return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
                } else {
                    return response()->json($rates);
                }
            } else {
                return response()->json(Payment::getSupportedPaymentMethods());
            }
        }
    }

    /**
     * @return JsonResponse
     */
    public function saveShipping()
    {
        $shippingMethod = request()->get('shipping_method');
        if (app(\Webkul\Checkout\Cart::class)->hasError() || !$shippingMethod || !app(\Webkul\Checkout\Cart::class)->saveShippingMethod($shippingMethod)) {
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
        }

        app(\Webkul\Checkout\Cart::class)->collectTotals();

        return response()->json(Payment::getSupportedPaymentMethods());
    }

    /**
     * @return JsonResponse
     * @throws Throwable
     */
    public function savePayment()
    {
        $payment = request()->get('payment');

        if (app(\Webkul\Checkout\Cart::class)->hasError() || ! $payment || ! app(\Webkul\Checkout\Cart::class)->savePaymentMethod($payment)) {
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
        }

        app(\Webkul\Checkout\Cart::class)->collectTotals();

        $cart = app(\Webkul\Checkout\Cart::class)->getCart();

        return response()->json([
            'jump_to_section' => 'review',
            'html'            => view('shop::checkout.onepage.review', compact('cart'))->render(),
        ]);
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function saveOrder()
    {
        if (app(\Webkul\Checkout\Cart::class)->hasError()) {
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
        }

        app(\Webkul\Checkout\Cart::class)->collectTotals();

        $this->validateOrder();

        $cart = app(\Webkul\Checkout\Cart::class)->getCart();

        if ($redirectUrl = Payment::getRedirectUrl($cart)) {
            return response()->json([
                'success'      => true,
                'redirect_url' => $redirectUrl,
            ]);
        }

        $order = $this->orderRepository->create(app(\Webkul\Checkout\Cart::class)->prepareDataForOrder());

        app(\Webkul\Checkout\Cart::class)->deActivateCart();

        session()->flash('order', $order);

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Order success page
     *
     * @return Application|Factory|RedirectResponse|View
     */
    public function success()
    {
        if (! $order = session('order')) {
            return redirect()->route('shop.checkout.cart.index');
        }
        $order=app('Webkul\Sales\Repositories\OrderRepository')->find(json_decode(session('order'))->id);
        $order->shippingAddress = $order->getShippingAddressAttribute();
        $order->payment = $order->payment()->first();

        // Get card
  /*      $cardInfo = app(ClearentCartRepository::class)->findWhere([
            'cart_id' => $order->cart_id
        ])->last();
        if ($cardInfo) {
            $order->payment->card = $cardInfo->card()->first();
        }*/

        // Check if there is another products from another sellers
        $isCartEmpty = app(\Webkul\Marketplace\Helpers\Cart::class)->getAllCartsItems()['carts']->isEmpty();

        return view($this->_config['view'], compact('order', 'isCartEmpty'));
    }

    /**
     * Validate order before creation
     *
     * @return void|Exception
     * @throws Exception
     */
    public function validateOrder()
    {
        $cart = app(\Webkul\Checkout\Cart::class)->getCart();

        if ($cart->haveStockableItems() && ! $cart->shipping_address) {
            throw new Exception(trans('Please check shipping address.'));
        }

        if (! $cart->billing_address) {
            throw new Exception(trans('Please check billing address.'));
        }

        if ($cart->haveStockableItems() && ! $cart->selected_shipping_rate) {
            throw new Exception(trans('Please specify shipping method.'));
        }

        if (! $cart->payment) {
            throw new Exception(trans('Please specify payment method.'));
        }
    }

    /**
     * Check Customer is exist or not
     *
     * @return Response
     */
    public function checkExistCustomer()
    {
       $customer = $this->customerRepository->findOneWhere([
            'email' => request()->email,
       ]);

       if (! is_null($customer)) {
           return 'true';
       }

       return 'false';
    }

    /**
     * @return JsonResponse
     * @throws ValidationException
     */
    public function loginForCheckout()
    {
        $this->validate(request(), [
            'email' => 'required|email'
        ]);

        if (! auth()->guard('customer')->attempt(request(['email', 'password']))) {
            return response()->json(['error' => trans('shop::app.customer.login-form.invalid-creds')]);
        }

        app(\Webkul\Checkout\Cart::class)->merge();

        return response()->json(['success' => 'Login successfully']);
    }

    /**
     * @return JsonResponse
     * @throws ValidationException
     */
    public function applyCoupon()
    {
        $this->validate(request(), [
            'code' => 'string|required',
        ]);

        $code = request()->input('code');

        $result = $this->coupon->apply($code);

        if ($result) {
            app(\Webkul\Checkout\Cart::class)->collectTotals();

            return response()->json([
                'success' => true,
                'message' => trans('shop::app.checkout.total.coupon-applied'),
                'result'  => $result,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => trans('shop::app.checkout.total.cannot-apply-coupon'),
                'result'  => null,
            ], 422);
        }

        return $result;
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function removeCoupon()
    {
        $result = $this->coupon->remove();

        if ($result) {
            app(\Webkul\Checkout\Cart::class)->collectTotals();

            return response()->json([
                'success' => true,
                'message' => trans('admin::app.promotion.status.coupon-removed'),
                'data'    => [
                    'grand_total' => core()->currency(app(\Webkul\Checkout\Cart::class)->getCart()->grand_total),
                ],
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => trans('admin::app.promotion.status.coupon-remove-failed'),
                'data'    => null,
            ], 422);
        }
    }
}
