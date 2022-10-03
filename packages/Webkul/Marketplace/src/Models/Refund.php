<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Marketplace\Contracts\Refund as RefundContract;

/**
 * Seller Refund
 *
 * @author    Naresh Verma <naresh.verma327@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Refund extends Model implements RefundContract
{
    protected $table = 'marketplace_refunds';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Get the Refund.
     */
    public function refund()
    {
        return $this->belongsTo(\Webkul\Sales\Models\RefundProxy::modelClass(), 'refund_id');
    }

    /**
     * Get the Refund items record.
     */
    public function items()
    {
        return $this->hasMany(RefundItemProxy::modelClass(), 'marketplace_refund_id');
    }

    /**
     * Get the order that belongs to the refund.
     */
    public function order()
    {
        return $this->belongsTo(OrderProxy::modelClass(), 'marketplace_order_id');
    }
}