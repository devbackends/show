<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Devvly\Blog\Models\PostCategoryTranslation;
use Devvly\Blog\Models\PostCategory;
$factory->define(PostCategoryTranslation::class, function (Faker $faker, array $attributes) {
    $name = $faker->unique()->words(2, true);
    return [
        'name' => $name,
        'locale' => 'en',
        'url_key' => str_replace(' ', '-', $name),
        'post_category_id' => factory(PostCategory::class)->create(),
    ];
});