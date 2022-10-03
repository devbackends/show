<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Marketplace\Contracts\Transaction as TransactionContract;

class Transaction extends Model implements TransactionContract
{
    protected $table = 'marketplace_transactions';

    protected $fillable = ['type', 'transaction_id', 'method', 'comment', 'total', 'base_total', 'marketplace_seller_id', 'marketplace_order_id'];

    /**
     * The orders that belong to the transaction.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'marketplace_order_id');
    }
}