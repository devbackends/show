<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Marketplace\Contracts\Review as ReviewContract;
use Webkul\Customer\Models\Customer;

class Review extends Model implements ReviewContract
{
    protected $table = 'marketplace_seller_reviews';

    protected $guarded = ['_token'];

    /**
     * Get the seller that belongs to the review.
     */
    public function seller()
    {
        return $this->belongsTo(SellerProxy::modelClass(), 'marketplace_seller_id');
    }

    /**
     * Get the customer that belongs to the review.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}