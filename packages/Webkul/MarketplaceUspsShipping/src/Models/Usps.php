<?php

namespace Webkul\MarketplaceUspsShipping\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\MarketplaceUspsShipping\Contracts\Usps as UspsContract;

class Usps extends Model implements UspsContract
{
    protected $table = 'marketplace_usps_shipping_credentials';

    protected $fillable = ['account_id', 'password', 'usps_seller_id'];

    /**
     * Get the product that belongs to the seller.
     */
    public function credentials()
    {
        return $this->belongsTo(SellerProxy::modelClass(), 'usps_seller_id');
    }

    public $timestamps = false;

}