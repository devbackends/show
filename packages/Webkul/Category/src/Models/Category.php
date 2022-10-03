<?php

namespace Webkul\Category\Models;

use Devvly\ElasticSearch\Traits\Searchable;
use Webkul\Attribute\Models\AttributeOption;
use Webkul\Core\Eloquent\TranslatableModel;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Webkul\Category\Contracts\Category as CategoryContract;
use Webkul\Attribute\Models\AttributeProxy;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Product\Models\Product;
use Illuminate\Support\Facades\DB;
use Webkul\Product\Models\ProductProxy;
use Webkul\Product\Models\ProductFlatProxy;

/**
 * Class Category
 *
 * @package Webkul\Category\Models
 *
 * @property-read string $url_path maintained by database triggers
 */
class Category extends TranslatableModel implements CategoryContract
{
    use NodeTrait, Searchable;

    public $translatedAttributes = [
        'name',
        'description',
        'slug',
        'url_path',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $fillable = ['position', 'status', 'display_mode', 'parent_id'];

    protected $with = ['translations','children'];


    /**
     * Get image url for the category image.
     */
    public function image_url()
    {
        if (! $this->image)
            return;

        return Storage::url($this->image);
    }

    /**
     * Get image url for the category image.
     */
    public function getImageUrlAttribute()
    {
        return $this->image_url();
    }

    /**
     * The filterable attributes that belong to the category.
     */
    public function filterableAttributes()
    {
        return $this->belongsToMany(AttributeProxy::modelClass(), 'category_filterable_attributes')->with('options');
    }

    public function filterableAttributesWithOptionsAndProductsAmount($categoryId)
    {
        $attrs = $this->belongsToMany(AttributeProxy::modelClass(), 'category_filterable_attributes')->get();
        $attributes=[];
        $attributesOptionsProductsAmount=\Webkul\Attribute\Models\AttributesOptionsProductsAmount::query()->selectRaw('attributes_options_products_amount.seller_id,marketplace_sellers.shop_title as shop_title ,attributes_options_products_amount.products_amount,attributes_options_products_amount.option_id,attribute_options.admin_name as `option` ,attributes.primary_filter ,attributes.admin_name as attribute,attributes.code as attribute_code,attributes.id as attribute_id')
            ->join('attribute_options','attribute_options.id','=','attributes_options_products_amount.option_id')
            ->join('attributes','attribute_options.attribute_id','=','attributes.id')
            ->join('marketplace_sellers','marketplace_sellers.id','=','attributes_options_products_amount.seller_id')
            ->where('category_id','=',$categoryId)
            ->where('products_amount','>',0)
            ->whereIn('attributes.id', $attrs->pluck('id')->toArray())
            ->orderBy('attributes.primary_filter','desc')
            ->get();

        $attributeCounter=-1;
        $sellerCounter=-1;

        foreach($attributesOptionsProductsAmount as $key => $attributeOptionProductAmount){
            if(!isset($attrsCheck[$attributeOptionProductAmount->attribute_id])){
                $attributeCounter+=1;
                $attrsCheck[$attributeOptionProductAmount->attribute_id]=$attributeCounter;
                $attributes[$attributeCounter]=array('id'=>$attributeOptionProductAmount->attribute_id,'code'=>$attributeOptionProductAmount->attribute_code,'primary_filter'=> $attributeOptionProductAmount->primary_filter ,'admin_name'=>$attributeOptionProductAmount->attribute,'options'=>[]);
            }
            $checkFoundBefore=0;
            foreach ($attributes[$attrsCheck[$attributeOptionProductAmount->attribute_id]]['options'] as $key => $option){
                if($option['id']==$attributeOptionProductAmount->option_id){
                    $checkFoundBefore=1;
                    $p_amount=$option['products_amount']+$attributeOptionProductAmount->products_amount;
                    $attributes[$attrsCheck[$attributeOptionProductAmount->attribute_id]]['options'][$key]= array('admin_name'=>$attributeOptionProductAmount->option,'products_amount'=>$p_amount,'id'=>$attributeOptionProductAmount->option_id);
                }
            }
            if(!$checkFoundBefore){
                $attributes[$attrsCheck[$attributeOptionProductAmount->attribute_id]]['options'][count($attributes[$attrsCheck[$attributeOptionProductAmount->attribute_id]]['options'])]= array('admin_name'=>$attributeOptionProductAmount->option,'products_amount'=>$attributeOptionProductAmount->products_amount,'id'=>$attributeOptionProductAmount->option_id);
            }
        }
        foreach ($attributes as $key => $attribute){
            $columns = array_column($attribute['options'], 'admin_name');
            array_multisort($columns, SORT_ASC, $attribute['options']);
            $attributes[$key]['options']=$attribute['options'];
        }
        $cat=app(CategoryRepository::class)->find($categoryId);
        $catProductsArray=$cat->products->pluck('id');

        $sellers=[];
        $results = app('Webkul\Product\Repositories\ProductFlatRepository')->scopeQuery(function($query) use($categoryId) {

            return $query->distinct()->selectRaw('count(*) as counter,marketplace_sellers.id as seller_id,marketplace_sellers.shop_title as shop_title')
                ->leftJoin('products','products.id','=','product_flat.product_id')
                ->join('product_categories', 'product_flat.product_id', '=', 'product_categories.product_id')
                ->join('marketplace_sellers', 'product_flat.marketplace_seller_id', '=', 'marketplace_sellers.id')
                ->where('product_flat.status', 1)
                ->whereRaw('products.parent_id is null')
                ->whereRaw(' IF(products.type != "booking" AND products.type != "configurable" ,  product_flat.quantity - product_flat.ordered_quantity , 1 ) > 0')
                ->where('product_categories.category_id',$categoryId)
                ->groupBy('product_flat.marketplace_seller_id');


        })->get();
        foreach($results as $key => $result){
            if($result->counter > 0){
                array_push($sellers,array('products_amount'=>$result->counter,"id"=>$result->seller_id,"shop_title"=>$result->shop_title));
            }
        }


        // below we are fetching Subcategories
        $cat=[];
        $children=app(CategoryRepository::class)->find($categoryId)->children->pluck('id');
        if($children->count()){
            $results = app('Webkul\Product\Repositories\ProductRepository')->scopeQuery(function($query) use($children) {

                return $query->distinct()->selectRaw('count(*) as counter,category_translations.name,category_translations.category_id')
                    ->join('product_flat', 'products.id', '=', 'product_flat.product_id')
                    ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
                    ->join('category_translations', 'product_categories.category_id', '=', 'category_translations.category_id')
                    ->where('product_flat.status', 1)
                    ->whereRaw('products.parent_id is null')
                    ->whereRaw('(IF(products.type != "booking" AND products.type != "configurable",  product_flat.quantity - product_flat.ordered_quantity , 1 )) > 0')
                    ->whereIn('product_categories.category_id',$children)
                    ->groupBy('product_categories.category_id');
            })->get();

            foreach($results as $key => $result){
                if($result->counter > 0){
                    $cat[$key]=array('id'=>$result->category_id,'products_amount'=>$result->counter,'name'=>$result->name);
                    /*array_push($sellers,array('products_amount'=>$result->counter,"id"=>$result->seller_id,"shop_title"=>$result->shop_title));*/
                }

            }
        }

        return array('attributes' => $attributes,'categories' => [$cat],'sellers'=>[$sellers]);

    }
    /**
     * Getting the root category of a category
     *
     * @return Category
     */
    public function getRootCategory(): Category
    {
        return Category::where([
            ['parent_id', '=', null],
            ['_lft', '<=', $this->_lft],
            ['_rgt', '>=', $this->_rgt],
        ])->first();
    }

    /**
     * Returns all categories within the category's path
     *
     * @return Category[]
     */
    public function getPathCategories(): array
    {
        $category = $this->findInTree();

        $categories = [$category];

        while (isset($category->parent)) {
            $category = $category->parent;
            $categories[] = $category;
        }

        return array_reverse($categories);
    }

    /**
     * Finds and returns the category within a nested category tree
     * will search in root category by default
     * is used to minimize the numbers of sql queries for it only uses the already cached tree
     *
     * @param Category[] $categoryTree
     * @return Category
     */
    public function findInTree($categoryTree = null): Category
    {
        if (! $categoryTree) {
            $categoryTree = app(CategoryRepository::class)->getVisibleCategoryTree($this->getRootCategory()->id);
        }

        $category = $categoryTree->first();

        if (! $category) {
            throw new NotFoundHttpException('category not found in tree');
        }

        if ($category->id === $this->id) {
            return $category;
        }

        return $this->findInTree($category->children);
    }
    public function products()
    {
        return $this->belongsToMany(ProductProxy::modelClass(), 'product_categories');
    }

    public function productsFlat()
    {
        return $this->belongsToMany(ProductFlatProxy::modelClass(), 'product_categories','product_id','product_id');
    }

}