<?php

namespace Webkul\Marketplace\Models;

use Devvly\ElasticSearch\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Webkul\Marketplace\Contracts\Seller as SellerContract;
use Webkul\Customer\Models\Customer;
use Webkul\Marketplace\Service\SellerType;

/**
 * @property mixed type
 */
class Seller extends Model implements SellerContract
{
    use Searchable;

    protected $table = 'marketplace_sellers';

    protected $guarded = ['_token', 'logo', 'banner'];

    /**
     * Get logo image url.
     */
    public function logo_url()
    {
        if (! $this->logo)
            return;

        return Storage::url($this->logo);
    }

    /**
     * Get logo image url.
     */
    public function getLogoUrlAttribute()
    {
        return $this->logo_url();
    }

    /**
     * Get banner image url.
     */
    public function banner_url()
    {
        if (! $this->banner)
            return;

        return Storage::url($this->banner);
    }

    /**
     * Get banner image url.
     */
    public function getBannerUrlAttribute()
    {
        return $this->banner_url();
    }

    /**
     * Get the order products record associated with the seller.
     */
    public function products()
    {
        return $this->hasMany(ProductProxy::modelClass(), 'marketplace_seller_id');
    }

    /**
     * Get the order reviews record associated with the seller.
     */
    public function reviews()
    {
        return $this->hasMany(ReviewProxy::modelClass(), 'marketplace_seller_id');
    }

    /**
     * Get the customer that belongs to the review.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the order orders record associated with the seller.
     */
    public function orders()
    {
        return $this->hasMany(OrderProxy::modelClass(), 'marketplace_seller_id');
    }
}