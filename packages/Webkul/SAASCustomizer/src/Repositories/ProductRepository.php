<?php

namespace Webkul\SAASCustomizer\Repositories;

use Carbon\Carbon;
use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Cache;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Repositories\ProductFlatRepository;

/**
 * Product Reposotory
 *
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductRepository extends Repository
{
    /**
     * AttributeRepository object
     *
     * @var \Webkul\Attribute\Repositories\AttributeRepository
     */
    protected $attributeRepository;

    /**
     * Create a new repository instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @param  \Illuminate\Container\Container  $app
     * @return void
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        App $app
    )
    {
        $this->attributeRepository = $attributeRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Product\Contracts\Product';
    }

    /**
     * @param  int|array|null  $categoryId
     * @param array|null $attribute_family_ids
     * @return \Illuminate\Support\Collection
     */
    public function getAll($categoryId = null, $attribute_family_ids = null, $params = [])
    {
        if (Cache::has('getProducts')){
            return Cache::get('getProducts');
        }
        if(!count($params)){
            $params = request()->all();
        }

        return $this->getProducts($params, $categoryId, $attribute_family_ids);
    }

    public function getProducts($params = [], $categoryId = null, $attribute_family_ids = null)
    {
        $subcategories=[];
         if(isset($params['cat'])){
             $subcategories=explode(',',$params['cat']);
         }
        $sellers=[];
        if(isset($params['seller'])){
            $sellers=explode(',',$params['seller']);
        }


        $results = app(ProductFlatRepository::class)->scopeQuery(function($query) use($params, $categoryId, $attribute_family_ids,$subcategories,$sellers) {
            $channel = $params['channel'] ?? (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

            $locale = $params['locale'] ?? app()->getLocale();


            $qb =$query->addSelect('product_flat.*')
                ->addSelect(['products.attribute_family_id'])
                ->addSelect('product_categories.category_id as category_id')
                ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
                ->leftJoin('product_categories', 'products.id', '=', 'product_categories.product_id');

            if(isset($params['instock'])){
                $qb = $qb->whereRaw("product_flat.quantity - product_flat.ordered_quantity > 0 ");
            }
               $qb = $qb->where('product_flat.locale', $locale)
                ->whereNotNull('product_flat.url_key')
                ->where('product_flat.status','=', '1')
                ->where(function ($query) use ($channel) {
                    $query->where('product_flat.channel', $channel);
                    if ($channel == config('app.defaultChannel')) {
                        $query->orWhere('product_flat.show_on_marketplace', '1');
                    }
                });


            if (isset($params['compatibleWith'])) {
                $attr = Attribute::query()->where('code', '=', 'compatible_with')->first();
                $qb = $qb->leftJoin('product_attribute_values', function ($join) use ($attr) {
                    $join->on('products.id', '=', 'product_attribute_values.product_id')
                        ->where('product_attribute_values.attribute_id', '=', $attr->id);
                })->where('product_attribute_values.text_value', 'like', '%'.strtolower($params['compatibleWith']).'%');
            }


            if(sizeof($subcategories) > 0){
                foreach ($subcategories as $subcatId){
                    $qb->where('product_categories.category_id', $subcatId);
                }
            }else{
                if ($categoryId) {
                    if(is_array($categoryId)){
                        $qb->whereIn('product_categories.category_id', $categoryId);
                    }
                    else {
                        $qb->where('product_categories.category_id', $categoryId);
                    }
                }
            }
            if (sizeof($sellers) > 0) {
                foreach ($sellers as $sellerId) {
                    $qb->orwhere('product_flat.marketplace_seller_id', $sellerId);
                }
            }


            if($attribute_family_ids){
                $qb->whereIn('products.attribute_family_id', $attribute_family_ids);
            }
            if (array_key_exists('status', $params) && is_null($params['status'])) {
                $qb->where('product_flat.status', 1);
            }

            if (array_key_exists('visible_individually', $params) && is_null($params['visible_individually'])) {
                $qb->where('product_flat.visible_individually', 1);
            }

            $queryBuilder = $qb->leftJoin('product_flat as flat_variants', function($qb) use($channel, $locale) {
                $qb->on('product_flat.id', '=', 'flat_variants.parent_id')
                    ->where('flat_variants.channel', $channel)
                    ->where('flat_variants.locale', $locale);
            });

            if (isset($params['search'])){
                $qb->where('product_flat.name', 'like', '%' . urldecode($params['search']) . '%');
                $arr=explode(' ',$params['search']);
                if(sizeof($arr) > 1){
                    $pattern='';
                    foreach ($arr as $str){
                        $pattern.='%' . $str . '%';
                    }
                    $qb->orwhere('product_flat.name', 'like', $pattern);
                }

            }




            if (isset($params['sort'])) {
                $attribute = $this->attributeRepository->findOneByField('code', $params['sort']);

                if ($params['sort'] == 'price') {
                    if ($attribute->code == 'price') {
                        $qb->orderBy('min_price', $params['order']);
                    } else {
                        $qb->orderBy($attribute->code, $params['order']);
                    }
                } else {
                    $qb->orderBy($params['sort'] == 'created_at' ? 'product_flat.created_at' : $attribute->code, $params['order']);
                }
            }

            $qb = $qb->leftJoin('products as variants', 'products.id', '=', 'variants.parent_id');

            $qb = $qb->where(function($query1) use($qb, $params) {
                $aliases = [
                    'products' => 'filter_',
                    'variants' => 'variant_filter_',
                ];

                foreach($aliases as $table => $alias) {
                    $stop = null;
                    $query1 = $query1->orWhere(function($query2) use ($qb, $table, $alias, $params) {
                        foreach ($this->attributeRepository->getProductDefaultAttributes(array_keys($params)) as $code => $attribute) {
                            $aliasTemp = $alias . $attribute->code;

                            $qb = $qb->leftJoin('product_attribute_values as ' . $aliasTemp, $table . '.id', '=', $aliasTemp . '.product_id');

                            $column = ProductAttributeValue::$attributeTypeFields[$attribute->type];

                            $temp = explode(',', $params[$attribute->code]);

                            if ($attribute->type != 'price') {
                                $query2 = $query2->where($aliasTemp . '.attribute_id', $attribute->id);

                                $query2 = $query2->where(function($query3) use($aliasTemp, $column, $temp) {
                                    foreach($temp as $code => $filterValue) {
                                        if (! is_numeric($filterValue)) {
                                            continue;
                                        }

                                        $columns = $aliasTemp . '.' . $column;
                                        $query3 = $query3->orwhereRaw("find_in_set($filterValue, $columns)");
                                    }
                                });

                            } else {
                                $query2->where('product_flat.min_price', '>=', core()->convertToBasePrice(current($temp)))
                                    ->where('product_flat.min_price', '<=', core()->convertToBasePrice(end($temp)));

                            }
                        }
                    });
                }
            });

            return $qb->groupBy('product_flat.id')->orderByRaw('CASE WHEN product_flat.quantity = 0 THEN 1 END')
                ->orderBy('product_flat.id', 'desc');
        })->paginate(isset($params['limit']) ? $params['limit'] : 12);



        /*foreach ($results as $key => $result) {
            $results[$key] = ProductFlat::where('id', $result->id)->get()->first();
            // get the category relation:
            if($categoryId){
                $cat_id = $result->category_id;
                $results[$key]->category = CategoryProxy::where('id', $cat_id)->get()->first();
            }
            // set attribute_family_id
            if($attribute_family_ids){
                $results[$key]->attribute_family_id = $result->attribute_family_id;
            }
        }*/
        $date = Carbon::now();
        $date->addHour();
        Cache::put('getProducts', $results, $date);

        return $results;
    }
}