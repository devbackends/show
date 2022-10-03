<?php

namespace Devvly\ElasticSearch\Console\Commands;

use Elasticsearch\Client;
use Illuminate\Console\Command;
use Webkul\Attribute\Models\Attribute;
use Webkul\Category\Models\Category;
use Webkul\Marketplace\Models\Seller;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductFlat;
use Webkul\Product\Models\Product;

class Elasticsearch extends Command
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

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run elasticsearch indexing';

    protected $elasticsearch;

    protected $productSearchAttributes = [];

    /**
     * Elasticsearch constructor.
     * @param Client $elasticsearch
     */
    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;

        $this->setProductSearchAttributes();

        parent::__construct();
    }

    public function handle()
    {
        $this->indexProducts();

        $this->indexCategories();

    }

    protected function indexProducts()
    {
        $first = ProductFlat::query()
            ->where(['status' => 1])
            ->whereNull('parent_id')
            ->whereRaw('quantity - ordered_quantity > 0');

        $bookingProductsIds=Product::query()
            ->where('type','booking')
            ->pluck('id');
        $second=ProductFlat::query()
            ->where(['status' => 1])
            ->whereIn('product_id',$bookingProductsIds)
            ->union($first);
        $configurableProducts=ProductFlat::query()
            ->whereNotNull('parent_id')
            ->groupBy('parent_id')->pluck('parent_id');
        $products=ProductFlat::query()
            ->where(['status' => 1])
            ->whereIn('id',$configurableProducts)
            ->union($second)->get();

        $this->comment('Products');
        $this->output->progressStart(count($products));

        try {
            $this->elasticsearch->indices()->delete(['index' => app(ProductFlat::class)->getSearchIndex()]);
        } catch (\Exception $e) {}

        foreach ($products as $product) {

            // Set search attributes
            $product = $this->setSearchAttributesForProduct($product);


            // Set Product Inventory
            $product = $this->setSearchInventoryForProduct($product);

            // Set Product Categories
            $product = $this->setSearchCategoriesForProduct($product);

            // Set Product Seller
            $product = $this->setSearchSellerForProduct($product);


            $product->object = serialize($product);
            $this->elasticsearch->index([
                'index' => $product->getSearchIndex(),
                'type' => $product->getSearchType(),
                'id' => $product->product_id,
                'body' => $product->toArray(true),
            ]);
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }

    protected function indexCategories()
    {
        $categories = Category::query()->where(['status' => '1'])->get();
        $groupedCategories = $this->getCategoriesGroupedById($categories);
        $this->comment('Categories');
        $this->output->progressStart(count($categories));

        try {
            $this->elasticsearch->indices()->delete(['index' => app(Category::class)->getSearchIndex()]);
        } catch (\Exception $e) {}

        foreach ($categories as $category) {

            $params = [
                'index' => $category->getSearchIndex(),
                'type' => $category->getSearchType(),
                'id' => $category->getKey()
            ];
            $category->url = implode('/', array_reverse(explode('/', $this->getCategoryUrl($category->id, $groupedCategories))));
            if (isset($category->url[0]) && $category->url[0] !== '/') $category->url = '/'.$category->url;
            $category->object = serialize($category);
            $category = $category->toArray();
            unset($category['translations']);
            $params['body'] = $category;

            $this->elasticsearch->index($params);
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
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
        //check booking product
        if($product->type=='booking'){
            $product->isInStockIndex= 1;
            return $product;
        }

        //check configurable product
        $childProductsFlat=ProductFlat::query()->where(['status' => 1])->where('parent_id',$product->id)->get();
        if($childProductsFlat->count()){
            foreach ($childProductsFlat as $childProductFlat ){
                if(($childProductFlat->quantity - $childProductFlat->ordered_quantity) > 0){
                    $product->isInStockIndex= 1 ;
                    return $product;
                }
            }
        }

        //check simple product
        $product->isInStockIndex= ($product->quantity - $product->ordered_quantity) > 0 ? 1 : 0 ;
        return $product;
    }

    protected function setSearchSellerForProduct($product){
        $product->marketplace_seller_id = $product->marketplace_seller_id;

        return $product;
    }
    protected function getCategoriesGroupedById($categories): array
    {
        $result = [];
        foreach ($categories as $category) {
            $result[$category->id] = $category;
        }
        return $result;
    }

    protected function getCategoryUrl($parentId, $categories): string
    {
        if ($categories[$parentId]->parent_id) {
            return $categories[$parentId]->slug . '/' . $this->getCategoryUrl($categories[$parentId]->parent_id, $categories);
        } else {
            return ($categories[$parentId]->slug === 'root') ? '' : $categories[$parentId]->slug;
        }
    }

    
    protected function setProductSearchAttributes()
    {
        foreach (self::PRODUCT_SEARCH_ATTRIBUTES as $attributeCode => $valueCode) {
            $attribute = Attribute::query()
                ->where([
                    'code' => $attributeCode,
                ])->first();
            if ($attribute) {
                $this->productSearchAttributes[$attribute->id] = [
                    'code' => $attributeCode,
                    'valueCode' => $valueCode,
                ];
            }
        }
    }
}
