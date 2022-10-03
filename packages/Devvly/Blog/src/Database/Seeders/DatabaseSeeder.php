<?php


namespace Devvly\Blog\Database\Seeders;


use Devvly\Blog\Models\PostTranslation;
use Devvly\Blog\Models\TagTranslation;
use Devvly\Blog\Repositories\TagRepository;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(){
        $tags = factory(TagTranslation::class, 5)->create();
        factory(PostTranslation::class, 10)->create()->each(function(PostTranslation $item) use($tags){
            $post = $item->post;
            $res = $post->tags()->attach($tags);
        });
    }
}