<?php

namespace Webkul\Product\Repositories;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webkul\Attribute\Helper\AttributesOptionsProductsAmountHelper;
use Webkul\Attribute\Models\AttributeOption;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Core\Eloquent\Repository;

use Webkul\Product\Contracts\Product;
use Webkul\Product\Contracts\ProductFlat;
use Webkul\Product\Models\ProductAttributeValueProxy;


class ProductRepository extends Repository
{
    const FIREARM_FAMILY = 'firearm';

    /**
     * AttributeRepository object
     *
     * @var AttributeRepository
     */
    protected $attributeRepository;

    /**
     * Create a new repository instance.
     *
     * @param AttributeRepository $attributeRepository
     * @param App $app
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
     * @return string
     */
    function model()
    {
        return 'Webkul\Product\Contracts\Product';
    }

    /**
     * @param array $data
     * @return Product
     */
    public function create(array $data)
    {
        Event::dispatch('catalog.product.create.before');

        $typeInstance = app(config('product_types.' . $data['type'] . '.class'));

        $product = $typeInstance->create($data);

        Event::dispatch('catalog.product.create.after', $product);


        return $product;
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return Product
     */
    public function update(array $data, $id, $attribute = "id")
    {
        Event::dispatch('catalog.product.update.before', $id);

        $product = $this->find($id);

        if (isset($data['url_key'])) {
            $data['url_key'] = $this->cleanUrl($data['url_key']);
        }

        $product_info = $product->getTypeInstance()->update($data, $id, $attribute);

/*        if(isset($data['shipping_type'])){
            $sellerProduct = app('Webkul\Marketplace\Repositories\ProductRepository')->findWhere(['product_id'=>$id])->first();
            if($sellerProduct){
                $sellerProduct->shipping_type=$data['shipping_type'];
                $sellerProduct->save();
            }
        }*/

        if (isset($data['channels'])) {
            $product_info['channels'] = $data['channels'];
        }

        Event::dispatch('catalog.product.update.after', $product_info);


        return $product_info;
    }

    function cleanUrl($string) {
        $string=strtolower($string);

        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }

    /**
     * @param $id
     * @return void
     */
    public function delete($id)
    {
        Event::dispatch('catalog.product.delete.before', $id);

        $product = parent::find($id);
        (new AttributesOptionsProductsAmountHelper($product, []))->execute();

        parent::delete($id);

        Event::dispatch('catalog.product.delete.after', $id);
    }

    /**
     * @param $categoryId
     * @return LengthAwarePaginator
     */
    public function getAll($categoryId = null)
    {
        $params = request()->input();

        if (core()->getConfigData('catalog.products.storefront.products_per_page')) {
            $pages = explode(',', core()->getConfigData('catalog.products.storefront.products_per_page'));

            $perPage = isset($params['limit']) ? $params['limit'] : current($pages);
        } else {
            $perPage = isset($params['limit']) ? $params['limit'] : 12;
        }

        $page = Paginator::resolveCurrentPage('page');

        $repository = app(ProductFlatRepository::class)->scopeQuery(function($query) use($params, $categoryId) {
            $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

            $locale = request()->get('locale') ?: app()->getLocale();

            $qb = $query->distinct()
                ->select('product_flat.*')
                ->join('product_flat as variants', 'product_flat.id', '=', DB::raw('COALESCE(variants.parent_id, variants.id)'))
                ->leftJoin('product_categories', 'product_categories.product_id', '=', 'product_flat.product_id')
                ->leftJoin('product_attribute_values', 'product_attribute_values.product_id', '=', 'variants.product_id')
                ->where('product_flat.locale', $locale)
                ->whereNotNull('product_flat.url_key')
                ->where(function ($query) use($channel) {
                    $query->where('product_flat.channel', $channel);
                    if ($channel == config('app.defaultChannel')) {
                        $query->orWhere('product_flat.show_on_marketplace', '1');
                    }
                });

            if ($categoryId) {
                $qb->where('product_categories.category_id', $categoryId);
            }

            if (is_null(request()->input('status'))) {
                $qb->where('product_flat.status', 1);
            }

            /*if (is_null(request()->input('visible_individually'))) {
                $qb->where('product_flat.visible_individually', 1);
            }*/

            if (isset($params['search']) || isset($params['term'])) {
                $term = $params['search'] ?? $params['term'];
                $qb->where(function ($query) use($term) {
                    $query->where('product_flat.name', 'like', '%' . urldecode($term) . '%')
                        ->orWhere('product_flat.sku', 'like', '%' . urldecode($term) . '%')
                        ->orWhere('product_flat.short_description', 'like', '%' . urldecode($term) . '%')
                        ->orWhere('product_flat.description', 'like', '%' . urldecode($term) . '%');
                });
            }

            # sort direction
            $orderDirection = 'asc';
            if( isset($params['order']) && in_array($params['order'], ['desc', 'asc']) ){
                $orderDirection = $params['order'];
            }
            $qb->orderByRaw('CASE WHEN product_flat.quantity = 0 THEN 1 END');
            if (isset($params['sort'])) {
                $attribute = $this->attributeRepository->findOneByField('code', $params['sort']);

                if ($attribute) {
                    if ($attribute->code == 'price') {
                        $qb->orderBy('min_price', $orderDirection);
                    } else {
                        $qb->orderBy($params['sort'] == 'created_at' ? 'product_flat.created_at' : $attribute->code, $orderDirection);
                    }
                }
            }

            if ( $priceFilter = request('price') ){
                $priceRange = explode(',', $priceFilter);
                if( count($priceRange) > 0 ) {
                    $qb->where('variants.min_price', '>=', core()->convertToBasePrice($priceRange[0]));
                    $qb->where('variants.min_price', '<=', core()->convertToBasePrice(end($priceRange)));
                }
            }

            $attributeFilters = $this->attributeRepository
                ->getProductDefaultAttributes(array_keys(
                    request()->except(['price'])
                ));

            if ( count($attributeFilters) > 0 ) {
                $qb->where(function ($filterQuery) use($attributeFilters){

                    foreach ($attributeFilters as $attribute) {
                        $filterQuery->orWhere(function ($attributeQuery) use ($attribute) {

                            $column = 'product_attribute_values.' . ProductAttributeValueProxy::modelClass()::$attributeTypeFields[$attribute->type];

                            $filterInputValues = explode(',', request()->get($attribute->code));

                            # define the attribute we are filtering
                            $attributeQuery = $attributeQuery->where('product_attribute_values.attribute_id', $attribute->id);

                            # apply the filter values to the correct column for this type of attribute.
                            if ($attribute->type != 'price') {

                                $attributeQuery->where(function ($attributeValueQuery) use ($column, $filterInputValues) {
                                    foreach ($filterInputValues as $filterValue) {
                                        if (!is_numeric($filterValue)) {
                                            continue;
                                        }
                                        $attributeValueQuery->orWhereRaw("find_in_set(?, {$column})", [$filterValue]);
                                    }
                                });

                            } else {
                                $attributeQuery->where($column, '>=', core()->convertToBasePrice(current($filterInputValues)))
                                    ->where($column, '<=', core()->convertToBasePrice(end($filterInputValues)));
                            }
                        });
                    }

                });

                # this is key! if a product has been filtered down to the same number of attributes that we filtered on,
                # we know that it has matched all of the requested filters.
                $qb->groupBy('variants.id');
                $qb->havingRaw('COUNT(*) = ' . count($attributeFilters));
            }


            return $qb->groupBy('product_flat.id');

        });

        # apply scope query so we can fetch the raw sql and perform a count
        $repository->applyScope();
        $countQuery = "select count(*) as aggregate from ({$repository->model->toSql()}) c";
        $count = collect(DB::select($countQuery, $repository->model->getBindings()))->pluck('aggregate')->first();

        if ($count > 0) {
            # apply a new scope query to limit results to one page
            $repository->scopeQuery(function ($query) use ($page, $perPage) {
                return $query->forPage($page, $perPage);
            });

            # manually build the paginator
            $items = $repository->get();
        } else {
            $items = [];
        }

        return new LengthAwarePaginator($items, $count, $perPage, $page, [
            'path'  => request()->url(),
            'query' => request()->query()
        ]);
    }

    public function getAllWithPagination($categoryId, $paginate)
    {

        return app(ProductFlatRepository::class)->scopeQuery(function($query) use($categoryId) {
            $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());
            $locale = request()->get('locale') ?: app()->getLocale();
            $select = ['product_flat.id','product_flat.sku','product_flat.name','product_flat.url_key','product_flat.new','product_flat.featured','product_flat.status','product_flat.thumbnail','product_flat.weight','product_flat.created_at','product_flat.locale','product_flat.channel','product_flat.product_id','product_flat.updated_at','product_flat.parent_id','product_flat.visible_individually','product_flat.min_price','product_flat.max_price','product_flat.short_description','product_flat.meta_title','product_flat.meta_keywords','product_flat.show_on_marketplace' ,'product_flat.marketplace_seller_id'];
            if ($categoryId > 0) {
                array_push($select, 'product_categories.category_id');
            }
            $query = $query->select($select);

            $query->addSelect(DB::raw('IF( product_flat.special_price_from IS NOT NULL
                            AND product_flat.special_price_to IS NOT NULL , IF( NOW( ) >= product_flat.special_price_from AND NOW( ) <= product_flat.special_price_to, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) , IF( product_flat.special_price_from IS NULL , IF( product_flat.special_price_to IS NULL , IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , IF( NOW( ) <= product_flat.special_price_to, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) ) , IF( product_flat.special_price_to IS NULL , IF( NOW( ) >= product_flat.special_price_from, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) , product_flat.price ) ) ) AS price'));

            $query->addSelect(DB::raw('product_flat.description AS description'));

            if ($categoryId > 0) {
                $query->leftJoin('product_categories', 'product_categories.product_id', '=', 'product_flat.product_id');
            }
            $query->where('product_flat.locale', $locale)
                ->where('product_flat.status', 1)
                /*->where('product_flat.is_seller_approved',1)*/
                ->whereNull('product_flat.parent_id')
                ->whereNotNull('product_flat.url_key')
                ->where(function ($query) use($channel) {
                    $query->where('product_flat.channel', $channel);
                    if ($channel == config('app.defaultChannel')) {
                        $query->orWhere('product_flat.show_on_marketplace', '1');
                    }
                });

            if ($categoryId > 0) {
                $query->where('product_categories.category_id', '=', $categoryId);
            }

            return $query->orderByRaw('CASE WHEN product_flat.quantity = 0 THEN 1 END')
                ->orderBy('product_flat.updated_at', 'desc');
        })->paginate($paginate);
    }

    /**
     * Retrive product from slug
     *
     * @param string $slug
     * @param null $columns
     * @return Product
     */
    public function findBySlugOrFail(string $slug, $columns = null)
    {
        $product = app(ProductFlatRepository::class)->findOneWhere([
            'url_key' => $slug,
            'status' => 1,
            'locale'  => app()->getLocale(),
            'channel' => core()->getCurrentChannelCode(),
        ]);

        if (! $product) {
            throw (new ModelNotFoundException)->setModel(
                get_class($this->model), $slug
            );
        }

        return $product;
    }

    /**
     * Retrieve product from slug without throwing an exception (might return null)
     *
     * @param string $slug
     * @return ProductFlat
    */
    public function findBySlug(string $slug)
    {
        return app(ProductFlatRepository::class)->scopeQuery(function($query) use ($slug) {
            return $query->distinct()
                ->addSelect('product_flat.*')
                ->where('product_flat.url_key', $slug)
                ->where('product_flat.status', 1)
                ->where('product_flat.locale', app()->getLocale())
                ->where(function ($query) {
                    $query->where('product_flat.channel', core()->getCurrentChannelCode());
                    if (core()->getCurrentChannelCode() == config('app.defaultChannel')) {
                        $query->orWhere('product_flat.show_on_marketplace', '1');
                    }
                });
        })->first();
    }

    /**
     * Returns newly added product
     *
     * @return Collection
     */
    public function getNewProducts()
    {
        return app(ProductFlatRepository::class)->scopeQuery(function($query) {
            $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

            $locale = request()->get('locale') ?: app()->getLocale();

            return $query->distinct()
                            ->addSelect('product_flat.*')
                            ->where('product_flat.status', 1)
                            /*->where('product_flat.visible_individually', 1)*/
                            ->where('product_flat.new', 1)
                            ->where('product_flat.channel', $channel)
                            ->where('product_flat.locale', $locale)
                            ->orderBy('updated_at', 'desc');
        })->paginate(4);
    }

    /**
     * Returns featured product
     *
     * @return Collection
     */
    public function getFeaturedProducts()
    {
        return app(ProductFlatRepository::class)->scopeQuery(function($query) {
            $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

            $locale = request()->get('locale') ?: app()->getLocale();

            return $query->distinct()
                            ->addSelect('product_flat.*')
                            ->where('product_flat.status', 1)
                            /*->where('product_flat.visible_individually', 1)*/
                            ->where('product_flat.featured', 1)
                            ->where('product_flat.channel', $channel)
                            ->where('product_flat.locale', $locale)
                            ->orderBy('updated_at', 'desc');
        })->paginate(4);
    }

    /**
     * Search Product by Attribute
     *
     * @param string $term
     * @return Collection
     */
    public function searchProductByAttribute(string $term)
    {
        return app(ProductFlatRepository::class)->scopeQuery(function($query) use($term) {
            $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

            $locale = request()->get('locale') ?: app()->getLocale();

            return $query->distinct()
                            ->addSelect('product_flat.*')
                            ->where('product_flat.status', 1)
                            /*->where('product_flat.visible_individually', 1)*/
                            ->where('product_flat.channel', $channel)
                            ->where('product_flat.locale', $locale)
                            ->whereNotNull('product_flat.url_key')
                            ->where('product_flat.name', 'like', '%' . urldecode($term) . '%')
                            ->orderBy('product_id', 'desc');
        })->paginate(16);
    }

    /**
     * Returns product's super attribute with options
     *
     * @param Product $product
     * @return array
     */
    public function getSuperAttributes(Product $product)
    {
        $superAttrbutes = [];

        foreach ($product->super_attributes as $key => $attribute) {

            $superAttrbutes[$key] = $attribute->toArray();
            unset($superAttrbutes[$key]['pivot']);
            $result=DB::select(' select `selected_attribute_options` from `product_super_attributes` where `product_id` = '.$product->id.' and `attribute_id` ='.$attribute->id);
            $selected_attribute_options='';
            if(isset($result[0]->selected_attribute_options)){
                $selected_attribute_options=$result[0]->selected_attribute_options;
            }
            $superAttrbutes[$key]['selected_attribute_options']=json_decode($selected_attribute_options);
            foreach ($attribute->options as $option) {
                $superAttrbutes[$key]['options'][] = [
                    'id'           => $option->id,
                    'admin_name'   => $option->admin_name,
                    'sort_order'   => $option->sort_order,
                    'swatch_value' => $option->swatch_value,
                ];
            }
        }

        return $superAttrbutes;
    }

    /**
     * Search simple products for grouped product association
     *
     * @param string $term
     * @return Collection
     */
    public function searchSimpleProducts(string $term)
    {
        return app(ProductFlatRepository::class)->scopeQuery(function($query) use($term) {
            $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

            $locale = request()->get('locale') ?: app()->getLocale();

            return $query->distinct()
                         ->addSelect('product_flat.*')
                         ->addSelect('product_flat.product_id as id')
                         ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
                         ->where('products.type', 'simple')
                         ->where('product_flat.channel', $channel)
                         ->where('product_flat.status', 1)
                         ->where('product_flat.locale', $locale)
                         ->where('product_flat.name', 'like', '%' . urldecode($term) . '%')
                         ->orderBy('product_id', 'desc');
        })->get();
    }

    public function sortCategories($categories){

        $selected_categories = [];

        $i = 0;
        foreach ($categories as $key => $category) {
            if ($category->parent_id == 64) {
                $selected_categories[$i]['category'] = $category->id;
                $i += 1;
            } else {

                $cats = array_column($selected_categories, 'category');
                if (in_array($category->parent_id, $cats)) {
                    foreach ($cats as $index => $cat) {
                        if ($cat == $category->parent_id) {
                            $selected_categories[$index]['subcategory'] = $category->id;
                        }
                    }
                }
                $subcats = array_column($selected_categories, 'subcategory');
                if (in_array($category->parent_id, $subcats)) {
                    foreach ($subcats as $index => $subcat) {
                        if ($subcat == $category->parent_id) {
                            $selected_categories[$index]['subsubcategory'] = $category->id;
                        }
                    }
                }

            }
        }

        return $selected_categories;
    }

    public function formatCreateApiRequest($request_data){


      if(!isset($request_data['sku'])){
          $seller =app('Webkul\Marketplace\Repositories\SellerRepository')->findOneByField('customer_id', auth()->guard('customer')->user()->id);
          $request_data['sku']=$seller->id .'-'.$request_data['type'].'-'.date("His");
      }

      if(isset($request_data['super_attributes'])){
          $attributes=$this->attributeRepository->findWhereIn('type',['select','multiselect']);
          foreach ($attributes as $key =>$attribute){
              if(isset($request_data['super_attributes'][$attribute->code])){
                  $request_data['super_attributes'][$attribute->code]=$this->getAttributeOptionId($attribute, $request_data['super_attributes'][$attribute->code]);
              }
          }
      }

      return $request_data;
    }

    public function getFormattedApiRequest($request_data)
    {
        $attributes=$this->attributeRepository->findWhere([['code','In',array_keys($request_data)],['type','In',['select','multiselect']]]);

        foreach ($attributes as $key =>$attribute){
            if(isset($request_data[$attribute->code])){
                $request_data[$attribute->code]=$this->getAttributeOptionId($attribute, $request_data[$attribute->code]);
            }
        }
        if(isset($request_data["categories"])){
            $request_data["categories"]=$this->validateProductCategories($request_data["categories"]);

        }
        if(isset($request_data['variants'])){
            foreach ($request_data['variants'] as $key => $variant){
                foreach ($variant as $index => $value) {
                    if($index!='sku' && $index!='name' && $index!='price' && $index!='weight' && $index !='status' && $index !='inventories'){
                        $attribute= $this->attributeRepository->findWhere(['code' => $index ])->first();
                        if($attribute){
                            $variant[$index]=$this->getAttributeOptionId($attribute,$value,1);
                        }
                    }
                }
                $request_data['variants'][$key]=$variant;
            }
        }
        $request_data["channel"] = "devvlystore";
        $request_data["locale"] = "en";

      return $request_data;
    }
    public function getAttributeOptionId($attribute, $value,$is_variant=0){



        if ($attribute->type == 'multiselect') {
            if(!is_array($value)){
                $multiAttributesOptions = explode(',', $value);
            }else{
                $multiAttributesOptions = $value;
            }

            $arr = array();
            foreach ($multiAttributesOptions as $attribute_option) {
                $attribute_option = rtrim(ltrim($attribute_option));
                $attributeOption = AttributeOption::where(['admin_name' => $attribute_option], ['attribute_id' => $attribute->id])->get();
                if ($attributeOption->count() > 0) {
                    $attributeOptionId = $attributeOption->first()->id;
                }
                array_push($arr, $attributeOptionId);
            }
            if(!is_array($value)){
                return implode(',',$arr);
            }else{
                return $arr;
            }
        }
        if ($attribute->type == 'select') {
            //below if condition is used on create api for the case of configurable product
           if($is_variant){
               if(is_array($value)){
                   $arr = array();
                   foreach ($value as $attribute_option) {
                       $attribute_option = rtrim(ltrim($attribute_option));
                       $attributeOption = AttributeOption::where(['admin_name' => $attribute_option], ['attribute_id' => $attribute->id])->get();

                       if ($attributeOption->count() > 0) {
                           $attributeOptionId = $attributeOption->first()->id;
                       }
                       array_push($arr, $attributeOptionId);
                   }
                   return  $arr;
               }
           }

            $attributeOptions = AttributeOption::where(['admin_name' => $value])->where( ['attribute_id' => $attribute->id])->get();
            if ($attributeOptions->count() > 0) {
                $attributeOption = $attributeOptions->first();
                if (isset($attributeOption->id)) {
                    return $attributeOption->id;
                }
            }
        }
        return '';
    }
    public function validateProductCategories($categories){
        $cats=[];
        if(is_array($categories)){
            foreach ($categories as $key => $category){

                $check_category = DB::select("select ca.id,ca.parent_id from categories ca inner join category_translations ct on ca.id=ct.category_id  where lower(ct.name) = lower('" . $category . "')");
                if (!isset($check_category[0])) {
                    continue;
                }else{
                    if($check_category[0]->parent_id){
                          if(!in_array($check_category[0]->id,$cats)){
                              array_push($cats,$check_category[0]->id);
                          }
                        $first_level_parent_category = DB::select("select ca.id,ca.parent_id from categories ca where id = " . $check_category[0]->parent_id);
                        if(!in_array($first_level_parent_category[0]->id,$cats)){
                            array_push($cats,$first_level_parent_category[0]->id);
                        }
                        if($first_level_parent_category[0]->parent_id){
                             $second_level_parent_category = DB::select("select ca.id,ca.parent_id from categories ca where id = " . $first_level_parent_category[0]->parent_id);
                         if(isset($second_level_parent_category[0])){
                             if(!in_array($second_level_parent_category[0]->id,$cats)){
                                 array_push($cats,$second_level_parent_category[0]->id);
                             }
                         }
                        }

                    }
                }
            }
        }

        return $cats;
    }
    public function getProductInventory($product_id,$seller_id){
        $product=$this->find($product_id);
        return $product->product->totalQuantity($seller_id);
    }
  public function updateProductInventory($quantity,$product_id,$seller_id)
  {
      $product = $this->find($product_id);
      return $product->product->updateProductInventory($quantity, $seller_id);
  }

    public function sendWebhook($inventory,$seller_id,$product_id)
    {


        $product = 'product';
        $seller=app('Webkul\Marketplace\Repositories\SellerRepository')->find($seller_id);
        $product=$this->find($product_id);
        $url = $seller->webhooks;
        $data = [
            'status_code' => 200,
            'status' => 'success',
            'inventory' =>  $inventory,
            'product' =>  $product
        ];
        $json_array = json_encode($data);
        if($url){
            $curl = curl_init();
            $headers = ['Content-Type: application/json'];

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json_array);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HEADER, 1);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($curl);
            $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            curl_close($curl);

            if ($http_code >= 200 && $http_code < 300) {
                return response()->json([
                    'message' => 'webhook sent successfully.'
                ]);
            } else {
                return response()->json([
                    'message' => 'webhook failed.'
                ]);
            }
        }
        return response()->json([
            'message' => 'webhook failed.'
        ]);
    }

}
