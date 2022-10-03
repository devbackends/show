<?php

namespace Webkul\TableRate\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\TableRate\Contracts\SuperSet as SuperSetContract;

class SuperSet extends Model implements SuperSetContract
{
    protected $table = 'tablerate_supersets';

    protected $fillable = ['code', 'name', 'status'];

    public function sellerSuperSet()
    {
        return $this->hasMany(SuperSetRateProxy::modelClass() ,'tablerate_superset_id');
    }

    public function shippingRate()
    {
        return $this->hasMany(ShippingRateProxy::modelClass() ,'tablerate_superset_id');
    }
}