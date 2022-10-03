<?php

namespace Webkul\Checkout;

use Error;
use Exception;
use Prettus\Validator\Exceptions\ValidatorException;
use Webkul\API\Http\Resources\Checkout\Cart as CartResource;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Checkout\Repositories\CartItemRepository;
use Webkul\Checkout\Repositories\CartAddressRepository;
use Webkul\Customer\Contracts\Customer;
use Webkul\Customer\Contracts\WishlistItem;
use Webkul\MarketplaceFedExShipping\Carriers\MarketplaceFedEx;
use Webkul\MarketplaceUpsShipping\Carriers\MarketplaceUps;
use Webkul\MarketplaceUspsShipping\Carriers\MarketplaceUsps;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\TableRate\Repositories\SuperSetRepository;
use Webkul\Tax\Helpers\Tax;
use Webkul\Tax\Repositories\TaxCategoryRepository;
use Webkul\Checkout\Models\CartItem;
use Webkul\Checkout\Models\CartPayment;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\Customer\Repositories\CustomerAddressRepository;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Arr;

class Cart
{

    /**
     * CartRepository instance
     *
     * @var CartRepository
     */
    protected $cartRepository;

    /**
     * CartItemRepository instance
     *
     * @var CartItemRepository
     */
    protected $cartItemRepository;

    /**
     * CartAddressRepository instance
     *
     * @var CartAddressRepository
     */
    protected $cartAddressRepository;

    /**
     * ProductRepository instance
     *
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * TaxCategoryRepository instance
     *
     * @var TaxCategoryRepository
     */
    protected $taxCategoryRepository;

    /**
     * WishlistRepository instance
     *
     * @var WishlistRepository
     */
    protected $wishlistRepository;

    /**
     * CustomerAddressRepository instance
     *
     * @var CustomerAddressRepository
     */
    protected $customerAddressRepository;

    protected $cart = null;

    /**
     * Create a new class instance.
     *
     * @param CartRepository $cartRepository
     * @param CartItemRepository $cartItemRepository
     * @param CartAddressRepository $cartAddressRepository
     * @param ProductRepository $productRepository
     * @param TaxCategoryRepository $taxCategoryRepository
     * @param WishlistRepository $wishlistRepository
     * @param CustomerAddressRepository $customerAddressRepository
     * @return void
     */
    public function __construct(
        CartRepository $cartRepository,
        CartItemRepository $cartItemRepository,
        CartAddressRepository $cartAddressRepository,
        ProductRepository $productRepository,
        TaxCategoryRepository $taxCategoryRepository,
        WishlistRepository $wishlistRepository,
        CustomerAddressRepository $customerAddressRepository
    )
    {
        $this->cartRepository = $cartRepository;

        $this->cartItemRepository = $cartItemRepository;

        $this->cartAddressRepository = $cartAddressRepository;

        $this->productRepository = $productRepository;

        $this->taxCategoryRepository = $taxCategoryRepository;

        $this->wishlistRepository = $wishlistRepository;

        $this->customerAddressRepository = $customerAddressRepository;
    }

    /**
     * @param int $sellerId
     * @param bool $reload
     * @return Models\Cart|null
     */
    public function getCart(int $sellerId = -1, $reload = false): ?Models\Cart
    {
        if ($this->cart && !$reload) {
            return $this->cart;
        }

        if ($sellerId === -1) return null; // If cart not set and someone want to get it without seller id - return null

        $wheres = [
            'seller_id' => $sellerId,
            'is_active' => 1,
        ];
        if ($this->getCurrentCustomer()->check()) {
            $wheres['customer_id'] = $this->getCurrentCustomer()->user()->id;
        } else {
            if (session()->has('guest_carts')
                && is_array(session()->get('guest_carts'))
                && isset(session()->get('guest_carts')[$sellerId])) {
                $wheres['is_guest'] = 1;
                $wheres['id'] = session()->get('guest_carts')[$sellerId];
            } else {
                return null;
            }
        }

        $this->cart = $this->cartRepository->findOneWhere($wheres);

        return ($this->cart && $this->cart->is_active) ? $this->cart : null;
    }

    /**
     * Create new cart instance.
     *
     * @param int $sellerId
     * @return Contracts\Cart|null
     */
    public function create(int $sellerId): ?Contracts\Cart
    {
        $cartData = [
            'channel_id'            => core()->getCurrentChannel()->id,
            'global_currency_code'  => core()->getBaseCurrencyCode(),
            'base_currency_code'    => core()->getBaseCurrencyCode(),
            'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
            'cart_currency_code'    => core()->getCurrentCurrencyCode(),
            'items_count'           => 1,
            'seller_id'             => $sellerId,
        ];

        // Fill in the customer data, as far as possible:
        if ($this->getCurrentCustomer()->check()) {
            $cartData['customer_id'] = $this->getCurrentCustomer()->user()->id;
            $cartData['is_guest'] = 0;
            $cartData['customer_first_name'] = $this->getCurrentCustomer()->user()->first_name;
            $cartData['customer_last_name'] = $this->getCurrentCustomer()->user()->last_name;
            $cartData['customer_email'] = $this->getCurrentCustomer()->user()->email;
        } else {
            $cartData['is_guest'] = 1;
        }

        $this->cart = $this->cartRepository->create($cartData);

        if (isset($cartData['is_guest']) && $cartData['is_guest']) {
            $carts = is_array(session()->get('guest_carts')) ? session()->get('guest_carts') : [];
            $carts[$sellerId] = $this->cart->id;
            session()->put('guest_carts', $carts);
        }

        if (!$this->cart) {
            session()->flash('error', trans('shop::app.checkout.cart.create-error'));
            return null;
        }

        return $this->cart;
    }

