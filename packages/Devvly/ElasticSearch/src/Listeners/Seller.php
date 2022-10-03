<?php

namespace Devvly\ElasticSearch\Listeners;

use Elasticsearch\Client;
use Webkul\Marketplace\Models\Seller as SellerModel;

class Seller
{

    /**
     * @var Client
     */
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function onSellerSaved(SellerModel $seller)
    {
        $seller = SellerModel::find($seller->id);
        if ($seller->is_approved == 1) {
            $seller->object = serialize($seller);
            $this->elasticsearch->index([
                'index' => $seller->getSearchIndex(),
                'type' => $seller->getSearchType(),
                'id' => $seller->getKey(),
                'body' => $seller->toArray(),
            ]);
        }
    }

    public function onSellerDeleted(string $sellerId)
    {
        try {
            $this->elasticsearch->delete([
                'index' => app(SellerModel::class)->getSearchIndex(),
                'type' => app(SellerModel::class)->getSearchType(),
                'id' => $sellerId,
            ]);
        } catch(\Exception $e) {}
    }

}