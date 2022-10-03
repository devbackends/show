<?php

namespace Webkul\TableRate\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\TableRate\Contracts\ShippingRate as ShippingRateContract;

class ShippingRate Extends Model implements ShippingRateContract
{
    protected $table = 'tablerate_shipping_rates';

    protected $fillable = [
        'tablerate_superset_id', 'zip_from', 'zip_to', 'country', 'state', 'is_zip_range', 'weight_from', 'weight_to', 'price', 'zip_code'
    ];

    public function superset()
    {
        return $this->belongsTo(SuperSetProxy::modelClass(), 'tablerate_superset_id');
    }
}