    /**
     * This function handles when guest has some of cart products and then logs in.
     *
     * @return bool
     * @throws Exception
     */
    public function merge(): bool
    {
        if (session()->has('guest_carts')) {
            $guestCarts = session()->get('guest_carts');

            foreach ($guestCarts as $sellerId => $guestCartId) {
                // Try to get customer cart by this seller
                $customerCart = $this->cartRepository->findOneWhere([
                    'customer_id' => $this->getCurrentCustomer()->user()->id,
                    'is_active'   => 1,
                    'seller_id'   => $sellerId
                ]);

                // If there is no customer cart by this seller - just update guest cart to customer
                if (!$customerCart) {
                    $this->cartRepository->update([
                        'customer_id'         => $this->getCurrentCustomer()->user()->id,
                        'is_guest'            => 0,
                        'customer_first_name' => $this->getCurrentCustomer()->user()->first_name,
                        'customer_last_name'  => $this->getCurrentCustomer()->user()->last_name,
                        'customer_email'      => $this->getCurrentCustomer()->user()->email,
                    ], $guestCartId);

                    continue;
                }

                // Get guest cart
                $guestCart = $this->cartRepository->find($guestCartId);

                // If customer cart exist and we need to merge them
                foreach ($guestCart->items as $key => $guestCartItem) {

                    $found = false;
                    foreach ($customerCart->items as $customerCartItem) {
                        $this->cart = $customerCart;
                        if (!$customerCartItem
                            ->product
                            ->getTypeInstance()
                            ->compareOptions($customerCartItem->additional, $guestCartItem->additional)
                        ) {
                            continue;
                        }

                        $customerCartItem->quantity = $newQuantity = $customerCartItem->quantity + $guestCartItem->quantity;

                        if (!$this->isItemHaveQuantity($customerCartItem)) {
                            $this->cartItemRepository->delete($guestCartItem->id);

                            continue;
                        }

                        $this->cartItemRepository->update([
                            'quantity'          => $newQuantity,
                            'total'             => core()->convertPrice($customerCartItem->price * $newQuantity),
                            'base_total'        => $customerCartItem->price * $newQuantity,
                            'total_weight'      => $customerCartItem->weight * $newQuantity,
                            'base_total_weight' => $customerCartItem->weight * $newQuantity,
                        ], $customerCartItem->id);



                        $guestCart->items->forget($key);

                        $this->cartItemRepository->delete($guestCartItem->id);

                        $found = true;

                        // Collect totals
                        $this->collectTotals();
                    }

                    if (!$found) {
                        $this->cartItemRepository->update([
                            'cart_id' => $customerCart->id,
                        ], $guestCartItem->id);

                        foreach ($guestCartItem->children as $child) {
                            $this->cartItemRepository->update([
                                'cart_id' => $customerCart->id,
                            ], $child->id);
                        }
                    }
                }

                try {
                    $this->cartRepository->delete($guestCart->id);
                    //check if guest cart has coupon to apply it on the new cart
                    if($guestCart->coupon_code){
                        try {
                            if (strlen($guestCart->coupon_code)) {
                                app(\Webkul\Checkout\Cart::class)->setCouponCode($guestCart->coupon_code,$guestCart->seller_id)->collectTotals();
                            }

                        } catch (\Exception $e) {

                        }
                    }
                } catch (Error $e) {}
            }

            session()->forget('guest_carts');
        }

        return true;
    }

    protected function reloadCart()
    {
        $cart = $this->getCart();
        if ($cart && $cart instanceof Models\Cart) {
            $cart->shipping_method = null;
            $cart->ffl_address()->delete();
            $cart->shipping_address()->delete();
            $cart->selected_shipping_rate()->delete();
            $cart->selected_ffl_shipping_rate()->delete();
            $cart->payment()->delete();
            $cart->save();
        }
    }

    /**
     * Add Items in a cart with some cart and item details.
     *
     * @param int $productId
     * @param array $data
     * @param int $sellerId
     * @return Contracts\Cart|Exception|array
     * @throws ValidatorException
     * @throws Exception
     */
    public function addProduct(int $productId, int $sellerId, array $data)
    {
        $cart = $this->getCart($sellerId);

        if (!$cart && !$cart = $this->create($sellerId)) {
            return ['warning' => __('shop::app.checkout.cart.item.error-add')];
        }

        $this->reloadCart();

        Event::dispatch('checkout.cart.add.before', $productId);

        $product = $this->productRepository->findOneByField('id', $productId);

        $cartProducts = $product->getTypeInstance()->prepareForCart($data, $sellerId);

        if (is_string($cartProducts)) {
            $this->collectTotals();

            if (count($cart->all_items) <= 0) {
                session()->forget('cart');
            }

            throw new Exception($cartProducts);
        } else {
            $parentCartItem = null;

            foreach ($cartProducts as $cartProduct) {
                $cartItem = $this->getItemByProduct($cartProduct);

                if (isset($cartProduct['parent_id'])) {
                    $cartProduct['parent_id'] = $parentCartItem->id;
                }

                if (!$cartItem) {

                    $cartItem = $this->cartItemRepository->create(array_merge($cartProduct, ['cart_id' => $cart->id]));
                } else {

                    if (isset($cartProduct['parent_id']) && $cartItem->parent_id != $parentCartItem->id) {

                        $cartItem = $this->cartItemRepository->create(array_merge($cartProduct, [
                            'cart_id' => $cart->id,
                        ]));
                    } else {

                        $cartItem = $this->cartItemRepository->update($cartProduct, $cartItem->id);

                    }
                }

                if (!$parentCartItem) {
                    $parentCartItem = $cartItem;
                }
            }

        }

        Event::dispatch('checkout.cart.add.after', $cart);

        $this->collectTotals();

        return $this->cart;
    }

