<?php

namespace Webkul\SAASCustomizer\Models\Coupon;


use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Models\ChannelProxy;


class CouponUsage extends Model
{
    use Notifiable;

    protected $table = 'coupon_usage';

    protected $fillable = [
        'times_used',
        'coupon_id',
    ];
}