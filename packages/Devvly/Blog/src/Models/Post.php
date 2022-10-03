<?php

namespace Devvly\Blog\Models;

use Webkul\Core\Eloquent\TranslatableModel;
use Devvly\Blog\Contracts\Post as PostContract;

use Webkul\Core\Models\ChannelProxy;

class Post extends TranslatableModel implements PostContract
{

    protected $fillable = ['post_category_id', 'created_at', 'published'];
    protected $casts = [
        'published' => 'boolean',
    ];
    public $translatedAttributes = [
        'title',
        'url_key',
        'locale',
        'image',
        'content',
        'pb_page_id',
    ];

    protected $with = ['translations'];

    /**
     * Get the channels.
     */
    public function channels()
    {
        return $this->belongsToMany(ChannelProxy::modelClass(), 'post_channels');
    }

    /**
     * Get the options.
     */
    public function postCategory()
    {
        return $this->belongsTo(PostCategoryProxy::modelClass());
    }

    /**
     * Get the options.
     */
    public function tags()
    {
        return $this->morphToMany(TagProxy::modelClass(), 'taggable');
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('published', 1);
    }
}
