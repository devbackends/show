<?php

namespace Devvly\Blog\Repositories;

use Devvly\Blog\Models\PostCategory;
use Devvly\Blog\Models\PostCategoryTranslation;
use Devvly\Blog\Models\Tag;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Event;
use Webkul\Core\Eloquent\Repository;

class PostCategoryRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Devvly\Blog\Contracts\PostCategory';
    }

    /**
     * @param  array  $data
     * @return Tag
     */
    public function create(array $data)
    {
        Event::dispatch('blog.post_category.create.before');

        $model = $this->getModel();

        foreach (core()->getAllLocales() as $locale) {
            foreach ($model->translatedAttributes as $attribute) {
                if (isset($data[$attribute])) {
                    $data[$locale->code][$attribute] = $data[$attribute];
                }
            }
        }

        $channels = $data['channels'];
        unset($data['channels']);
        $category = parent::create($data);

        $category->save();
        $category->channels()->sync($channels);

        Event::dispatch('blog.post_category.create.after', $category);

        return $category;
    }

    /**
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return Tag
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $category = $this->find($id);

        Event::dispatch('blog.post_category.update.before', $id);

        parent::update($data, $id, $attribute);

        $category->save();

        $category->channels()->sync($data['channels']);

        Event::dispatch('blog.post_category.update.after', $id);

        return $category;
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
        $exists = PostCategoryTranslation::where('post_category_id', '<>', $id)
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
     * @return PostCategory|\Exception
     */
    public function findByUrlKeyOrFail($urlKey)
    {
        $category = $this->model->whereTranslation('url_key', $urlKey)->first();

        if ($category) {
            return $category;
        }

        throw (new ModelNotFoundException)->setModel(
            get_class($this->model), $urlKey
        );
    }
}