<?php

namespace Webkul\Marketplace\Helpers;

use Illuminate\Container\Container;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Marketplace\Models\MarketplaceCustomerWishlist;


class Marketplace
{
    const PAGINATION_PAGE_SIZE = 24;

    public function paginate($results)
    {
        $page = Paginator::resolveCurrentPage();
        $limit = request()->has('limit') ? request()->get('limit') : self::PAGINATION_PAGE_SIZE;
         if(request()->has('limitForMenu')){
             $limit=192;
         }


        $paginate = Container::getInstance()->makeWith(LengthAwarePaginator::class, [
            'items' => $results->forPage($page, $limit),
            'total' => $results->count(),
            'perPage' => $limit,
            'currentPage' => $page,
            'options' => [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => 'page',
            ],
        ]);

        $paginate->setPath('search');
        $paginate->appends($_GET);

        return $paginate;
    }

    public function getFormattedProduct($productFlat, $seller = 0)
    {

        $productFlat->condition = $this->getProductCondition($productFlat, true);
        $productFlat->link = route('shop.product.index', $productFlat->url_key);
        $productFlat->image = app('Webkul\Product\Helpers\ProductImage')->getProductBaseImage($productFlat)['medium_image_url'];

        if(isset($productFlat->product)){
            if($productFlat->product) {
                $productFlat->isInStock = $productFlat->product->getTypeInstance()->haveSufficientQuantity(1, $seller);

                $productFlat->price = $productFlat->product->getTypeInstance($productFlat)->getPriceHtml();
            }
        }
        //check if there is a free shipping
        $productFlat->isFreeShipping = $productFlat->product->free_shipping;
        $productFlat->isWithinThirtyDays = strtotime($productFlat->created_at) < strtotime('-30 days') ? 0 : 1;

        $productFlat->addToCartHtml = view('shop::products.add-to-cart', [
            'showCompare'       => true,
            'product'           => $productFlat,
            'addWishlistClass'  => '',
            'btnText'           => null,
            'moveToCart'        => null,
            'addToCartBtnClass' => 'small-padding',
        ])->render();

        return $productFlat;
    }

    public function getProductCondition($product, $isSeller = false): string
    {
        if ($isSeller) {
            $condition=[];
            if ($product->condition) {
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
                if($product->condition){
                    if(isset($conditions[$product->condition])){
                        $condition = [$conditions[$product->condition]];
                    }
                }
                if ($product->used_condition) {
                    if(isset($usedConditions[$product->used_condition])){
                        array_push($condition, $usedConditions[$product->used_condition]);
                    }
                }
                return implode(': ', $condition);
            }
            return '';
        } else {
            $condition = [];
            $conditionAttribute = app(AttributeRepository::class)->getAttributeByCode('condition');
           if(isset($product->product)){
               if($product->product){
                   $conditionAttributeValue = $product->product['condition'];
               }
           }
            foreach ($conditionAttribute->options as $option) {
                if(isset($conditionAttributeValue)){
                    if ($conditionAttributeValue == $option->id) {
                        array_push($condition, $option->admin_name);
                    }
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
            return implode(': ', $condition);
        }
    }

    public function isProductWished($productId, $sellerId): bool
    {
        $customer = auth()->guard('customer')->user();

        if (!$sellerId) $sellerId = 0;

        $res = MarketplaceCustomerWishlist::where([
            'customer_id' => $customer->id,
            'product_id' => $productId,
            'marketplace_seller_id' => $sellerId
        ])->get()->first();

        if ($res) {
            return true;
        } else {
            return false;
        }
    }


}