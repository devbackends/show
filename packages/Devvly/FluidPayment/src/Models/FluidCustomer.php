<?php

namespace Devvly\FluidPayment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Marketplace\Models\Seller;

/**
 * @property mixed customer_id
 * @property mixed payment_method_id
 * @property mixed subscription_id
 * @property string card_details
 * @property mixed api_key
 * @property mixed public_key
 * @property mixed seller_id
 * @property boolean is_approved
 */
class FluidCustomer extends Model
{
    protected $table = 'fluid_customers';

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

}