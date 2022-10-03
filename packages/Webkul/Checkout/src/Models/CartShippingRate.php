<?php

namespace Webkul\Checkout\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Checkout\Contracts\CartShippingRate as CartShippingRateContract;

/**
 * Webkul\Checkout\Models\CartShippingRate
 *
 * @property int $id
 * @property string $carrier
 * @property string $carrier_title
 * @property string $method
 * @property string $method_title
 * @property string|null $method_description
 * @property float|null $price
 * @property float|null $base_price
 * @property int|null $cart_address_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property float $discount_amount
 * @property float $base_discount_amount
 * @property bool|null $ffl
 * @property-read \Webkul\SAASCustomizer\Models\Checkout\CartAddress $shipping_address
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartShippingRate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartShippingRate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartShippingRate query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartShippingRate whereBaseDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartShippingRate whereBasePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartShippingRate whereCarrier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartShippingRate whereCarrierTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartShippingRate whereCartAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartShippingRate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartShippingRate whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartShippingRate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartShippingRate whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartShippingRate whereMethodDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartShippingRate whereMethodTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartShippingRate wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartShippingRate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CartShippingRate extends Model implements CartShippingRateContract
{
    protected $fillable = [
        'carrier',
        'carrier_title',
        'method',
        'method_title',
        'method_description',
        'price',
        'base_price',
        'discount_amount',
        'base_discount_amount',
        'ffl',
    ];

    /**
     * Get the post that owns the comment.
     */
    public function shipping_address()
    {
        return $this->belongsTo(CartAddressProxy::modelClass());
    }
}