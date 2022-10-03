<?php

namespace Webkul\Product\Repositories;

use DB;
use Webkul\Core\Eloquent\Repository;
use Webkul\Marketplace\Helpers\Marketplace;

class ProductFlatRepository extends Repository
{
    public function model()
    {
        return 'Webkul\Product\Contracts\ProductFlat';
    }

    /**
     * Maximum Price of Category Product
     *
     * @param \Webkul\Category\Contracts\Category  $category
     * @return float
     */
    public function getCategoryProductMaximumPrice($category = null)
    {
        if (! $category) {
            return $this->model->max('max_price');
        }

        return $this->model
                    ->leftJoin('product_categories', 'product_flat.product_id', 'product_categories.product_id')
                    ->where('product_categories.category_id', $category->id)
                    ->max('max_price');
    }

    public function search(array $term, $sortOptions = [])
    {
        return (new Marketplace())->paginate(collect([]));
    }

    public function getAllIndexedData()
    {
        return (new Marketplace())->paginate(collect([]));
    }
    /**
     * get Category Product Attribute
     *
     * @param  int  $categoryId
     * @return array
     */
    public function getCategoryProductAttribute($categoryId)
    {
        $qb = $this->model
                   ->leftJoin('product_categories', 'product_flat.product_id', 'product_categories.product_id')
                   ->where('product_categories.category_id', $categoryId)
                   ->where('product_flat.channel', core()->getCurrentChannelCode())
                   ->where('product_flat.locale', app()->getLocale());

        $productArrributes = $qb->leftJoin('product_attribute_values as pa', 'product_flat.product_id', 'pa.product_id')
                                ->pluck('pa.attribute_id')
                                ->toArray();

        $productSuperArrributes = $qb->leftJoin('product_super_attributes as ps', 'product_flat.product_id', 'ps.product_id')
                                     ->pluck('ps.attribute_id')
                                     ->toArray();

        $productCategoryArrributes = array_unique(array_merge($productArrributes, $productSuperArrributes));

        return $productCategoryArrributes;
    }

    /**
     * get Filterable Attributes.
     *
     * @param  array  $category
     * @param  array  $products
     * @return \Illuminate\Support\Collection
     */
    public function getFilterableAttributes($category, $products) {
        $filterAttributes = [];

        if (count($category->filterableAttributes) > 0 ) {
            $filterAttributes = $category->filterableAttributes;
        } else {
            $categoryProductAttributes = $this->getCategoryProductAttribute($category->id);

            if ($categoryProductAttributes) {
                foreach (app('Webkul\Attribute\Repositories\AttributeRepository')->getFilterAttributes() as $filterAttribute) {
                    if (in_array($filterAttribute->id, $categoryProductAttributes)) {
                        $filterAttributes[] = $filterAttribute;
                    } else  if ($filterAttribute ['code'] == 'price') {
                        $filterAttributes[] = $filterAttribute;
                    }
                }

                $filterAttributes = collect($filterAttributes);
            }
        }

        return $filterAttributes;
    }

    /**
     * Returns seller by product
     *
     * @param integer $productId
     * @return boolean
     */
    public function getSellerByProductId($productId)
    {
        $product = $this->findWhere(['product_id' => $productId])->first();
        if (!$product) {
            return;
        }

        return $product->seller;
    }

    public function getTotalProducts($seller)
    {
        return $this->findWhere(
            [
                ['marketplace_seller_id','=', $seller->id],
                ['status','=',1],
                ['parent_id','=', null],
                ['url_key','!=',null]
            ]
        )->count();

    }

    /**
     * Returns count of seller that selling the same product
     *
     * @param Product $product
     * @return integer
     */
    public function getSellerCount($product)
    {

        return $this->findWhere(
            [
                ['product_id','=',  $product->id],
                ['status','=',1],
                ['parent_id','=', null],
                ['url_key','!=',null]
            ]
        )->count();
    }

    public function getSellerProductsCount($seller)
    {
        return $this->findWhere(['marketplace_seller_id' => $seller->id])->count();

    }



