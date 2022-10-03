<?php

namespace Devvly\ElasticSearch\Listeners;

use Elasticsearch\Client;
use Webkul\Category\Models\Category as CategoryModel;

class Category
{

    /**
     * @var Client
     */
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function onCategoryCreated(CategoryModel $category)
    {
        return;
        if ($category->status == 1) {
            $category->url = implode('/', array_reverse(explode('/', $this->getCategoryUrl($category))));
            if (isset($category->url[0]) && $category->url[0] !== '/') $category->url = '/'.$category->url;
            $category->object = serialize($category);
            $array = $category->toArray();
            unset($array['translations']);
            unset($array['children']);
            $this->elasticsearch->index([
                'index' => $category->getSearchIndex(),
                'type' => $category->getSearchType(),
                'id' => $category->getKey(),
                'body' => $array,
            ]);
        }
    }

    public function onCategoryUpdated(string $categoryId)
    {
        $category = CategoryModel::find($categoryId);
        if ($category) {
            $this->onCategoryCreated($category);
        }
    }

    public function onCategoryDeleted(string $categoryId)
    {
        try {
            $this->elasticsearch->delete([
                'index' => app(CategoryModel::class)->getSearchIndex(),
                'type' => app(CategoryModel::class)->getSearchType(),
                'id' => $categoryId,
            ]);
        } catch(\Exception $e) {}
    }

    protected function getCategoryUrl($category): string
    {
        if ($category->parent) {
            return $category->slug . '/' . $this->getCategoryUrl($category->parent);
        } else {
            return ($category->slug === 'root') ? '' : $category->slug;
        }
    }

}