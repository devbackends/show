<?php

namespace Devvly\Blog\Models;

use Devvly\Blog\Contracts\Tag as TagContract;
use Webkul\Core\Eloquent\TranslatableModel;
use Webkul\Core\Models\ChannelProxy;

class Tag extends TranslatableModel implements TagContract
{
    protected $fillable = [];
    public $translatedAttributes = [
        'name',
        'url_key',
    ];
    protected $with = ['translations'];

    /**
     * Get the channels.
     */
    public function channels()
    {
        return $this->belongsToMany(ChannelProxy::modelClass(), 'tag_channels');
    }

    public function posts()
    {
        return $this->morphedByMany(PostProxy::modelClass(), 'taggable');
    }
}
