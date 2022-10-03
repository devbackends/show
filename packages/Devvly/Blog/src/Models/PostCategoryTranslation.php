<?php

namespace Devvly\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Devvly\Blog\Contracts\PostCategoryTranslation as PostCategoryTranslationContract;

class PostCategoryTranslation extends Model implements PostCategoryTranslationContract
{
    protected $table = 'post_c_translations';
    protected $fillable = [
        'name',
        'locale',
        'url_key',
        'post_category_id',
    ];
    public $timestamps = false;
}
