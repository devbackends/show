<?php

namespace Devvly\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Devvly\Blog\Contracts\PostTranslation as PostTranslationContract;
use Webkul\CMS\Helper\BlockParser;

class PostTranslation extends Model implements PostTranslationContract
{
    protected $fillable = [
        'title',
        'url_key',
        'locale',
        'image',
        'content',
        'pb_page_id'
    ];
    public $timestamps = false;
    /**
     * Get the options.
     */
    public function post()
    {
        return $this->belongsTo(PostProxy::modelClass());
    }

    public function getContentAttribute($value){
        $parser = app(BlockParser::class);
        return $parser->parse($value);
    }
}
