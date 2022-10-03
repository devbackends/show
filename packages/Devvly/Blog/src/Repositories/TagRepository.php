<?php

namespace Devvly\Blog\Repositories;

use Devvly\Blog\Models\PostCategory;
use Devvly\Blog\Models\Tag;
use Devvly\Blog\Models\TagTranslation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Event;
use Webkul\Core\Eloquent\Repository;

class TagRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Devvly\Blog\Contracts\Tag';
    }

    /**
     * @param  array  $data
     * @return Tag
     */
    public function create(array $data)
    {
        Event::dispatch('blog.tags.create.before');

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
        $tag = parent::create($data);

        $tag->save();

        $tag->channels()->sync($channels);

        Event::dispatch('blog.tags.create.after', $tag);

        return $tag;
    }

    /**
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return Tag
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $tag = $this->find($id);

        Event::dispatch('blog.tags.update.before', $id);

        parent::update($data, $id, $attribute);

        $tag->save();

        $tag->channels()->sync($data['channels']);

        Event::dispatch('blog.tags.update.after', $id);

        return $tag;
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
        $exists = TagTranslation::where('tag_id', '<>', $id)
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
     * @return Tag|\Exception
     */
    public function findByUrlKeyOrFail($urlKey)
    {
        $tag = $this->model->whereTranslation('url_key', $urlKey)->first();

        if ($tag) {
            return $tag;
        }

        throw (new ModelNotFoundException)->setModel(
            get_class($this->model), $urlKey
        );
    }
}