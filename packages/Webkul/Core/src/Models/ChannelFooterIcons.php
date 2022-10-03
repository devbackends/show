<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelFooterIcons extends Model
{
    protected $fillable = [
        'icon',
        'url',
        'channel_id'
    ];
    public function channel()
    {
        return $this->belongsTo( Channel::class);
    }
}