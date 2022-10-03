<?php

namespace Devvly\Blog\Repositories;

use Devvly\Blog\Models\Post;
use Devvly\Blog\Models\PostTranslation;
use Devvly\Blog\Models\Tag;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webkul\Core\Eloquent\Repository;

class PostRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Devvly\Blog\Contracts\Post';
    }

    /**
     * @param  array  $data
     * @return Post
     */
    public function create(array $data)
    {
        Event::dispatch('blog.posts.create.before');

        $model = $this->getModel();

        foreach (core()->getAllLocales() as $locale) {
            foreach ($model->translatedAttributes as $attribute) {
                if (isset($data[$attribute])) {
                    $data[$locale->code][$attribute] = $data[$attribute];
                }
            }
        }
        $post = parent::create($data);

        $post->save();
        $post->channels()->sync($data['channels']);
        if (isset($data['tags']) && !empty($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

        Event::dispatch('blog.posts.create.after', $post);

        return $post;
    }

    /**
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return Post
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $post = $this->find($id);

        Event::dispatch('blog.posts.update.before', $id);

        parent::update($data, $id, $attribute);

        $post->save();
        $locale = request()->get('locale') ?: app()->getLocale();
        $post->channels()->sync($data['channels']);
        if (isset($data[$locale]['tags']) && !empty($data[$locale]['tags'])) {
            $post->tags()->sync($data[$locale]['tags']);
        }
        else {
            $post->tags()->sync([]);
        }

        Event::dispatch('blog.posts.update.after', $id);

        return $post;
    }

    /**
     * Checks slug is unique or not based on locale
     *
     * @param  int  $id
     * @param  string  $urlKey
     * @return bool
     */
    public function isUrlKeyUnique($id, $urlKey)
    {
        $exists = PostTranslation::where('post_id', '<>', $id)
            ->where('url_key', $urlKey)
            ->limit(1)
            ->select(\DB::raw(1))
            ->exists();

        return $exists ? false : true;
    }

    /**
     * Retrive category from slug
     *
     * @param  string  $urlKey
     * @return Post|\Exception
     */
    public function findByUrlKeyOrFail($urlKey)
    {
        $post = $this->model->whereTranslation('url_key', $urlKey)->first();

        if ($post) {
            return $post;
        }

        throw (new ModelNotFoundException)->setModel(
            get_class($this->model), $urlKey
        );
    }
}