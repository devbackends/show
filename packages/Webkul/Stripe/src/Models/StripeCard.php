<?php

namespace Webkul\Stripe\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Customer\Models\Customer;
use Webkul\Stripe\Contracts\StripeCard as StripeCardContract;
/**
 * @property string nickname
 * @property int last_four
 * @property string expiration_date
 * @property string stripe_card_id
 * @property string stripe_customer_id
 * @property int customer_id
 * @property int seller_id
 */
class StripeCard extends Model  implements StripeCardContract
{
    protected $table = 'stripe_cards';
    protected $fillable=['nickname','last_four','token','misc','expiration_date','stripe_card_id','stripe_customer_id','customer_id','seller_id'];
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

}