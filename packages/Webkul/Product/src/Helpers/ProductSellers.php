<?php

namespace Webkul\Product\Helpers;

use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Core\Models\CoreConfig;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Product\Models\ProductFlat;
use Webkul\Marketplace\Repositories\ReviewRepository;

class ProductSellers extends AbstractProduct
{

    protected $productFlatRepository;

    protected $sellerRepository;

    protected $reviewRepository;

    public function __construct(ProductFlatRepository $productFlatRepository,
                                SellerRepository $sellerRepository,
                                ReviewRepository $reviewRepository)
    {
        $this->productFlatRepository = $productFlatRepository;
        $this->sellerRepository = $sellerRepository;
        $this->reviewRepository = $reviewRepository;
    }

    public function getSellersProductsByProduct($mainProduct)
    {
        $products = $this->productFlatRepository->getSellerProducts($mainProduct);
        $products->prepend($mainProduct);

        foreach ($products as $index => $product) {

            if ($product instanceof ProductFlat) {
                $product = $this->setMainProduct($product);
            } else {
                $product = $this->setSellerProduct($product);
            }

            $product->productImages = app('Webkul\Product\Helpers\ProductImage')->getGalleryImages($product);
            if ($product->special_price) {
                $product->price_save = $product->price - $product->special_price;
                $product->price_save = $product->price_save;
                $product->special_price = $product->special_price;
            }
            $product->price = (string)$product->price;
            $product->isSaleable = $product->isSaleable();

            $products[$index] = $product;
        }

        return $products;
    }

    protected function setMainProduct($product)
    {
        $productFlat = $this->productFlatRepository->findWhere([
            'product_id' => $product->product_id
        ])->first();

        $product->seller = app(SellerRepository::class)->find($productFlat->marketplace_seller_id ?? 0);

        if (empty($product->seller->logo)) {
            $product->seller->logo = $this->getDefaultStoreLogo();
        }

        $product->shopUrl = route('shop.home.index');
        $product->review = [
            'average' => app(Review::class)->getAverageRating($product->seller),
            'total' => app(Review::class)->getTotalReviews($product->seller),
        ];
        $product->quantity = $product->product->totalQuantity();

        $condition = [];
        $conditionAttribute = app(AttributeRepository::class)->getAttributeByCode('condition');
        $conditionAttributeValue = $product->product['condition'];
        if ($conditionAttributeValue) {
            foreach ($conditionAttribute->options as $option) {
                if ($conditionAttributeValue == $option->id) {
                    array_push($condition, $option->admin_name);
                }
            }
            if (count($condition) > 0 && strtolower($condition[0]) === 'used') {
                $usedConditionAttribute = app(AttributeRepository::class)->getAttributeByCode('used_condition');
                $usedConditionAttributeValue = $product->product['used_condition'];
                foreach ($usedConditionAttribute->options as $option) {
                    if ($usedConditionAttributeValue == $option->id) {
                        array_push($condition, $option->admin_name);
                    }
                }
            }
        }
        $product->condition = $condition;

        return $product;
    }

    protected function setSellerProduct($product)
    {
        $product->seller = app(SellerRepository::class)->find($product->marketplace_seller_id);
        $product->shopUrl = route('marketplace.seller.show', $product->seller->url);
        $product->review = [
            'average' => app(ReviewRepository::class)->getAverageRating($product->seller),
            'total' => app(ReviewRepository::class)->getTotalRating($product->seller),
        ];
        $product->quantity = $product->product->totalQuantity();
        $product->condition = $this->getConditionForSellerProduct($product);
        return $product;
    }

    protected function getConditionForSellerProduct($product) {
        $conditions = [
            'new' => 'New',
            'used' => 'Used',
            'refurbished' => 'Refurbished'
        ];
        $usedConditions = [
            'like_new' => 'Like New',
            'very_good' => 'Very Good',
            'good' => 'Good',
            'fair' => 'Fair',
            'poor' => 'Poor'
        ];
        $condition = [$conditions[$product->condition]];
        if ($product->used_condition) {
            array_push($condition, $usedConditions[$product->used_condition]);
        }
        return $condition;
    }

    public function getDefaultStoreLogo()
    {
        $logo = CoreConfig::query()
            ->where('code', '=', 'general.design.admin_logo.logo_image')
            ->first();
        return (empty($logo)) ? '' : $logo->value;
    }

}