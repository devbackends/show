<?php

namespace Devvly\ElasticSearch\Repositories;

use Devvly\ElasticSearch\Traits\ElasticsearchRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Webkul\Category\Models\Category;
use Webkul\Marketplace\Helpers\Marketplace;
use Webkul\Category\Repositories\CategoryRepository as MainRepository;
use Elasticsearch\Client;
use Illuminate\Support\Arr;

class CategoryRepository extends MainRepository
{
    use ElasticsearchRepository;

    /**
     * @var Client
     */
    private $elasticsearch;

    /**
     * ProductFlatRepository constructor.
     * @param Client $elasticsearch
     * @param $app
     */
    public function __construct(Client $elasticsearch, $app)
    {
        $this->elasticsearch = $elasticsearch;

        parent::__construct($app);
    }

    /**
     * @param string $term
     * @param array $sortOptions
     * @return false|LengthAwarePaginator
     */
    public function search(array $terms, $sortOptions = [])
    {

        // Search with elastic
        $items = $this->getElasticsearchItems(Category::class, $terms, ['name^3', 'slug^2', 'description'], $sortOptions);
        if (!$items['hits']['hits'] || empty($items['hits']['hits'])) return false;

        // Pluck objects into new array
        $categories = [];
        foreach (Arr::pluck($items['hits']['hits'], '_source') as $item) {
            $category = unserialize($item['object']);
            $category->image = $category->image_url();
            $categories[] = $category;
        }
        $categories = collect($categories);

        // Wrap search result with paginated database data
        return (new Marketplace())->paginate($categories);
    }

}