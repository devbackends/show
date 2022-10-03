<?php

namespace Devvly\ElasticSearch\Repositories;

use Devvly\ElasticSearch\Traits\ElasticsearchRepository;
use Elasticsearch\Client;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Webkul\Marketplace\Models\Seller;
use Webkul\Marketplace\Helpers\Marketplace;
use Webkul\Marketplace\Repositories\OrderItemRepository;
use Webkul\Marketplace\Repositories\SellerRepository as MainSellerRepository;


class SellerRepository extends MainSellerRepository
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
        parent::__construct(app(OrderItemRepository::class), $app);
    }

    /**
     * @param string $term
     * @param array $sortOptions
     * @return false|LengthAwarePaginator
     */
    public function search(array $term, $sortOptions = [])
    {
        // Search with elastic
        $items = $this->getElasticsearchItems(Seller::class, $term, ['shop_title^3', 'url^2', 'description'], $sortOptions);
        if (!$items['hits']['hits'] || empty($items['hits']['hits'])) return false;

        // Pluck objects into new array
        $sellers = [];
        foreach (Arr::pluck($items['hits']['hits'], '_source') as $item) {
            $seller = unserialize($item['object']);
            $seller->image_url = $seller->logo_url;
            $seller->shop_url = route('marketplace.seller.show', $seller->url);
            $sellers[] = $seller;
        }

        // Wrap search result with paginated database data
        return (new Marketplace())->paginate(collect($sellers));
    }

}