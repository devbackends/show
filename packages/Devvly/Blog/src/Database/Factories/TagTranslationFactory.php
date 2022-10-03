<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Devvly\Blog\Models\TagTranslation;
use Devvly\Blog\Models\Tag;
$factory->define(TagTranslation::class, function (Faker $faker, array $attributes) {
    $name = $faker->unique()->words(2, true);
    return [
        'name' => $name,
        'locale' => 'en',
        'url_key' => str_replace(' ', '-', $name),
        'tag_id' => factory(Tag::class)->create(),
    ];
});