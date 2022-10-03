<?php

namespace Webkul\Authorize\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Customer\Models\Customer;
use Webkul\Authorize\Contracts\AuthorizeCard as AuthorizeCardContract;
/**
 * @property string nickname
 * @property int last_four
 * @property string expiration_date
 * @property string stripe_card_id
 * @property string stripe_customer_id
 * @property int customer_id
 * @property int seller_id
 */
class AuthorizeCard extends Model  implements AuthorizeCardContract
{
    protected $table = 'authorize_cards';
    protected $fillable=['nickname','last_four','token','misc','expiration_date','authorize_card_id','authorize_customer_id','authorize_token','is_default','customer_id','seller_id'];
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

}