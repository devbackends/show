<?php

namespace Devvly\FluidPayment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Customer\Models\Customer;

/**
 * @property string nickname
 * @property int last_four
 * @property string expiration_date
 * @property string fluid_card_id
 * @property string fluid_customer_id
 * @property int customer_id
 * @property int seller_id
 */
class FluidCard extends Model
{
    protected $table = 'fluid_cards';

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

}