<?php

namespace Webkul\SAASCustomizer\Models\Coupon;


use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Models\ChannelProxy;


class CouponType extends Model
{
    use Notifiable;

    protected $table = 'coupon_type';
    
    protected $fillable = [
        'type',
        'description',
        'image',
        'status'
    ];
}