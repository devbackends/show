<?php

namespace Webkul\Sales\Models;

use Webkul\Checkout\Models\CartProxy;
use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Contracts\Order as OrderContract;

class Order extends Model implements OrderContract
{
    protected $guarded = [
        'id',
        'items',
        'shipping_address',
        'billing_address',
        'customer',
        'channel',
        'payment',
        'created_at',
        'updated_at',
    ];

    protected $statusLabel = [
        'pending'         => 'Pending',
        'pending_payment' => 'Pending Payment',
        'processing'      => 'Processing',
        'completed'       => 'Completed',
        'canceled'        => 'Canceled',
        'closed'          => 'Closed',
        'fraud'           => 'Fraud',
    ];

    /**
     * Get the order items record associated with the order.
     */
    public function getCustomerFullNameAttribute()
    {
        return $this->customer_first_name . ' ' . $this->customer_last_name;
    }

    /**
     * Returns the status label from status code
     */
    public function getStatusLabelAttribute()
    {
        return $this->statusLabel[$this->status];
    }

    /**
     * Return base total due amount
     */
    public function getBaseTotalDueAttribute()
    {
        return $this->base_grand_total - $this->base_grand_total_invoiced;
    }

    /**
     * Return total due amount
     */
    public function getTotalDueAttribute()
    {
        return $this->grand_total - $this->grand_total_invoiced;
    }

    /**
     * Get the associated cart that was used to create this order.
     */
    public function cart()
    {
        return $this->belongsTo(CartProxy::modelClass());
    }


    /**
     * Get the order items record associated with the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItemProxy::modelClass())->whereNull('parent_id');
    }

    /**
     * Get the order items record associated with the order.
     */
    public function all_items()
    {
        return $this->hasMany(OrderItemProxy::modelClass());
    }

    /**
     * Get the order shipments record associated with the order.
     */
    public function shipments()
    {
        return $this->hasMany(ShipmentProxy::modelClass());
    }

    /**
     * Get the order invoices record associated with the order.
     */
    public function invoices()
    {
        return $this->hasMany(InvoiceProxy::modelClass());
    }

    /**
     * Get the order refunds record associated with the order.
     */
    public function refunds()
    {
        return $this->hasMany(RefundProxy::modelClass());
    }

    /**
     * Get the customer record associated with the order.
     */
    public function customer()
    {
        return $this->morphTo();
    }

    /**
     * Get the addresses for the order.
     */
    public function addresses()
    {
        return $this->hasMany(OrderAddressProxy::modelClass());
    }

    /**
     * Get the payment for the order.
     */
    public function payment()
    {
        return $this->hasOne(OrderPaymentProxy::modelClass());
    }

    /**
     * Get the biling address for the order.
     */
    public function billing_address()
    {
        return $this->addresses()->where('address_type', 'billing');
    }

    /**
     * Get billing address for the order.
     */
    public function getBillingAddressAttribute()
    {
        return $this->billing_address()->first();
    }

    /**
     * Get the shipping address for the order.
     */
    public function shipping_address()
    {
        return $this->addresses()->where('address_type', 'shipping');
    }

    /**
     * Get shipping address for the order.
     */
    public function getShippingAddressAttribute()
    {
        return $this->shipping_address()->first();
    }

    public function ffl_address()
    {
        return $this->addresses()->where('address_type', 'ffl_shipping');
    }

    public function getFflAddressAttribute()
    {
        return $this->ffl_address()->first();
    }

    /**
     * Get the channel record associated with the order.
     */
    public function channel()
    {
        return $this->morphTo();
    }

    /**
     * Checks if cart have stockable items
     *
     * @return boolean
     */
    public function haveStockableItems()
    {
        foreach ($this->items as $item) {
            if ($item->getTypeInstance()->isStockable()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if new shipment is allow or not
     */
    public function canShip()
    {
        if ($this->status == 'fraud') {
            return false;
        }

        foreach ($this->items as $item) {
            if ($item->canShip()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if new invoice is allow or not
     */
    public function canInvoice()
    {
        if ($this->status == 'fraud') {
            return false;
        }

        foreach ($this->items as $item) {
            if ($item->canInvoice()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if order can be canceled or not
     */
    public function canCancel()
    {
        if ($this->status == 'fraud') {
            return false;
        }

        foreach ($this->items as $item) {
            if ($item->canCancel()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if order can be refunded or not
     */
    public function canRefund()
    {
        if ($this->status == 'fraud') {
            return false;
        }

        foreach ($this->items as $item) {
            if ($item->qty_to_refund > 0) {
                return true;
            }
        }

        if ($this->base_grand_total_invoiced - $this->base_grand_total_refunded - $this->refunds()->sum('base_adjustment_fee') > 0) {
            return true;
        }

        return false;
    }
}