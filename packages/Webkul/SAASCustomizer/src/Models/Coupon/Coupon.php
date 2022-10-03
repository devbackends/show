<?php

namespace Webkul\SAASCustomizer\Models\Coupon;


use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Models\ChannelProxy;


class Coupon extends Model
{
    use Notifiable;

    protected $table = 'coupon';

    protected $fillable = [
        'name',
        'description',
        'coupon_code',
        'starts_from',
        'ends_till',
        'status',
        'coupon_type',
        'usage_per_customer',
        'uses_per_coupon',
        'times_used',
        'action_type',
        'discount_amount'
    ];

}