    /**
     * Update cart items information
     *
     * @param int $itemId
     * @param array $data
     * @return bool|Exception
     * @throws Exception
     */
    public function updateItem(int $itemId, array $data)
    {
        $item = $this->cartItemRepository->findOneByField('id', $itemId);

        if (!$item) return false;

        $cart = $this->cartRepository->find($item->cart_id);
        $cart = $this->getCart($cart->seller_id);

        $this->reloadCart();

        if (isset($data['quantity'])) {
            if ($data['quantity'] <= 0) {
                $this->removeItem($cart->seller_id, $itemId);
                throw new Exception(trans('shop::app.checkout.cart.quantity.illegal'));
            }

            $item->quantity = $data['quantity'];

            if (!$this->isItemHaveQuantity($item)) {
                throw new Exception(trans('shop::app.checkout.cart.quantity.inventory_warning'));
            }

            Event::dispatch('checkout.cart.update.before', $item);

            $this->cartItemRepository->update([
                'quantity'          => $data['quantity'],
                'total'             => core()->convertPrice($item->price * $data['quantity']),
                'base_total'        => $item->price * $data['quantity'],
                'total_weight'      => $item->weight * $data['quantity'],
                'base_total_weight' => $item->weight * $data['quantity'],
            ], $itemId);

            Event::dispatch('checkout.cart.update.after', $item);

            $this->collectTotals();
        }

        return true;
    }

    /**
     * Remove the item from the cart
     *
     * @param int $sellerId
     * @param int $itemId
     * @return boolean
     * @throws Exception
     */
    public function removeItem(int $sellerId, int $itemId): bool
    {
        Event::dispatch('checkout.cart.delete.before', $itemId);

        if (!$cart = $this->getCart($sellerId)) {
            return false;
        }

        $this->reloadCart();

        $this->cartItemRepository->delete($itemId);

        if ($cart->items()->get()->count() == 0) {
            $this->cartRepository->delete($cart->id);

            if (session()->has('cart')) {
                session()->forget('cart');
            }
        }

        Event::dispatch('checkout.cart.delete.after', $itemId);

        // Refresh cart
        $this->getCart($sellerId, true);

        $this->collectTotals();

        return true;
    }

    /**
     * Get cart item by product
     *
     * @param array $data
     * @return Contracts\CartItem|void
     */
    public function getItemByProduct(array $data)
    {
        $items = $this->getCart()->all_items;

        foreach ($items as $item) {
            if ($item->product->getTypeInstance()->compareOptions($item->additional, $data['additional'])) {
                if (isset($data['additional']['parent_id'])) {
                    if ($item->parent->product->getTypeInstance()->compareOptions($item->parent->additional, request()->all())) {
                        return $item;
                    }
                } else {
                    return $item;
                }
            }
        }
    }

    /**
     * Return current logged in customer
     *
     * @return Customer|bool
     */
    public function getCurrentCustomer()
    {
        $guard = request()->has('token') ? 'api' : 'customer';

        return auth()->guard($guard);
    }

    /**
     * Returns cart details in array
     *
     * @return array
     * @throws Exception
     */
    public function toArray()
    {
        $cart = $this->getCart();

        $data = $cart->toArray();

        $data['billing_address'] = $cart->billing_address->toArray();

        if ($cart->haveStockableItems()) {
            if ($cart->shipping_address) {
                $data['shipping_address'] = $cart->shipping_address->toArray();
                $data['selected_shipping_rate'] = $cart->selected_shipping_rate ? $cart->selected_shipping_rate->toArray() : 0.0;
                $data['per_product_shipping_rate'] = $cart->per_product_shipping_rate ? $cart->per_product_shipping_rate->toArray() : 0.0;
            } else {
                $data['shipping_address'] = [];
                $data['selected_shipping_rate'] = 0.0;
            }
            if ($cart->ffl_address) {
                $data['ffl_address'] = $cart->ffl_address->toArray();
                $data['selected_ffl_shipping_rate'] = $cart->selected_ffl_shipping_rate ? $cart->selected_ffl_shipping_rate->toArray() : 0.0;
                $data['per_product_ffl_shipping_rate'] = $cart->per_product_ffl_shipping_rate ? $cart->per_product_ffl_shipping_rate->toArray() : 0.0;
            }
        }

        $data['payment'] = $cart->payment->toArray();

        $data['items'] = $cart->items->toArray();

        return $data;
    }

    /**
     * @param $data
     * @return bool
     * @throws ValidatorException
     */
    public function saveCustomerFflShippingAddress($data): bool
    {
        if (!$cart = $this->getCart()) {
            return false;
        }
        $stateId = $data['shipping']['state'];
        $data['shipping']['state'] = core()->states('US')->filter(function ($item) use ($stateId) {
            return $item->id === $stateId;
        })->first()->code;

        $shippingAddressData = $this->gatherShippingAddress($data, $cart);
        if ($cart->haveStockableItems()) {
            $shippingAddressModel = $cart->ffl_address;
        }
        if ($shippingAddressModel) {
            $this->cartAddressRepository->update($shippingAddressData, $shippingAddressModel->id);
        } else {
            $this->cartAddressRepository->create(array_merge($shippingAddressData,
                ['address_type' => 'ffl_shipping']));
        }
        $this->assignCustomerFields($cart);
        $cart->save();
        $this->collectTotals();
        return true;
    }

    /**
     * @param $data
     * @return bool
     * @throws ValidatorException
     */
    public function saveCustomerShippingAddress($data): bool
    {
        if (!$cart = $this->getCart()) {
            return false;
        }

        $shippingAddressData = $this->gatherShippingAddress($data, $cart);
        if ($cart->haveStockableItems()) {
            $shippingAddressModel = $cart->shipping_address;
            if ($shippingAddressModel) {
                $this->cartAddressRepository->update($shippingAddressData, $shippingAddressModel->id);
            } else {
                $this->cartAddressRepository->create(array_merge($shippingAddressData,
                    ['address_type' => 'shipping']));
            }
        }
        $this->assignCustomerFields($cart);
        $cart->save();

        $this->collectTotals();

        return true;
    }

    /**
     * @param $data
     * @return bool
     * @throws ValidatorException
     */
    public function saveCustomerBillingAddress($data): bool
    {
        if (!$cart = $this->getCart()) {
            return false;
        }
        $billingAddressData = $this->gatherBillingAddress($data, $cart);
        $billingAddressModel = $cart->billing_address;
        if ($billingAddressModel) {
            $this->cartAddressRepository->update($billingAddressData, $billingAddressModel->id);
        } else {
            $this->cartAddressRepository->create(array_merge($billingAddressData, ['address_type' => 'billing']));
        }
        $this->assignCustomerFields($cart);
        $cart->save();
        $this->collectTotals();

        return true;
    }

