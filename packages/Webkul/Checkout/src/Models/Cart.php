<?php

namespace Webkul\Checkout\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Webkul\Product\Models\ProductProxy;
use Webkul\Checkout\Contracts\Cart as CartContract;

/**
 * Webkul\Checkout\Models\Cart
 *
 * @property int $id
 * @property string|null $customer_email
 * @property string|null $customer_first_name
 * @property string|null $customer_last_name
 * @property string|null $shipping_method
 * @property string|null $ffl_shipping_method
 * @property string|null $coupon_code
 * @property int $is_gift
 * @property int|null $items_count
 * @property float|null $items_qty
 * @property float|null $exchange_rate
 * @property string|null $global_currency_code
 * @property string|null $base_currency_code
 * @property string|null $channel_currency_code
 * @property string|null $cart_currency_code
 * @property float|null $grand_total
 * @property float|null $base_grand_total
 * @property float|null $sub_total
 * @property float|null $base_sub_total
 * @property float|null $tax_total
 * @property float|null $base_tax_total
 * @property float|null $discount_amount
 * @property float|null $base_discount_amount
 * @property string|null $checkout_method
 * @property int|null $is_guest
 * @property int|null $is_active
 * @property string|null $conversion_time
 * @property int|null $customer_id
 * @property int $channel_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $applied_cart_rule_ids
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webkul\SAASCustomizer\Models\Checkout\CartAddress[] $addresses
 * @property-read int|null $addresses_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webkul\SAASCustomizer\Models\Checkout\CartItem[] $all_items
 * @property-read int|null $all_items_count
 * @property-read mixed $billing_address
 * @property-read mixed $selected_shipping_rate
 * @property-read mixed $per_product_shipping_rate
 * @property-read mixed $selected_ffl_shipping_rate
 * @property-read mixed $per_product_ffl_shipping_rate
 * @property-read mixed $shipping_address
 * @property-read mixed $ffl_address
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webkul\SAASCustomizer\Models\Checkout\CartItem[] $items
 * @property-read \Webkul\SAASCustomizer\Models\Checkout\CartPayment|null $payment
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webkul\SAASCustomizer\Models\Checkout\CartShippingRate[] $shipping_rates
 * @property-read int|null $shipping_rates_count
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereAppliedCartRuleIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereBaseCurrencyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereBaseDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereBaseGrandTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereBaseSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereBaseTaxTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereCartCurrencyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereChannelCurrencyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereCheckoutMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereConversionTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereCouponCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereCustomerEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereCustomerFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereCustomerLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereExchangeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereGlobalCurrencyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereGrandTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereIsGift($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereIsGuest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereItemsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereItemsQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereShippingMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereTaxTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\Cart whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Cart extends Model implements CartContract
{
    protected $table = 'cart';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $with = [
        'items',
        'items.children',
    ];

    /**
     * To get relevant associated items with the cart instance
     */
    public function items()
    {
        return $this->hasMany(CartItemProxy::modelClass())->whereNull('parent_id');
    }

    /**
     * To get all the associated items with the cart instance even the parent and child items of configurable products
     */
    public function all_items()
    {
        return $this->hasMany(CartItemProxy::modelClass());
    }

    /**
     * Get the addresses for the cart.
     */
    public function addresses()
    {
        return $this->hasMany(CartAddressProxy::modelClass());
    }

    public function ffl_address()
    {
        return $this->addresses()->where('address_type', 'ffl_shipping');
    }

    public function getFflAddressAttribute()
    {
        return $this->ffl_address()->first();
    }

    /**
     * Get the biling address for the cart.
     */
    public function billing_address()
    {
        return $this->addresses()->where('address_type', 'billing');
    }

    /**
     * Get billing address for the cart.
     */
    public function getBillingAddressAttribute()
    {
        return $this->billing_address()->first();
    }

    /**
     * Get the shipping address for the cart.
     */
    public function shipping_address()
    {
        return $this->addresses()->where('address_type', 'shipping');
    }

    /**
     * Get shipping address for the cart.
     */
    public function getShippingAddressAttribute()
    {
        return $this->shipping_address()->first();
    }

    /**
     * Get the shipping rates for the cart.
     */
    public function shipping_rates()
    {
        return $this->hasManyThrough(CartShippingRateProxy::modelClass(), CartAddressProxy::modelClass(), 'cart_id', 'cart_address_id');
    }

    public function selected_ffl_shipping_rate()
    {
        return $this->shipping_rates()->where('method', $this->ffl_shipping_method)->whereNotNull('ffl');
    }

    /**
     * Get all of the attributes for the attribute groups.
     */
    public function getSelectedFflShippingRateAttribute()
    {
        return $this->selected_ffl_shipping_rate()
            ->first();
    }

    public function per_product_ffl_shipping_rate()
    {
        return $this->shipping_rates()->where('method', 'ffl_product_shipping_price_total')->whereNotNull('ffl');
    }

    public function getPerProductFflShippingRateAttribute()
    {
        return $this->per_product_ffl_shipping_rate()->first();
    }

    /**
     * Get all of the attributes for the attribute groups.
     */
    public function selected_shipping_rate()
    {
        return $this->shipping_rates()->where('method', $this->shipping_method)->whereNull('ffl');
    }

    /**
     * Get all of the attributes for the attribute groups.
     */
    public function getSelectedShippingRateAttribute()
    {
        return $this->selected_shipping_rate()->where('method', $this->shipping_method)->first();
    }

    public function per_product_shipping_rate()
    {
        return $this->shipping_rates()->where('method', 'product_shipping_price_total')->whereNull('ffl');
    }

    public function getPerProductShippingRateAttribute()
    {
        return $this->per_product_shipping_rate()->first();
    }

    /**
     * Get the payment associated with the cart.
     */
    public function payment()
    {
        return $this->hasOne(CartPaymentProxy::modelClass());
    }

    /**
     * Checks if cart have stockable items
     *
     * @return boolean
     */
    public function haveStockableItems()
    {
        foreach ($this->items as $item) {
            if ($item->product->isStockable()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if cart has downloadable items
     *
     * @return boolean
     */
    public function hasDownloadableItems()
    {
        foreach ($this->items as $item) {
            if (stristr($item->type, 'downloadable') !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns true if cart contains one or many products with quantity box.
     * (for example: simple, configurable, virtual)
     * @return bool
     */
    public function hasProductsWithQuantityBox(): bool
    {
        foreach ($this->items as $item) {
            if ($item->product->getTypeInstance()->showQuantityBox() === true) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks if cart has items that allow guest checkout
     *
     * @return boolean
     */
    public function hasGuestCheckoutItems()
    {
        foreach ($this->items as $item) {
            if ($item->product->getAttribute('guest_checkout') === 0) {
                return false;
            }
        }

        return true;
    }
}
