<?php

namespace Devvly\ElasticSearch\Listeners;

use Elasticsearch\Client;
use Webkul\Product\Models\Product as ProductModel;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductFlat as ProductFlatModel;

class Product
{

    const PRODUCT_SEARCH_ATTRIBUTES = [
        'name' => 'text_value',
        'sku'  =>  'text_value' ,
        'short_description' => 'text_value' ,
        'description' => 'text_value',
        'condition' => 'integer_value',
        'used_condition' => 'integer_value',
        'Finish' => 'integer_value',
        'stock_type' => 'integer_value',
        'man_part_num' => 'text_value',
        'compatible_with' => 'text_value',
        'action' => 'integer_value',
        'adult_sig' => 'boolean_value',
        'barrel_length' => 'integer_value',
        'bullet_type' => 'text_value',
        'caliber_singleselect' => 'integer_value',
        'caliber_multiselect' => 'text_value',
        'capacity' => 'integer_value',
        'chokes' => 'text_value',
        'gauge' => 'integer_value',
        'grains' => 'text_value',
        'ground_only' => 'boolean_value',
        'gun_size' => 'integer_value',
        'Hand' => 'integer_value',
        'manufacturer_ammunition' => 'integer_value',
        'manufacturer_firearm' => 'integer_value',
        'material' => 'integer_value',
        'moa' => 'integer_value',
        'Model' => 'text_value',
        'ounce_of_shot' => 'text_value',
        'reticle' => 'text_value',
        'round_count' => 'integer_value',
        'safety' => 'text_value',
        'shell_length' => 'integer_value',
        'shot_size' => 'integer_value',
        'type_of_barrel' => 'integer_value',
        'units_per_box' => 'text_value',
        'units_per_case' => 'text_value',
        'velocity' => 'text_value',
        'price' => 'float_value'
    ];

    const CATEGORY_DEFAULT_LOCALE_CODE = 'en';

    protected $productSearchAttributes = [];



    /**
     * @var Client
     */
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function onProductSaved(ProductModel $product)
    {
        $productFlat = ProductFlatModel::query()->where('product_id', '=', $product->id)->get()->first();
        if ($productFlat) {
            if ($productFlat->status == 1 && !$productFlat->parent_id) {
                // Set search attributes
                $productFlat = $this->setSearchAttributesForProduct($productFlat);
                // Set Product Inventory
                $productFlat = $this->setSearchInventoryForProduct($productFlat);
                // Set Product Categories
                $productFlat = $this->setSearchCategoriesForProduct($productFlat);
                // Set Product Seller
                $productFlat = $this->setSearchSellerForProduct($productFlat);


                $productFlat->object = serialize($productFlat);
                $this->elasticsearch->index([
                    'index' => $productFlat->getSearchIndex(),
                    'type' => $productFlat->getSearchType(),
                    'id' => $productFlat->product_id,
                    'body' => $productFlat->toArray(true),
                ]);
            } else {
                $this->onProductDeleted($productFlat->product_id);
            }
        }
    }


    protected function setSearchAttributesForProduct($product)
    {
        foreach ($this->productSearchAttributes as $attributeId => $attributeData) {
            $productAttributeValue = ProductAttributeValue::query()
                ->where([
                    'product_id' => $product->product_id,
                    'attribute_id' => $attributeId,
                ])
                ->first();
            if ($productAttributeValue) {
                $product->{$attributeData['code']} = $productAttributeValue->{$attributeData['valueCode']};
                if($attributeData['code']=='price'){
                    $product->productPrice = (float) $productAttributeValue->{$attributeData['valueCode']};
                }
            }
        }
        return $product;
    }

    protected function setSearchCategoriesForProduct($product){

        $categories=$product->product->categories->pluck('id');
        if($categories){
            $product->productCategories = $categories;
        }

        return $product;
    }

    protected function setSearchInventoryForProduct($product){
        $childProductsFlat=ProductFlatModel::query()->where(['status' => 1])->where('parent_id',$product->id)->get();
        if($childProductsFlat->count()){
            foreach ($childProductsFlat as $childProductFlat ){
                if(($childProductFlat->quantity - $childProductFlat->ordered_quantity) > 0){
                    $product->isInStockIndex= 1 ;
                    return $product;
                }
            }
        }
        $product->isInStockIndex= ($product->quantity - $product->ordered_quantity) > 0 ? 1 : 0 ;
        return $product;
    }

    protected function setSearchSellerForProduct($product){
        $product->marketplace_seller_id = $product->marketplace_seller_id;

        return $product;
    }


    public function onProductDeleted(string $productId)
    {
        try {
            $this->elasticsearch->delete([
                'index' => app(ProductFlatModel::class)->getSearchIndex(),
                'type' => app(ProductFlatModel::class)->getSearchType(),
                'id' => $productId,
            ]);
        } catch(\Exception $e) {}
    }

}