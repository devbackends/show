<?php

namespace Webkul\CMS\Repositories;

use HansSchouten\LaravelPageBuilder\Page;
use HansSchouten\LaravelPageBuilder\Repositories\PageRepository;
use HansSchouten\LaravelPageBuilder\Repositories\PageTranslationRepository;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webkul\CMS\Contracts\CmsPage;
use Webkul\Core\Eloquent\Repository;
use Webkul\CMS\Models\CmsPageTranslation;

class CmsRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\CMS\Contracts\CmsPage';
    }

    /**
     * @param  array  $data
     * @return \Webkul\CMS\Contracts\CmsPage
     */
    public function create(array $data)
    {
        Event::dispatch('cms.pages.create.before');

        $model = $this->getModel();

        foreach (core()->getAllLocales() as $locale) {
            foreach ($model->translatedAttributes as $attribute) {
                if (isset($data[$attribute])) {
                    $data[$locale->code][$attribute] = $data[$attribute];
                }
            }
        }

        $page = parent::create($data);

        $page->save();

        $page->channels()->sync($data['channels']);

        Event::dispatch('cms.pages.create.after', $page);

        return $page;
    }

    /**
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\CMS\Contracts\CmsPage
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $page = $this->find($id);

        Event::dispatch('cms.pages.update.before', $id);

        parent::update($data, $id, $attribute);

        $page->save();

        $page->channels()->sync($data['channels']);

        Event::dispatch('cms.pages.update.after', $id);

        return $page;
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
        $exists = CmsPageTranslation::where('cms_page_id', '<>', $id)
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
     * @return \Webkul\CMS\Contracts\CmsPage|\Exception
     */
    public function findByUrlKeyOrFail($urlKey, $pb_page_id = null)
    {
        $page = $this->model->published()->whereTranslation('url_key', $urlKey)->first();

        if ($page) {
            return $page;
        }

        throw (new ModelNotFoundException)->setModel(
            get_class($this->model), $urlKey
        );
    }

    public function duplicate(CmsPage $page){
        $pb_repo = new PageRepository();
        /** @var Page $pb_page */
        $pb_page = $pb_repo->duplicate($page->pb_page_id);
        $new_page_data = $page->toArray();
        $channels = $page->channels->pluck('id')->toArray();
        $title = $this->generateUniqueTitle($new_page_data['page_title']);
        unset($new_page_data['id']);
        $new_page_data['channels'] = $channels;
        $new_page_data['pb_page_id'] = $pb_page->getId();
        $new_page_data['page_title'] = $title;
        $new_page_data['meta_title'] = $this->generateUniqueMetaTitle($new_page_data['meta_title']);
        $new_page_data['url_key'] = $this->generateUniqueUrlKey($new_page_data['url_key']);
        $new_page = $this->create($new_page_data);
        return $new_page;
    }

    /**
     * Generates a unique title for the translation.
     *
     * @param $existingTitle
     * @return string
     */
    protected function generateUniqueTitle($existingTitle) {
        $name = $this->generateUniqueColVal('title', $existingTitle, true);
        return $name;
    }

    /**
     * Generates a unique title for the translation.
     *
     * @param $existingKey
     * @return string
     */
    protected function generateUniqueUrlKey($existingKey) {
        $name = $this->generateUniqueColVal('url-key', $existingKey, true);
        return $name;
    }

    /**
     * Generates a unique title for the translation.
     *
     * @param $metaTitle
     * @return string
     */
    protected function generateUniqueMetaTitle($metaTitle) {
        $name = $this->generateUniqueColVal('meta_title', $metaTitle, true);
        return $name;
    }

    protected function generateUniqueColVal($col, $exitingVal, $space = false){
        $number = 2;
        do {
            $newVal = $exitingVal . '-' . $number;
            if($space){
                $newVal = $exitingVal . ' - ' . $number;
            }
            $page = $this->model->whereTranslation('page_title', $newVal)->get()->first();
            $number++;
        }
        while ( isset($page) );
        return $newVal;
    }
}