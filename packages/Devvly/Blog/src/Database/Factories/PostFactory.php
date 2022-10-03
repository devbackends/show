<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Devvly\Blog\Models\Post;
use Devvly\Blog\Models\PostCategoryTranslation;
$factory->define(Post::class, function (Faker $faker, array $attributes) {
    return [
        'published' => 1,
        'post_category_id' => factory(PostCategoryTranslation::class)->create(),
    ];
});