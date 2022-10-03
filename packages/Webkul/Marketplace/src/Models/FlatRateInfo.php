<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Marketplace\Contracts\FlatRateInfo as FlatRateInfoContract;

class FlatRateInfo extends Model implements FlatRateInfoContract
{
    protected $table = 'marketplace_flatrate_shipping_info';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Get the seller that belongs to the row.
     */
    public function seller()
    {
        return $this->belongsTo(\Webkul\Marketplace\Models\SellerProxy::modelClass(), 'seller_id');
    }

}