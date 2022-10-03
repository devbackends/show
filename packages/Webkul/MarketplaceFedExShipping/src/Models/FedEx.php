<?php

namespace Webkul\MarketplaceFedExShipping\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\MarketplaceFedExShipping\Contracts\FedEx as FedExContract;

class FedEx extends Model implements FedExContract
{
    protected $table = 'marketplace_fedex_shipping_credentials';

    protected $fillable = ['account_id', 'key', 'password', 'meter_number', 'marketplace_seller_id'];

    /**
     * Get the product that belongs to the seller.
     */
    public function credentials()
    {
        return $this->belongsTo(SellerProxy::modelClass(), 'marketplace_seller_id');
    }

    public $timestamps = false;

}