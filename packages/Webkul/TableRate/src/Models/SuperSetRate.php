<?php

namespace Webkul\TableRate\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\TableRate\Contracts\SuperSetRate as SuperSetRateContract;

class SuperSetRate Extends Model implements SuperSetRateContract
{
    protected $table = 'tablerate_superset_rates';

    protected $fillable = [
        'tablerate_superset_id', 'price_from', 'price_to', 'shipping_type', 'price'
    ];

    public function superset()
    {
        return $this->belongsTo(SupersetProxy::modelClass(), 'tablerate_superset_id');
    }
}
