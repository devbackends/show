<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Marketplace\Contracts\ShipmentItem as ShipmentItemContract;

class ShipmentItem extends Model implements ShipmentItemContract
{
    public $timestamps = false;
    
    protected $table = 'marketplace_shipment_items';

    protected $guarded = ['id', 'child', 'created_at', 'updated_at'];

    /**
     * Get the item that belongs to the item.
     */
    public function item()
    {
        return $this->belongsTo(\Webkul\Sales\Models\ShipmentItemProxy::modelClass(), 'shipment_item_id');
    }
}