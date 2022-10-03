<?php

namespace Webkul\Checkout\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\ProductProxy;
use Webkul\Product\Models\ProductFlatProxy;
use Webkul\Checkout\Contracts\CartItem as CartItemContract;


/**
 * Webkul\Checkout\Models\CartItem
 *
 * @property int $id
 * @property int $quantity
 * @property string|null $sku
 * @property string|null $type
 * @property string|null $name
 * @property string|null $coupon_code
 * @property float $weight
 * @property float $total_weight
 * @property float $base_total_weight
 * @property float $price
 * @property float $base_price
 * @property float $total
 * @property float $base_total
 * @property float|null $tax_percent
 * @property float|null $tax_amount
 * @property float|null $base_tax_amount
 * @property float $discount_percent
 * @property float $discount_amount
 * @property float $base_discount_amount
 * @property array|null $additional
 * @property int|null $parent_id
 * @property int $product_id
 * @property int $cart_id
 * @property int|null $tax_category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property float|null $custom_price
 * @property string|null $applied_cart_rule_ids
 * @property-read \Webkul\SAASCustomizer\Models\Checkout\Cart|null $cart
 * @property-read \Webkul\Checkout\Models\CartItem $child
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webkul\Checkout\Models\CartItem[] $children
 * @property-read int|null $children_count
 * @property-read mixed $product_flat
 * @property-read \Webkul\Checkout\Models\CartItem|null $parent
 * @property-read \Webkul\SAASCustomizer\Models\Product\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem whereAdditional($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem whereAppliedCartRuleIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem whereBaseDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem whereBasePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem whereBaseTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem whereBaseTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem whereBaseTotalWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem whereCartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem whereCouponCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem whereCustomPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem whereDiscountPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem whereTaxCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem whereTaxPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem whereTotalWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartItem whereWeight($value)
 * @mixin \Eloquent
 */
class CartItem extends Model implements CartItemContract
{
    protected $table = 'cart_items';

    protected $casts = [
        'additional' => 'array',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function product()
    {
        return $this->hasOne(ProductProxy::modelClass(), 'id', 'product_id');
    }

    /**
     * The Product Flat that belong to the product.
     */
    public function product_flat()
    {
        return (ProductFlatProxy::modelClass())
            ::where('product_flat.product_id', $this->product_id)
            ->where('product_flat.locale', app()->getLocale())
            ->where(function ($query) {
                $query->where('product_flat.channel', core()->getCurrentChannelCode());
                if (core()->getCurrentChannelCode() == config('app.defaultChannel')) {
                    $query->orWhere('product_flat.show_on_marketplace', '1');
                }
            })->select('product_flat.*');
    }

    /**
     * Get all of the attributes for the attribute groups.
     */
    public function getProductFlatAttribute()
    {
        return $this->product_flat()->first();
    }

    public function cart()
    {
        return $this->hasOne(CartProxy::modelClass(), 'id', 'cart_id');
    }

    /**
     * Get the child item.
     */
    public function child()
    {
        return $this->belongsTo(static::class, 'id', 'parent_id');
    }

    /**
     * Get the parent item record associated with the cart item.
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get the children items.
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
