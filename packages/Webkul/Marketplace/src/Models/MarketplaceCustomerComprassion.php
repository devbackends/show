<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Marketplace\Contracts\MarketplaceCustomerComprassion as MarketplaceCustomerComprassionContract;

/**
 * @property mixed customer_id
 * @property mixed product_id
 * @property mixed marketplace_seller_id
 */
class MarketplaceCustomerComprassion extends Model implements MarketplaceCustomerComprassionContract
{
    protected $table = 'marketplace_customer_comprassion';

    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        $this->timestamps = false;
        parent::__construct($attributes);
    }



}