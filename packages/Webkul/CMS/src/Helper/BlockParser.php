<?php


namespace Webkul\CMS\Helper;


class BlockParser
{
    public function parse($data){

        // parse featured products:
        $data = str_replace('[featured_products]','@include("shop::home.featured-products")', $data);

        // parse new products:
        $data = str_replace('[new_products]', '@include("shop::home.new-products")', $data);

        // parse recently viewed products:
        $data = str_replace('[recently_viewed_products]', '@include("velocity::shop.products.list.recently-viewed")', $data);

        // parse Product Policy
        $data = str_replace('[product_policy]', '@include("velocity::shop.home.product-policy")', $data);

        return $data;
    }
}