    public function sellerProductAdvancedSearch($seller){
        $params = request()->all();
        $params['marketplace_seller_id']=$seller;
        foreach ($params as $key =>$param){
            $terms[$key]=$param;
        }
        $sortOptions = [
            'sortField' => '',
            'sortOrder' => ''
        ];
        $productsArray=[];
        $paginationHtml='';

        $products = app('Webkul\Product\Repositories\ProductFlatRepository')->search($terms, $sortOptions);
        if($products){
            $productItems = $products->items();

            if ($productItems) {
                $formattedProducts = [];

                $helper = new Marketplace();
                foreach ($productItems as $product) {
                    array_push($formattedProducts, $helper->getFormattedProduct($product, $product->marketplace_seller_id));
                }

                $productsArray['data'] = $formattedProducts;
            }
            $paginationHtml=$products->appends(request()->input())->links()->toHtml();
        }
        return response()->json($response ?? [
                'products'       => $productsArray,
                'paginationHTML' => $paginationHtml

            ]);

    }

    public function getFilterableAttributesWithOptionsAndProductsAmountForSearchPage($type,$search){

        $filter=['attributes' =>[]];
        $searched_attributes=['caliber_multiselect','manufacturer_firearm','condition','caliber_singleselect','action','capacity','material'];
        $term = [];
        if($search){
            $term['search']=$search;
        }
        $sortOptions = [
            'sortField' => request()->get('sort'),
            'sortOrder' => request()->get('order')
        ];
        
        $helper = new Marketplace();
        if ($term) {
            request()->request->add(['limitForMenu'=>96]);
            $pagination = $this->search( $term, $sortOptions);
            if ($pagination) {
                $data = array_map(function ($product) use ($helper) {
                    return $helper->getFormattedProduct($product, $product->marketplace_seller_id)->toArray(true);
                }, $pagination->items());
            }
        }
    if(isset($data)){
        foreach ($data as $product){
            foreach ($searched_attributes as $code) {
                if (isset($product[$code])) {
                    if ($product[$code]) {
                        $key = $this->searchByValue($code, $filter['attributes'], 'code');
                        if (is_numeric($key)) {
                            $attr_option = app('Webkul\Attribute\Repositories\AttributeOptionRepository')->find($product[$code]);
                            if ($attr_option) {
                                $index = $this->searchByValue($attr_option->admin_name,
                                    $filter['attributes'][$key]['options'], 'admin_name');
                                if (is_numeric($index)) {
                                    $filter['attributes'][$key]['options'][$index]['products_amount'] += 1;
                                } else {
                                    array_push($filter['attributes'][$key]['options'], [
                                        "id" => $attr_option->id, "admin_name" => $attr_option->admin_name,
                                        "products_amount" => 1
                                    ]);
                                }
                            }
                        } else {
                            $attribute = app('Webkul\Attribute\Repositories\AttributeRepository')->findOneByField('code',
                                $code);
                            if ($attribute) {
                                $attr_option = app('Webkul\Attribute\Repositories\AttributeOptionRepository')->find($product[$code]);
                                if ($attr_option) {
                                    array_push($filter['attributes'], [
                                        "id" => $attribute->id, "code" => $attribute->code,
                                        "admin_name" => $attribute->admin_name, "primary_filter" => $attribute->primary_filter,"options" => [
                                            [
                                                "id" => $attr_option->id, "admin_name" => $attr_option->admin_name,
                                                "products_amount" => 1
                                            ]
                                        ]
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

     return $filter;

    }

    /**
     * Returns the all products of the seller
     *
     * @param integer $seller
     * @return Collection
     */
    public function findAllBySeller($seller)
    {
       $params = request()->input();
                $results = app('Webkul\Product\Repositories\ProductFlatRepository')->scopeQuery(function ($query) use ($seller, $params) {
                    $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());
                    $locale = request()->get('locale') ?: app()->getLocale();
                    $qb = $query->distinct()
                        ->addSelect('product_flat.*')
                        ->addSelect(DB::raw('IF( product_flat.special_price_from IS NOT NULL
                                    AND product_flat.special_price_to IS NOT NULL , IF( NOW( ) >= product_flat.special_price_from
                                    AND NOW( ) <= product_flat.special_price_to, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) , IF( product_flat.special_price_from IS NULL , IF( product_flat.special_price_to IS NULL , IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , IF( NOW( ) <= product_flat.special_price_to, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) ) , IF( product_flat.special_price_to IS NULL , IF( NOW( ) >= product_flat.special_price_from, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) , product_flat.price ) ) ) AS price'))
                        ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
                        ->leftJoin('product_attribute_values', 'product_attribute_values.product_id', '=', 'product_flat.product_id')
                        ->where('product_flat.status', 1)
                        ->whereNotNull('product_flat.url_key')
                        ->where('product_flat.marketplace_seller_id', $seller->id);
                        /*->where('product_flat.is_seller_approved', 1);*/

                    $queryBuilder = $qb->leftJoin('product_flat as flat_variants', function ($qb) use ($channel, $locale) {
                        $qb->on('product_flat.id', '=', 'flat_variants.parent_id')
                            ->where('flat_variants.channel', $channel)
                            ->where('flat_variants.locale', $locale);
                    });
                    if (isset($params['sort'])) {
                        $attribute = $this->attribute->findOneByField('code', $params['sort']);
                        if ($params['sort'] == 'price') {
                            $qb->orderBy($attribute->code, $params['order']);
                        } else {
                            $qb->orderBy($params['sort'] == 'created_at' ? 'product_flat.created_at' : $attribute->code, $params['order']);
                        }
                    } else {
                        $qb->orderBy('product_flat.created_at', 'desc');
                    }
                    if (isset($params['search']))
                        $qb->where('product_flat.name', 'like', '%' . urldecode($params['search']) . '%');
                    $attributes = app('Webkul\Attribute\Repositories\AttributeRepository')->getProductDefaultAttributes(array_keys(request()->input()));
                    $GLOBALS['conditions'] = 1;
                    $where_condition = '  1 ';
                    $counter = 1;
                    foreach ($attributes as $attribute) {
                        $column = 'product_attribute_values.' . ProductAttributeValueProxy::modelClass()::$attributeTypeFields[$attribute->type];
                        $queryParams = explode(',', request()->get($attribute->code));
                        if ($attribute->type != 'price') {
                            $values = implode("','", $queryParams);
                            if ($counter == 1) {
                                $where_condition .= " and (" . $column . " in ('" . $values . "') and product_attribute_values.attribute_id = " . $attribute->id . " and `product_flat`.`marketplace_seller_id`= " . $seller->id . ") ";
                            } else {
                                $where_condition .= " or (" . $column . " in ('" . $values . "') and product_attribute_values.attribute_id = " . $attribute->id . " and `product_flat`.`marketplace_seller_id`= " . $seller->id . ") ";
                            }
                        } else {
                            if ($counter == 1) {
                                $where_condition .= " and (" . $column . " >= " . core()->convertToBasePrice(current($queryParams)) . " and " . $column . " <= " . core()->convertToBasePrice(end($queryParams)) . "  and product_attribute_values.attribute_id = " . $attribute->id . " and `product_flat`.`marketplace_seller_id`= " . $seller->id . ") ";
                            } else {
                                $where_condition .= " or (" . $column . " >= " . core()->convertToBasePrice(current($queryParams)) . " and " . $column . " <= " . core()->convertToBasePrice(end($queryParams)) . "  and product_attribute_values.attribute_id = " . $attribute->id . "  and `product_flat`.`marketplace_seller_id`= " . $seller->id . ") ";
                            }
                        }
                        $counter += 1;
                    }
                    $qb = $qb->whereRaw(DB::raw($where_condition));
                    $qb->groupBy('product_flat.id');
                    if(isset($params['page'])){
                        if($qb->get()->count() < $params['page'] * 12 ){
                            unset($params['page']);
                            request()->request->remove('page');
                        }
                    }
                    return $qb;
                })->paginate(isset($params['limit']) ? $params['limit'] : 12);
                return $results;
    }

    /**
     * Returns the seller products of the product
     *
     * @param Product $product
     * @return Collection
     */
    public function getSellerProducts($product)
    {
        return $this->findWhere([
            'product_id' => $product->product_id ?? $product->id
        ]);
    }

    /**
     * Search Product by Attribute
     *
     * @return Collection
     */
    public function searchProducts($term, $firearm = 1)
    {
        $results = app('Webkul\Product\Repositories\ProductFlatRepository')->scopeQuery(function ($query) use ($term, $firearm) {
            $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

            $locale = request()->get('locale') ?: app()->getLocale();

            $query = $query->distinct()
                ->addSelect('product_flat.*');
            if ($firearm == 0) {
                $query = $query->join('products', 'products.id', '=', 'product_flat.product_id')
                    ->join('attribute_families', 'attribute_families.id', '=', 'products.attribute_family_id')
                    ->where('attribute_families.code', '!=', 'Firearm');
            }

            $query = $query->where('product_flat.status', 1)
                ->where('product_flat.channel', $channel)
                ->where('product_flat.locale', $locale)
                ->whereNotNull('product_flat.url_key')
                ->where('product_flat.name', 'like', '%' . $term . '%')
                ->orderBy('product_id', 'desc');
            return $query;
        })->paginate(16);

        return $results;
    }

    /**
     * @param integer $sellerId
     * @return Collection
     */
    public function getPopularProducts($sellerId, $pageTotal = 4)
    {
        return app('Webkul\Product\Repositories\ProductFlatRepository')->scopeQuery(function ($query) use ($sellerId) {
            $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

            $locale = request()->get('locale') ?: app()->getLocale();

            $qb = $query->distinct()
                ->addSelect('product_flat.*')
                ->where('product_flat.status', 1)
                ->where('product_flat.channel', $channel)
                ->where('product_flat.locale', $locale)
                ->whereNotNull('product_flat.url_key')
                ->where('product_flat.marketplace_seller_id', $sellerId)
                /*->where('product_flat.is_seller_approved', 1)*/
                ->whereIn('product_flat.product_id', $this->getTopSellingProducts($sellerId))
                ->orderBy('id', 'desc');

            return $qb;

        })->paginate($pageTotal);
    }

    public function getTopSellingProducts($sellerId)
    {
        $seller =  app('Webkul\Marketplace\Repositories\SellerRepository')->find($sellerId);

        $result = app('Webkul\Marketplace\Repositories\OrderItemRepository')->getModel()
            ->leftJoin('product_flat', 'marketplace_order_items.product_id', 'product_flat.product_id')
            ->leftJoin('order_items', 'marketplace_order_items.order_item_id', 'order_items.id')
            ->leftJoin('marketplace_orders', 'marketplace_order_items.marketplace_order_id', 'marketplace_orders.id')
            ->select(DB::raw('SUM(qty_ordered) as total_qty_ordered'), 'product_flat.product_id')
            ->where('marketplace_orders.marketplace_seller_id', $seller->id)
            /*->where('product_flat.is_seller_approved', 1)*/
            ->whereNull('order_items.parent_id')
            ->groupBy('product_flat.product_id')
            ->orderBy('total_qty_ordered', 'DESC')
            ->limit(4)
            ->get();

        return $result->pluck('product_id')->toArray();
    }



    public function getInstructorCourses($sellerId){


        $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());
        $locale = request()->get('locale') ?: app()->getLocale();
        $products = app('Webkul\Product\Repositories\ProductFlatRepository')->getModel()
            ->addSelect('product_flat.*')/*,'booking_products.type', 'booking_products.qty', 'booking_products.location', 'booking_products.show_location', 'booking_products.available_every_week','booking_products.available_from', 'booking_products.available_to'*/
            ->addSelect(DB::raw('IF( product_flat.special_price_from IS NOT NULL
                            AND product_flat.special_price_to IS NOT NULL , IF( NOW( ) >= product_flat.special_price_from
                            AND NOW( ) <= product_flat.special_price_to, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) , IF( product_flat.special_price_from IS NULL , IF( product_flat.special_price_to IS NULL , IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , IF( NOW( ) <= product_flat.special_price_to, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) ) , IF( product_flat.special_price_to IS NULL , IF( NOW( ) >= product_flat.special_price_from, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) , product_flat.price ) ) ) AS price'))

            ->Join('products', 'product_flat.product_id', '=', 'products.id')
            ->Join('booking_products', 'product_flat.product_id', '=', 'booking_products.product_id')

            ->where('product_flat.status', 1)
            ->where('product_flat.channel', $channel)
            ->where('product_flat.locale', $locale)
            ->where('products.type', 'booking')
            ->where('product_flat.marketplace_seller_id', $sellerId)
            /*->where('product_flat.is_seller_approved', 1)*/
            ->orderBy('id', 'desc')
            ->get();
        return $products;


    }

    /**
     * Returns the seller products of the product id
     *
     * @param integer $productId
     * @param integer $sellerId
     * @return Collection
     */
    public function getMarketplaceProductByProduct($productId, $sellerId = null)
    {
        if ($sellerId) {
            $seller = app('Webkul\Marketplace\Repositories\SellerRepository')->find($sellerId);
        } else {
            if (auth()->guard('customer')->check()) {
                $seller = app('Webkul\Marketplace\Repositories\SellerRepository')->findOneByField('customer_id', auth()->guard('customer')->user()->id);
            } else {
                return;
            }
        }

        return $this->findOneWhere([
            'product_id' => $productId,
            // 'is_owner' => 1,
            'marketplace_seller_id' => $seller->id,
        ]);
    }
    function searchByValue($id, $array,$code) {
        foreach ($array as $key => $val) {
            if ($val[$code] === $id) {
                return $key;
            }
        }
        return null;
    }
}