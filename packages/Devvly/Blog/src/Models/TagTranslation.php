<?php

namespace Devvly\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Devvly\Blog\Contracts\TagTranslation as TagTranslationContract;

class TagTranslation extends Model implements TagTranslationContract
{
    protected $fillable = ['name', 'url_key'];
    public $timestamps = false;

    public function tag(){
        return $this->belongsTo(TagProxy::modelClass());
    }
}
