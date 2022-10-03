<?php

namespace Webkul\Checkout\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Checkout\Contracts\CartPayment as CartPaymentContract;

/**
 * Webkul\Checkout\Models\CartPayment
 *
 * @property int $id
 * @property string $method
 * @property string|null $method_title
 * @property int|null $cart_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartPayment whereCartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartPayment whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartPayment whereMethodTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartPayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CartPayment extends Model implements CartPaymentContract
{
    protected $table = 'cart_payment';
}