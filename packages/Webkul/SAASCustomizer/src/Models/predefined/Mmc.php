<?php

namespace Webkul\SAASCustomizer\Models\Predefined;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

use Webkul\Core\Models\ChannelProxy;

class Mmc extends Model
{
    use Notifiable;

    protected $table = 'mmc';


    protected $fillable = ['code','name','status'];

}