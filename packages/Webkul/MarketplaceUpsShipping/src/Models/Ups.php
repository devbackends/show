<?php

namespace Webkul\MarketplaceUpsShipping\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\MarketplaceUpsShipping\Contracts\Ups as UpsContract;

class Ups extends Model implements UpsContract
{
    protected $table = 'marketplace_ups_shipping_credentials';

    protected $fillable = ['account_id', 'password', 'ups_seller_id'];

    /**
     * Get the product that belongs to the seller.
     */
    public function credentials()
    {
        return $this->belongsTo(SellerProxy::modelClass(), 'ups_seller_id');
    }

    public $timestamps = false;

}