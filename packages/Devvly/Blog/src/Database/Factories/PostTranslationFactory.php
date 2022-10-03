<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Devvly\Blog\Models\Post;
use Devvly\Blog\Models\PostTranslation;
use Devvly\Blog\Models\PostCategory;
$factory->define(PostTranslation::class, function (Faker $faker, array $attributes) {
    $title = $faker->words(3, true);
    $content = $faker->paragraphs($faker->randomNumber(2), true);
    $hasImage = $faker->boolean(50);
    return [
        'title' => $title,
        'url_key' => str_replace(' ', '-', $title),
        'locale' => 'en',
        'image' => null,
        'image_link' => "https://via.placeholder.com/1000x460",
        'teaser' => $content,
        'content' => $content,
        'post_id' => factory(Post::class)->create(),
    ];
});