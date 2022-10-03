<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Marketplace\Contracts\MarketplaceCustomerWishlist as MarketplaceCustomerWishlistContract;

/**
 * @property mixed customer_id
 * @property mixed product_id
 * @property mixed marketplace_seller_id
 */
class MarketplaceCustomerWishlist extends Model implements MarketplaceCustomerWishlistContract
{
    protected $table = 'marketplace_customer_wishlist';

    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        $this->timestamps = false;
        parent::__construct($attributes);
    }



}