    /**
     * Save customer address
     *
     * @param $data
     *
     * @return bool is the cart valid
     * @throws ValidatorException
     */
    public function saveCustomerAddress($data): bool
    {
        if (!$cart = $this->getCart()) {
            return false;
        }

        $billingAddressData = $this->gatherBillingAddress($data, $cart);

        $shippingAddressData = $this->gatherShippingAddress($data, $cart);

        $this->saveAddressesWhenRequested($data, $billingAddressData, $shippingAddressData);

        $this->linkAddresses($cart, $billingAddressData, $shippingAddressData);

        $this->assignCustomerFields($cart);

        $cart->save();

        $this->collectTotals();

        return true;
    }

    /**
     * Save shipping method for cart
     *
     * @param int $rateId
     * @param array $perProductRates
     * @return Mixed
     */
    public function saveShippingMethod(int $rateId, array $perProductRates = []): bool
    {
        if (! $cart = $this->getCart()) {
            return false;
        }

        $rate = null;

        if (!empty($perProductRates)) {
            $rate = CartShippingRate::query()->where([
                'cart_address_id' => $cart->shipping_address->id,
                'method' => 'product_shipping_price_total'
            ])->first();
            if (!$rate) {
                $rate = new CartShippingRate;
                $rate->carrier = 'product_shipping_price';
                $rate->carrier_title = 'Product Flat Rate';
                $rate->method = 'product_shipping_price_total';
                $rate->method_title = 'Product Flat Rate';
                $rate->method_description = '';
                $rate->cart_address_id = $cart->shipping_address->id;
            }

            $perProductTotalPrice = 0;
            foreach ($perProductRates as $productId => $perProductRateId) {
                $perProductRate = CartShippingRate::query()->find($perProductRateId);
                if (!$perProductRate) return false;
                $perProductTotalPrice += $perProductRate->price;
            }

            $rate->price = $perProductTotalPrice;
            $rate->base_price = $perProductTotalPrice;
            $rate->save();
        }

        if ($rateId > 0) {
            $rate = CartShippingRate::query()->find($rateId);
            if (!$rate) return false;
        }

        // Update cart
        if (!$rate) return false;
        $cart->shipping_method = $rate->method;
        $cart->save();

        return true;
    }

    /**
     * Save shipping ffl method for cart
     *
     * @param int $rateId
     * @param array $perProductRates
     * @return Mixed
     */
    public function saveFflShippingMethod(int $rateId, array $perProductRates = []): bool
    {
        if (! $cart = $this->getCart()) {
            return false;
        }

        $rate = null;

        if (!empty($perProductRates)) {
            $rate = CartShippingRate::query()->where([
                'cart_address_id' => $cart->ffl_address->id,
                'method' => 'ffl_product_shipping_price_total'
            ])->first();
            if (!$rate) {
                $rate = new CartShippingRate;
                $rate->carrier = 'product_shipping_price';
                $rate->carrier_title = 'Product Flat Rate';
                $rate->method = 'ffl_product_shipping_price_total';
                $rate->method_title = 'Product Flat Rate';
                $rate->method_description = '';
                $rate->ffl = 1;
                $rate->cart_address_id = $cart->ffl_address->id;
            }

            $perProductTotalPrice = 0;
            foreach ($perProductRates as $productId => $perProductRateId) {
                $perProductRate = CartShippingRate::query()->find($perProductRateId);
                if (!$perProductRate) return false;
                $perProductTotalPrice += $perProductRate->price;
            }

            $rate->price = $perProductTotalPrice;
            $rate->base_price = $perProductTotalPrice;
            $rate->save();
        }

        if ($rateId > 0) {
            $rate = CartShippingRate::query()->find($rateId);
            if (!$rate) return false;
            $rate->ffl = 1;
            $rate->save();
        }

        // Update cart
        if (!$rate) return false;
        $cart->ffl_shipping_method = $rate->method;
        $cart->save();

        return true;
    }

    /**
     * Save payment method for cart
     *
     * @param $payment
     * @return false|Contracts\CartPayment
     * @throws Exception
     */
    public function savePaymentMethod($payment)
    {
        if (!$cart = $this->getCart()) {
            return false;
        }

        if ($payment['method'] === 'fluid' || $payment['method'] === 'seller-fluid' || $payment['method'] === 'bluedog') {
            $fluidInfo = [
                'type' => '',
                'data' => [],
            ];
            if (!request()->get('additional')
                || !is_array(request()->get('additional'))) return false;
            $additional = request()->get('additional');
            if (isset($additional['token'])) {
                $fluidInfo['type'] = 'token';
                $fluidInfo['data'] = $additional;
            } elseif (isset($additional['card'])) {
                $fluidInfo['type'] = 'card';
                $fluidInfo['data'] = $additional;
            }
            if($payment['method'] === 'bluedog'){
                $fluidInfo['payment'] = 'bluedog';
            }
            $fluidInfo['sellerId'] = $cart->seller_id;
            session()->put('fluid_payment', $fluidInfo);
        } elseif($payment['method'] === 'stripe') {

            $stripeInfo = [
                'type' => '',
                'data' => [],
            ];
            if (!request()->get('additional')
                || !is_array(request()->get('additional'))) return false;
            $additional = request()->get('additional');
            if (isset($additional['token'])) {
                $stripeInfo['type'] = 'token';
                $stripeInfo['data'] = $additional;
            } elseif (isset($additional['card'])) {
                $stripeInfo['type'] = 'card';
                $stripeInfo['data'] = $additional;
            }
            $stripeInfo['sellerId'] = $cart->seller_id;
            session()->put('stripe_payment', $stripeInfo);

        } elseif($payment['method'] === 'authorize') {

            $authorizeInfo = [
                'type' => '',
                'data' => [],
            ];
            if (!request()->get('additional')
                || !is_array(request()->get('additional'))) return false;
            $additional = request()->get('additional');
            if (isset($additional['token'])) {
                $authorizeInfo['type'] = 'token';
                $authorizeInfo['data'] = $additional;
            } elseif (isset($additional['card'])) {
                $authorizeInfo['type'] = 'card';
                $authorizeInfo['data'] = $additional;
            }
            $authorizeInfo['sellerId'] = $cart->seller_id;
            session()->put('authorize_payment', $authorizeInfo);

        }else{
            session()->forget('fluid_payment');
            session()->forget('stripe_payment');
            session()->forget('authorize_payment');
        }

        if ($cartPayment = $cart->payment) {
            $cartPayment->delete();
        }

        $cartPayment = new CartPayment;

        $cartPayment->method = $payment['method'];
        $cartPayment->cart_id = $cart->id;
        $cartPayment->save();

        return $cartPayment;
    }

