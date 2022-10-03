<?php

namespace Webkul\PWA\Http\Controllers\Shop;

use Exception;
use Illuminate\Http\JsonResponse;
use Prettus\Validator\Exceptions\ValidatorException;
use Webkul\API\Http\Controllers\Shop\Controller;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Checkout\Cart as CartService;
use Webkul\Checkout\Http\Requests\CustomerBillingAddressForm;
use Webkul\Checkout\Http\Requests\CustomerShippingAddressForm;
use Webkul\Checkout\Http\Requests\CustomerShippingFflAddress;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Checkout\Repositories\CartItemRepository;
use Webkul\Core\Models\CountryState;
use Webkul\Marketplace\Helpers\Cart as CartHelper;
use Webkul\Shipping\Facades\Shipping;
use Webkul\Payment\Facades\Payment;
use Webkul\PWA\Http\Resources\Checkout\Cart as CartResource;
use Webkul\API\Http\Resources\Checkout\CartShippingRate as CartShippingRateResource;
use Webkul\PWA\Http\Resources\Sales\Order as OrderResource;
use Webkul\Checkout\Http\Requests\CustomerAddressForm;
use Webkul\Sales\Repositories\OrderRepository;

/**
 * Checkout controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @author    Vivek Sharma <viveksh047@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CheckoutController extends Controller
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
     * @var CartHelper
     */
    protected $cartHelper;

    /**
     * Controller instance
     *
     * @param CartRepository $cartRepository
     * @param CartItemRepository $cartItemRepository
     * @param OrderRepository $orderRepository
     * @param CartHelper $cartHelper
     */
    public function __construct(
        CartRepository $cartRepository,
        CartItemRepository $cartItemRepository,
        OrderRepository $orderRepository,
        CartHelper $cartHelper
    )
    {
        $this->guard = request()->has('token') ? 'api' : 'customer';

        auth()->setDefaultDriver($this->guard);

        $this->_config = request('_config');

        $this->cartRepository = $cartRepository;

        $this->cartItemRepository = $cartItemRepository;

        $this->orderRepository = $orderRepository;

        $this->cartHelper = $cartHelper;
    }

    /**
     * @param CustomerBillingAddressForm $request
     * @return JsonResponse
     * @throws ValidatorException
     */
    public function saveBillingAddress(CustomerBillingAddressForm $request)
    {
        $request->validated();
        $data = $request->all();

        $data['billing']['address1'] = implode(PHP_EOL, array_filter($data['billing']['address1']));

        if (app(CartService::class)->hasError(request()->get('sellerId')) || !app(CartService::class)->saveCustomerBillingAddress($data)) {
            abort(400);
        }

        return new JsonResponse([
            'data' => [
                'cart' => new CartResource(app(CartService::class)->getCart(request()->get('sellerId'), true)),
            ],
        ]);
    }

    /**
     * @param CustomerShippingAddressForm $request
     * @return JsonResponse
     * @throws ValidatorException
     */
    public function saveShippingAddress(CustomerShippingAddressForm $request)
    {
        $request->validated();
        $data = $request->all();

        $data['shipping']['address1'] = implode(PHP_EOL, array_filter($data['shipping']['address1']));

        if (app(CartService::class)->hasError(request()->get('sellerId'))) {
            abort(400);
        }

        // Check if cart have items that can't be shipped to shipping state
        $canBeShipped = true;
        $usAttr = app(AttributeRepository::class)->getAttributeByCode('unavailable_in_states');
        $shippingState = CountryState::query()->where('code', $data['shipping']['state'])->first();
        $optionId = 0;
        foreach ($usAttr->options as $option) {
            if ($option->admin_name === $shippingState->default_name) {
                $optionId = $option->id;
            }
        }
        foreach (app(CartService::class)->getCart()->items as $item) {
            if (in_array($optionId, explode(',', $item->product['unavailable_in_states']))) {
                $canBeShipped = false;
            }
        }

        if ($canBeShipped) {
            if (app(CartService::class)->saveCustomerShippingAddress($data)) {
                return new JsonResponse([
                    'data' => [
                        'cart' => new CartResource(app(CartService::class)->getCart(request()->get('sellerId'), true)),
                    ],
                ]);
            } else {
                abort(400);
            }
        } else {
            return new JsonResponse([
                'data' => [
                    'error' => true,
                    'message' => 'One or more products in your cart can not be shipped to your state.',
                ],
            ]);
        }
    }

    public function saveFflShippingAddress(CustomerShippingFflAddress $request)
    {
        $request->validated();
        $data = $request->all();
        $data['shipping']['address1'] = implode(PHP_EOL, array_filter($data['shipping']['address1']));
        if (app(CartService::class)->hasError(request()->get('sellerId')) || !app(CartService::class)->saveCustomerFflShippingAddress($data)) {
            abort(400);
        }

        return new JsonResponse([
            'data' => [
                'cart' => new CartResource(app(CartService::class)->getCart(request()->get('sellerId'), true)),
            ],
        ]);
    }

    public function saveFflShipping()
    {
        $shippingMethod = request()->get('shipping_method');

        if (app(CartService::class)->hasError(request()->get('sellerId')) || !$shippingMethod || !app(CartService::class)->saveFflShippingMethod($shippingMethod))
            abort(400);

        app(CartService::class)->collectTotals();

        return response()->json([
            'data' => [
                'cart' => new CartResource(app(CartService::class)->getCart(request()->get('sellerId'), true)),
            ],
        ]);
    }

    /**
     * Saves customer address.
     *
     * @param CustomerAddressForm $request
     * @return JsonResponse
     * @throws Exception
     */
    public function saveAddress(CustomerAddressForm $request)
    {
        $data = request()->all();

        $data['billing']['address1'] = implode(PHP_EOL, array_filter($data['billing']['address1']));
        $data['shipping']['address1'] = implode(PHP_EOL, array_filter($data['shipping']['address1']));

        if (isset($data['billing']['id']) && str_contains($data['billing']['id'], 'address_')) {
            unset($data['billing']['id']);
            unset($data['billing']['address_id']);
        }

        if (isset($data['shipping']['id']) && str_contains($data['shipping']['id'], 'address_')) {
            unset($data['shipping']['id']);
            unset($data['shipping']['address_id']);
        }

        if (app(CartService::class)->hasError(request()->get('sellerId')) || !app(CartService::class)->saveCustomerAddress($data) || !Shipping::collectRates())
            abort(400);

        $rates = [];

        foreach (Shipping::getGroupedAllShippingRates() as $code => $shippingMethod) {
            $rates[] = [
                'carrier_title' => $shippingMethod['carrier_title'],
                'rates'         => CartShippingRateResource::collection(collect($shippingMethod['rates'])),
            ];
        }

        app(CartService::class)->collectTotals();

        return response()->json([
            'data' => [
                'rates' => $rates,
                'cart'  => new CartResource(app(CartService::class)->getCart(request()->get('sellerId'), true)),
            ],
        ]);
    }

    /**
     * Saves shipping method.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function saveShipping()
    {
        app(CartService::class)->getCart(request()->get('sellerId'));
        $shippingRates = request()->get('selectedRates');

        if ((isset($shippingRates['ffl']) || isset($shippingRates['perProductFfl']))
                && (!empty($shippingRates['ffl']) || !empty($shippingRates['perProductFfl']))) {
            if (!app(CartService::class)->saveFflShippingMethod($shippingRates['ffl'] ?? 0, $shippingRates['perProductFfl'] ?? [])) {
                return response()->json(['redirectUrl' => route('shop.checkout.cart.index')], 403);
            }
        }
        if ((isset($shippingRates['other']) || isset($shippingRates['perProduct']))
            && (!empty($shippingRates['other']) || !empty($shippingRates['perProduct']))) {
            if (!app(CartService::class)->saveShippingMethod($shippingRates['other'] ?? 0, $shippingRates['perProduct'] ?? [])) {
                return response()->json(['redirectUrl' => route('shop.checkout.cart.index')], 403);
            }
        }

        app(CartService::class)->collectTotals();

        return response()->json([
            'data' => [
                'cart' => new CartResource(app(CartService::class)->getCart(request()->get('sellerId'), true)),
            ],
        ]);
    }

    /**
     * Saves payment method.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function savePayment()
    {
        $payment = request()->get('payment');

        if (app(CartService::class)->hasError(request()->get('sellerId')) || !$payment || !app(CartService::class)->savePaymentMethod($payment))
            abort(400);

        return response()->json([
            'data' => [
                'cart' => new CartResource(app(CartService::class)->getCart(request()->get('sellerId'), true)),
            ],
        ]);
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function saveOrder()
    {
        if (app(CartService::class)->hasError(request()->get('sellerId')))
            abort(400);

        app(CartService::class)->collectTotals();

        if (request()->has('onlyWithoutShipping') && request()->get('onlyWithoutShipping')) {
            session()->put('onlyWithoutShipping', true);
        }

        $this->validateOrder();

        $cart = app(CartService::class)->getCart();
        if ($redirectUrl = Payment::getRedirectUrl($cart)) {
            return response()->json([
                'success'      => true,
                'redirect_url' => $redirectUrl,
            ]);
        }

        $order = $this->orderRepository->create(app(CartService::class)->prepareDataForOrder());

        $order2 = $this->orderRepository->findOneWhere([
            'cart_id' => app(CartService::class)->getCart()->id
        ]);
        session()->flash('order', $order2);

        app(CartService::class)->deActivateCart();

        return response()->json([
            'success' => true,
            'order'   => new OrderResource($order),
        ]);
    }

    /**
     * Validate order before creation
     *
     * @throws Exception
     */
    public function validateOrder()
    {
        $cart = app(CartService::class)->getCart();

        if (!$cart->billing_address) {
            throw new Exception(trans('Please check billing address.'));
        }

        if (!session()->has('onlyWithoutShipping') || !request()->get('onlyWithoutShipping')) {
            if (!$cart->shipping_address && !$cart->ffl_address) {
                throw new Exception(trans('Please check shipping address.'));
            }

            if (!$cart->selected_shipping_rate && !$cart->selected_ffl_shipping_rate) {
                throw new Exception(trans('Please specify shipping method.'));
            }
        }

        if (!$cart->payment) {
            throw new Exception(trans('Please specify payment method.'));
        }
    }
}
