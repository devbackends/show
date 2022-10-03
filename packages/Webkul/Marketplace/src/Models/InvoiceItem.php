<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Marketplace\Contracts\InvoiceItem as InvoiceItemContract;

class InvoiceItem extends Model implements InvoiceItemContract
{
    public $timestamps = false;
    
    protected $table = 'marketplace_invoice_items';

    protected $guarded = ['id', 'child', 'created_at', 'updated_at'];

    /**
     * Get the item that belongs to the item.
     */
    public function item()
    {
        return $this->belongsTo(\Webkul\Sales\Models\InvoiceItemProxy::modelClass(), 'invoice_item_id');
    }
}