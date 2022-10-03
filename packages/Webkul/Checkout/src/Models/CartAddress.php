<?php

namespace Webkul\Checkout\Models;

use Devvly\Ffl\Models\FflProxy;
use Illuminate\Database\Eloquent\Model;
use Webkul\Checkout\Contracts\CartAddress as CartAddressContract;

/**
 * Webkul\Checkout\Models\CartAddress
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string|null $company_name
 * @property string|null $vat_id
 * @property string $address1
 * @property string|null $address2
 * @property string $country
 * @property string $state
 * @property string $city
 * @property string $postcode
 * @property string $phone
 * @property string $address_type
 * @property int|null $cart_id
 * @property int|null $customer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webkul\SAASCustomizer\Models\Checkout\CartShippingRate[] $shipping_rates
 * @property-read int|null $shipping_rates_count
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartAddress query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartAddress whereAddress1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartAddress whereAddress2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartAddress whereAddressType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartAddress whereCartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartAddress whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartAddress whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartAddress whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartAddress whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartAddress whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartAddress whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartAddress whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartAddress wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartAddress wherePostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartAddress whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartAddress whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webkul\Checkout\Models\CartAddress whereVatId($value)
 * @mixin \Eloquent
 */
class CartAddress extends Model implements CartAddressContract
{
    protected $table = 'cart_address';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'company_name',
        'vat_id',
        'address1',
        'city',
        'state',
        'postcode',
        'country',
        'phone',
        'address_type',
        'cart_id',
        'customer_id',
        'ffl_id',
    ];

    /**
     * Get the shipping rates for the cart address.
     */
    public function shipping_rates()
    {
        return $this->hasMany(CartShippingRateProxy::modelClass());
    }

    public function ffl()
    {
        return $this->belongsTo(FflProxy::class);
    }

    /**
     * Get all of the attributes for the attribute groups.
     */
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}