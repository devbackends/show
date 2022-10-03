<?php

namespace Devvly\ElasticSearch\Repositories;

use Devvly\ElasticSearch\Traits\ElasticsearchRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Webkul\Marketplace\Helpers\Marketplace;
use Webkul\Product\Repositories\ProductFlatRepository as MainRepository;
use Webkul\Product\Models\ProductFlat;
use Elasticsearch\Client;
use Illuminate\Support\Arr;

class ProductFlatRepository extends MainRepository
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

    public function getAllIndexedData(){

        $items = $this->getIndexedData();
        if (!$items['hits']['hits'] || empty($items['hits']['hits'])) return false;

        // Pluck objects into new array
        $products = [];
        foreach (Arr::pluck($items['hits']['hits'], '_source') as $item) {
            $products[] = unserialize($item['object']);
        }
        $products = collect($products);

        // Wrap search result with paginated database data
        return $products;
    }
    /**
     * @param string $term
     * @param array $sortOptions
     * @return false|LengthAwarePaginator
     */
    public function search(array $terms, $sortOptions = [],$fields =  ['name^6', 'sku^2','man_part_num^1','compatible_with^1' ])
    {

        // Search with elastic
        if(!$sortOptions['sortField']){
            if (array_key_exists("sort",$terms) && array_key_exists("order",$terms))
            {
                $sortOptions['sortField']=$terms['sort'];
                $sortOptions['sortOrder']=$terms['order'];

            }
        }

        $items = $this->getElasticsearchItems(
            ProductFlat::class,
            $terms,
            $fields,
            $sortOptions
        );
        if (!$items['hits']['hits'] || empty($items['hits']['hits'])) return false;

        // Pluck objects into new array
        $products = [];
        foreach (Arr::pluck($items['hits']['hits'], '_source') as $item) {
            $products[] = unserialize($item['object']);
        }
        $products = collect($products);

        // Wrap search result with paginated database data
        return (new Marketplace())->paginate($products);
    }

}