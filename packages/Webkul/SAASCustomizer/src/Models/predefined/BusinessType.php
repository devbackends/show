<?php

namespace Webkul\SAASCustomizer\Models\Predefined;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

use Webkul\Core\Models\ChannelProxy;

class BusinessType extends Model
{
    use Notifiable;

    protected $table = 'business_type';


    protected $fillable = ['business_type','status'];

}