    /**
     * Updates cart totals
     *
     * @return false
     * @throws Exception
     */
    public function collectTotals()
    {
        $validated = $this->validateItems();

        if (!$validated) {
            return false;
        }

        /** @var Models\Cart $cart */
        if (!$cart = $this->getCart()) {
            return false;
        }

        Event::dispatch('checkout.cart.collect.totals.before', $cart);

        $this->calculateItemsTax();
        $cart->refresh();

        $cart->grand_total = $cart->base_grand_total = 0;
        $cart->sub_total = $cart->base_sub_total = 0;
        $cart->tax_total = $cart->base_tax_total = 0;
        $cart->discount_amount = $cart->base_discount_amount = 0;

        foreach ($cart->items()->get() as $item) {
            $cart->discount_amount += $item->discount_amount;
            $cart->base_discount_amount += $item->base_discount_amount;

            $cart->sub_total = (float)$cart->sub_total + $item->total;
            $cart->base_sub_total = (float)$cart->base_sub_total + $item->base_total;
        }

        $cart->tax_total = Tax::getTaxTotal($cart, false);
        $cart->base_tax_total = Tax::getTaxTotal($cart, true);

        $cart->grand_total = $cart->sub_total + $cart->tax_total - $cart->discount_amount;
        $cart->base_grand_total = $cart->base_sub_total + $cart->base_tax_total - $cart->base_discount_amount;

        $isNeedToAddPerProduct = true;
        if ($shipping = $cart->selected_shipping_rate) {
            if ($shipping->method === 'product_shipping_price_total') $isNeedToAddPerProduct = false;

            $cart->grand_total = (float)$cart->grand_total + $shipping->price - $shipping->discount_amount;
            $cart->base_grand_total = (float)$cart->base_grand_total + $shipping->base_price - $shipping->base_discount_amount;

            $cart->discount_amount += $shipping->discount_amount;
            $cart->base_discount_amount += $shipping->base_discount_amount;
        }

        if ($isNeedToAddPerProduct && $perProductShipping = $cart->per_product_shipping_rate) {
            $cart->grand_total += $perProductShipping->price;
            $cart->base_grand_total += $perProductShipping->price;
        }

        $isNeedToAddPerProductFfl = true;
        if ($shipping = $cart->selected_ffl_shipping_rate) {
            if ($shipping->method === 'ffl_product_shipping_price_total') $isNeedToAddPerProductFfl = false;

            $cart->grand_total = (float)$cart->grand_total + $shipping->price - $shipping->discount_amount;
            $cart->base_grand_total = (float)$cart->base_grand_total + $shipping->base_price - $shipping->base_discount_amount;

            $cart->discount_amount += $shipping->discount_amount;
            $cart->base_discount_amount += $shipping->base_discount_amount;
        }

        if ($isNeedToAddPerProductFfl && $perProductFflShipping = $cart->per_product_ffl_shipping_rate) {
            $cart->grand_total += $perProductFflShipping->price;
            $cart->base_grand_total += $perProductFflShipping->price;
        }

        $quantities = 0;

        foreach ($cart->items as $item) {
            $quantities = $quantities + $item->quantity;
        }

        $cart->items_count = $cart->items->count();

        $cart->items_qty = $quantities;

        $cart->save();

        Event::dispatch('checkout.cart.collect.totals.after', $cart);
    }

    /**
     * To validate if the product information is changed by admin and the items have been added to the cart before it.
     *
     * @return bool
     * @throws Exception
     */
    public function validateItems()
    {
        if (!$cart = $this->getCart()) {
            return;
        }

        if (count($cart->items) == 0) {
            $this->cartRepository->delete($cart->id);

            return false;
        } else {
            foreach ($cart->items()->get() as $item) {

                $response = $item->product->getTypeInstance()->validateCartItem($item, $cart->seller_id);

                if ($response) {
                    return;
                }

                $price = !is_null($item->custom_price) ? $item->custom_price : $item->base_price;

                $this->cartItemRepository->update([
                    'price'      => core()->convertPrice($price),
                    'base_price' => $price,
                    'total'      => core()->convertPrice($price * $item->quantity),
                    'base_total' => $price * $item->quantity,
                ], $item->id);
            }

            return true;
        }
    }

    /**
     * Calculates cart items tax
     *
     * @return void
     */
    public function calculateItemsTax(): void
    {
        if (!$cart = $this->getCart()) {
            return;
        }

        Event::dispatch('checkout.cart.calculate.items.tax.before', $cart);

        foreach ($cart->items()->get() as $item) {
            $taxCategory = $this->taxCategoryRepository->find($item->product->tax_category_id);

            if (!$taxCategory) {
                continue;
            }

            if ($item->product->getTypeInstance()->isStockable()) {
                $address = $cart->shipping_address;
            } else {
                $address = $cart->billing_address;
            }

            if ($address === null && auth()->guard('customer')->check()) {
                $address = auth()->guard('customer')->user()->addresses()
                    ->where('default_address', 1)->first();
            }

            if ($address === null) {
                $address = new class() {
                    public $country;

                    public $postcode;

                    function __construct()
                    {
                        $this->country = strtoupper(config('app.default_country'));
                    }
                };
            }

            $taxRates = $taxCategory->tax_rates()->where([
                'country' => $address->country,
            ])->orderBy('tax_rate', 'desc')->get();

            $item = $this->setItemTaxToZero($item);

            if ($taxRates->count()) {
                foreach ($taxRates as $rate) {
                    $haveTaxRate = false;

                    if ($rate->state != '' && $rate->state != $address->state) {
                        continue;
                    }

                    if (!$rate->is_zip) {
                        if ($rate->zip_code == '*' || $rate->zip_code == $address->postcode) {
                            $haveTaxRate = true;
                        }
                    } else {
                        if ($address->postcode >= $rate->zip_from && $address->postcode <= $rate->zip_to) {
                            $haveTaxRate = true;
                        }
                    }

                    if ($haveTaxRate) {
                        $item->tax_percent = $rate->tax_rate;
                        $item->tax_amount = ($item->total * $rate->tax_rate) / 100;
                        $item->base_tax_amount = ($item->base_total * $rate->tax_rate) / 100;

                        break;
                    }
                }
            }

            $item->save();
        }
        Event::dispatch('checkout.cart.calculate.items.tax.after', $cart);
    }

