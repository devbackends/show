<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Contracts\Channel as ChannelContract;

class Theme extends Model implements ChannelContract
{
    protected $table = 'themes';

    protected $fillable = [
        'key',
        'name',
    ];
}