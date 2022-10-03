<?php

namespace Devvly\Blog\Models;

use Webkul\Core\Eloquent\TranslatableModel;
use Devvly\Blog\Contracts\PostCategory as PostCategoryContract;
use Webkul\Core\Models\ChannelProxy;

class PostCategory extends TranslatableModel implements PostCategoryContract
{

    protected $fillable = [];

    public $translatedAttributes = [
        'name',
        'locale',
        'url_key',
        'content',
    ];

    protected $with = ['translations'];

    /**
     * Get the channels.
     */
    public function channels()
    {
        return $this->belongsToMany(ChannelProxy::modelClass(), 'post_category_channels');
    }

    public function posts()
    {
        return $this->hasMany(PostProxy::modelClass());
    }
}
