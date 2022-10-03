<?php

namespace Devvly\Blog\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Devvly\Blog\Models\Post::class,
        \Devvly\Blog\Models\PostTranslation::class,
        \Devvly\Blog\Models\Tag::class,
        \Devvly\Blog\Models\TagTranslation::class,
        \Devvly\Blog\Models\PostCategory::class,
        \Devvly\Blog\Models\PostCategoryTranslation::class,
    ];
}