    /**
     * Set Item tax to zero.
     *
     * @param Contracts\CartItem $item
     * @return Contracts\CartItem
     */
    protected function setItemTaxToZero(CartItem $item): CartItem
    {
        $item->tax_percent = 0;
        $item->tax_amount = 0;
        $item->base_tax_amount = 0;

        return $item;
    }

    /**
     * Checks if cart has any error
     *
     * @param int $sellerId
     * @return bool
     */
    public function hasError($sellerId = -1)
    {
        if (!$this->getCart($sellerId)) {
            return true;
        }

        if (!$this->isItemsHaveSufficientQuantity()) {
            return true;
        }

        if(!$this->isValidCart()){
            return true;
        }

        return false;
    }

    /**
     * Checks if all cart items have sufficient quantity.
     *
     * @return bool
     */
    public function isItemsHaveSufficientQuantity()
    {
        foreach ($this->getCart()->items as $item) {

            if (!$this->isItemHaveQuantity($item)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks if all cart items have sufficient quantity.
     *
     * @param Contracts\CartItem $item
     * @return bool
     */
    public function isItemHaveQuantity($item)
    {
        return $item->product->getTypeInstance()->isItemHaveQuantity($item, $this->cart->seller_id);
    }

    /**
     * Deactivates current cart
     *
     * @return void
     * @throws Exception
     */
    public function deActivateCart()
    {
        if ($cart = $this->getCart()) {
            $this->cartRepository->update(['is_active' => false], $cart->id);

            if (session()->has('cart')) {
                session()->forget('cart');
            }
        }
    }

    /**
     * Validate order before creation
     *
     * @return array
     * @throws Exception
     */
    public function prepareDataForOrder()
    {
        $data = $this->toArray();

        $finalData = [
            'cart_id'               => $this->getCart()->id,
            'customer_id'           => $data['customer_id'],
            'is_guest'              => $data['is_guest'],
            'customer_email'        => $data['customer_email'],
            'customer_first_name'    => $data['customer_first_name'],
            'customer_last_name'    => $data['customer_last_name'],
            'customer'              => $this->getCurrentCustomer()->check() ? $this->getCurrentCustomer()->user() : null,
            'total_item_count'      => $data['items_count'],
            'total_qty_ordered'     => $data['items_qty'],
            'base_currency_code'    => $data['base_currency_code'],
            'channel_currency_code' => $data['channel_currency_code'],
            'order_currency_code'   => $data['cart_currency_code'],
            'grand_total'           => $data['grand_total'],
            'base_grand_total'      => $data['base_grand_total'],
            'sub_total'             => $data['sub_total'],
            'base_sub_total'        => $data['base_sub_total'],
            'tax_amount'            => $data['tax_total'],
            'base_tax_amount'       => $data['base_tax_total'],
            'coupon_code'           => $data['coupon_code'],
            'applied_cart_rule_ids' => $data['applied_cart_rule_ids'],
            'discount_amount'       => $data['discount_amount'],
            'base_discount_amount'  => $data['base_discount_amount'],
            'billing_address'       => Arr::except($data['billing_address'], ['id', 'cart_id']),
            'payment'               => Arr::except($data['payment'], ['id', 'cart_id']),
            'channel'               => core()->getCurrentChannel(),
        ];

        if (session()->has('onlyWithoutShipping') && request()->get('onlyWithoutShipping')) {
            $finalData = array_merge($finalData, [
                'shipping_method'               => '',
                'shipping_title'                => 'No Shipping',
                'shipping_description'          => 'No Shipping',
                'shipping_amount'               => 0,
                'base_shipping_amount'          => 0,
                'shipping_address'              => '',
                'shipping_discount_amount'      => 0,
                'base_shipping_discount_amount' => 0,
            ]);
        } else {
            if ($this->getCart()->haveStockableItems()) {
                $data['selected_shipping_rate'] = $this->getTotalSelectedShippingRateForOrder($data);

                $finalData = array_merge($finalData, [
                    'shipping_method'               => $data['selected_shipping_rate']['method'],
                    'shipping_title'                => $data['selected_shipping_rate']['method_title'],
                    'shipping_description'          => $data['selected_shipping_rate']['method_description'],
                    'shipping_amount'               => $data['selected_shipping_rate']['price'],
                    'base_shipping_amount'          => $data['selected_shipping_rate']['base_price'],
                    'shipping_discount_amount'      => $data['selected_shipping_rate']['discount_amount'],
                    'base_shipping_discount_amount' => $data['selected_shipping_rate']['base_discount_amount'],
                ]);

                if (isset($data['shipping_address']) && !empty($data['shipping_address'])) {
                    $finalData['shipping_address'] = Arr::except($data['shipping_address'], ['id', 'cart_id']);
                }
                if (isset($data['ffl_address']) && !empty($data['ffl_address'])) {
                    $finalData['ffl_address'] = Arr::except($data['ffl_address'], ['id', 'cart_id']);
                }
            }
        }

        foreach ($data['items'] as $item) {
            $finalData['items'][] = $this->prepareDataForOrderItem($item);
        }

        return $finalData;
    }

    /**
     * Prepares data for order item
     *
     * @param array $data
     * @return array
     */
    public function prepareDataForOrderItem($data)
    {
        $finalData = [
            'product'              => $this->productRepository->find($data['product_id']),
            'sku'                  => $data['sku'],
            'type'                 => $data['type'],
            'name'                 => $data['name'],
            'weight'               => $data['weight'],
            'total_weight'         => $data['total_weight'],
            'qty_ordered'          => $data['quantity'],
            'price'                => $data['price'],
            'base_price'           => $data['base_price'],
            'total'                => $data['total'],
            'base_total'           => $data['base_total'],
            'tax_percent'          => $data['tax_percent'],
            'tax_amount'           => $data['tax_amount'],
            'base_tax_amount'      => $data['base_tax_amount'],
            'discount_percent'     => $data['discount_percent'],
            'discount_amount'      => $data['discount_amount'],
            'base_discount_amount' => $data['base_discount_amount'],
            'additional'           => $data['additional'],
        ];

        if (isset($data['children']) && $data['children']) {
            foreach ($data['children'] as $child) {
                $child['quantity'] = $child['quantity'] ? $child['quantity'] * $data['quantity'] : $child['quantity'];

                $finalData['children'][] = $this->prepareDataForOrderItem($child);
            }
        }

        return $finalData;
    }

    /**
     * Move a wishlist item to cart
     *
     * @param WishlistItem $wishlistItem
     * @return bool
     */
    public function moveToCart($wishlistItem)
    {
        if (!$wishlistItem->product->getTypeInstance()->canBeMovedFromWishlistToCart($wishlistItem)) {
            return false;
        }

        if (!$wishlistItem->additional) {
            $wishlistItem->additional = ['product_id' => $wishlistItem->product_id];
        }

        request()->merge($wishlistItem->additional);

        $result = $this->addProduct($wishlistItem->product_id, $wishlistItem->additional);

        if ($result) {
            $this->wishlistRepository->delete($wishlistItem->id);

            return true;
        }

        return false;
    }

    /**
     * Function to move a already added product to wishlist will run only on customer
     * authentication.
     *
     * @param int $itemId
     * @return bool
     */
    public function moveToWishlist($itemId)
    {
        $cart = $this->getCart();

        $cartItem = $cart->items()->find($itemId);

        if (!$cartItem) {
            return false;
        }

        $wishlistItems = $this->wishlistRepository->findWhere([
            'customer_id' => $this->getCurrentCustomer()->user()->id,
            'product_id'  => $cartItem->product_id,
        ]);

        $found = false;

        foreach ($wishlistItems as $wishlistItem) {
            $options = $wishlistItem->item_options;

            if (!$options) {
                $options = ['product_id' => $wishlistItem->product_id];
            }

            if ($cartItem->product->getTypeInstance()->compareOptions($cartItem->additional, $options)) {
                $found = true;
            }
        }

        if (!$found) {
            $this->wishlistRepository->create([
                'channel_id'  => $cart->channel_id,
                'customer_id' => $this->getCurrentCustomer()->user()->id,
                'product_id'  => $cartItem->product_id,
                'additional'  => $cartItem->additional,
            ]);
        }

        $result = $this->cartItemRepository->delete($itemId);

        if (!$cart->items()->count()) {
            $this->cartRepository->delete($cart->id);
        }

        $this->collectTotals();

        return true;
    }

    /**
     * Set coupon code to the cart
     *
     * @param string $code
     * @return Contracts\Cart
     */
    public function setCouponCode($code,$seller_id =0)
    {

        $cart = $this->getCart($seller_id);

        $cart->coupon_code = $code;

        $cart->save();

        return $this;
    }

    /**
     * Remove coupon code from cart
     *
     * @return Contracts\Cart
     */
    public function removeCouponCode()
    {
        $cart = $this->getCart();

        $cart->coupon_code = null;

        $cart->save();

        return $this;
    }

    /**
     * Transfer the user profile information into the cart/into the order.
     *
     * When logged in as guest or the customer profile is not complete, we use the
     * billing address to fill the order customer_ data.
     *
     * @param Models\Cart $cart
     */
    private function assignCustomerFields(Models\Cart $cart): void
    {
        if ($this->getCurrentCustomer()->check()
            && ($user = $this->getCurrentCustomer()->user())
            && $this->profileIsComplete($user)
        ) {
            $cart->customer_email = $user->email;
            $cart->customer_first_name = $user->first_name;
            $cart->customer_last_name = $user->last_name;
        } else {
            $cart->customer_email = $cart->billing_address->email;
            $cart->customer_first_name = $cart->billing_address->first_name;
            $cart->customer_last_name = $cart->billing_address->last_name;
        }
    }

    /**
     * @param $user
     *
     * @return bool
     */
    private function profileIsComplete($user): bool
    {
        return $user->email && $user->first_name && $user->last_name;
    }

    /**
     * @return array
     */
    private function fillCustomerAttributes(): array
    {
        $attributes = [];

        $user = $this->getCurrentCustomer()->user();

        if ($user) {
            $attributes['first_name'] = $user->first_name;
            $attributes['last_name'] = $user->last_name;
            $attributes['email'] = $user->email;
            $attributes['customer_id'] = $user->id;
        }

        return $attributes;
    }

    /**
     * @return array
     */
    private function fillAddressAttributes(array $addressAttributes): array
    {
        $attributes = [];

        $cartAddress = new CartAddress();

        foreach ($cartAddress->getFillable() as $attribute) {
            if (isset($addressAttributes[$attribute])) {
                $attributes[$attribute] = $addressAttributes[$attribute];
            }
        }

        return $attributes;
    }

    /**
     * @param array $data
     * @param array $billingAddress
     * @param array $shippingAddress
     */
    private function saveAddressesWhenRequested(
        array $data,
        array $billingAddress,
        array $shippingAddress
    ): void
    {
        if (isset($data['billing']['save_as_address']) && $data['billing']['save_as_address']) {
            $this->customerAddressRepository->create($billingAddress);
        }

        if (isset($data['shipping']['save_as_address']) && $data['shipping']['save_as_address']) {
            $this->customerAddressRepository->create($shippingAddress);
        }
    }

    /**
     * @param $data
     * @param $cart
     *
     * @return array
     */
    private function gatherBillingAddress($data, Models\Cart $cart): array
    {
        $customerAddress = [];

        if (isset($data['billing']['address_id']) && $data['billing']['address_id']) {
            $customerAddress = $this
                ->customerAddressRepository
                ->findOneWhere(['id' => $data['billing']['address_id']])
                ->toArray();
        }

        $billingAddress = array_merge(
            $customerAddress,
            $data['billing'],
            ['cart_id' => $cart->id],
            $this->fillCustomerAttributes(),
            $this->fillAddressAttributes($data['billing'])
        );


        return $billingAddress;
    }

    /**
     * @param $data
     * @param Cart|null $cart
     *
     * @return array
     */
    private function gatherShippingAddress($data, Models\Cart $cart): array
    {
        $customerAddress = [];

        if (isset($data['shipping']['address_id']) && $data['shipping']['address_id']) {
            $customerAddress = $this
                ->customerAddressRepository
                ->findOneWhere(['id' => $data['shipping']['address_id']])
                ->toArray();
        }

        $shippingAddress = array_merge(
            $customerAddress,
            $data['shipping'],
            ['cart_id' => $cart->id],
            $this->fillCustomerAttributes(),
            $this->fillAddressAttributes($data['shipping'])
        );

        return $shippingAddress;
    }

    /**
     * @param Cart|null $cart
     * @param array $billingAddressData
     * @param array $shippingAddressData
     *
     * @throws ValidatorException
     */
    private function linkAddresses(
        Models\Cart $cart,
        array $billingAddressData,
        array $shippingAddressData
    ): void
    {
        $billingAddressModel = $cart->billing_address;
        if ($billingAddressModel) {
            $this->cartAddressRepository->update($billingAddressData, $billingAddressModel->id);

            if ($cart->haveStockableItems()) {
                $shippingAddressModel = $cart->shipping_address;
                if ($shippingAddressModel) {
                    if (isset($billingAddressData['use_for_shipping']) && $billingAddressData['use_for_shipping']) {
                        $this->cartAddressRepository->update($billingAddressData, $shippingAddressModel->id);
                    } else {
                        $this->cartAddressRepository->update($shippingAddressData, $shippingAddressModel->id);
                    }
                } else {
                    if (isset($billingAddressData['use_for_shipping']) && $billingAddressData['use_for_shipping']) {
                        $this->cartAddressRepository->create(array_merge($billingAddressData,
                            ['address_type' => 'shipping']));
                    } else {
                        $this->cartAddressRepository->create(array_merge($shippingAddressData,
                            ['address_type' => 'shipping']));
                    }
                }
            }
        } else {
            $this->cartAddressRepository->create(array_merge($billingAddressData, ['address_type' => 'billing']));

            if ($cart->haveStockableItems()) {
                if (isset($billingAddressData['use_for_shipping']) && $billingAddressData['use_for_shipping']) {
                    $this->cartAddressRepository->create(array_merge($billingAddressData, ['address_type' => 'shipping']));
                } else {
                    $this->cartAddressRepository->create(array_merge($shippingAddressData, ['address_type' => 'shipping']));
                }
            }
        }
    }

    /**
     * @param array $data
     * @return array
     */
    protected function getTotalSelectedShippingRateForOrder(array $data): array
    {
        $price = 0;

        if ($data['selected_shipping_rate'] !== 0.0) {
            $price += $data['selected_shipping_rate']['price'];
            if (isset($data['per_product_shipping_rate']) && $data['per_product_shipping_rate'] !== 0.0
                && $data['selected_shipping_rate']['method'] !== 'product_shipping_price_total') {
                $price += $data['per_product_shipping_rate']['price'];
            }
        }

        if (isset($data['selected_ffl_shipping_rate'])) {
            $price += $data['selected_ffl_shipping_rate']['price'];
            if (isset($data['per_product_ffl_shipping_rate']) && $data['per_product_ffl_shipping_rate'] !== 0.0
                && $data['selected_ffl_shipping_rate']['method'] !== 'ffl_product_shipping_price_total') {
                $price += $data['per_product_ffl_shipping_rate']['price'];
            }
        }

        if ($data['selected_shipping_rate'] === 0.0 && isset($data['selected_ffl_shipping_rate'])) {
            $data['selected_shipping_rate'] = $data['selected_ffl_shipping_rate'];
        }

        $data['selected_shipping_rate']['price'] = $price;
        $data['selected_shipping_rate']['base_price'] = $price;

        return $data['selected_shipping_rate'];
    }
    public function isValidCart(){
        $cart=$this->getCart();
        foreach ($cart->items()->get() as $item ){
            if($item->type=='booking'){
                $isValidBookingProductCartItem=app('Webkul\BookingProduct\Type\Booking')->isValidBookingProductCartItem($item);
                if(!$isValidBookingProductCartItem){
                    return false;
                }
            }
        }
        return true;
    }
    public function addCustomerToCart(){
        $data=request()->all();
        if(isset($data['cart']) && isset($data['customer'])){
            $cart = $this->cartRepository->find($data['cart']['id']);
            // If there is no customer cart by this seller - just update guest cart to customer
            if ($cart) {
                $this->cartRepository->update([
                    'customer_id'         => $data['customer']['id'],
                    'is_guest'            => 0,
                    'customer_first_name' => $data['customer']['first_name'],
                    'customer_last_name'  => $data['customer']['last_name'],
                    'customer_email'      => $data['customer']['email'],
                ], $cart['id']);
                return response()->json([
                        'status' => 'success',
                        'code'  => 200,
                        'message' => 'cart updated successfully'
                ],200);
            }
        }
        return response()->json([
            'status' => 'fail',
            'code'  => 500,
            'message' => 'cart not updated'
        ],200);
    }
}
