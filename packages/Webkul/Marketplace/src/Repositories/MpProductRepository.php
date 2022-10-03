<?php

namespace Webkul\Marketplace\Repositories;

use Carbon\Carbon;
use Exception;
use Illuminate\Container\Container as App;
use DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Webkul\Attribute\Helper\AttributesOptionsProductsAmountHelper;
use Webkul\Core\Eloquent\Repository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Marketplace\Service\SellerType;
use Webkul\Product\Models\ProductAttributeValue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Storage;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Repositories\ProductVideoRepository;
use function Webmozart\Assert\Tests\StaticAnalysis\length;

/**
 * Product Repository
 *
 * @author    Mohamad Kabalan <mohamad@devvly.com>
 * @copyright 2021  http://www.devvly.com)
 */
class MpProductRepository extends Repository
{
    /**
     * AttributeRepository object
     *
     * @var array
     */
    protected $attribute;

    /**
     * AttributeOptionRepository object
     *
     * @var array
     */
    protected $attributeOption;

    /**
     * ProductAttributeValueRepository object
     *
     * @var array
     */
    protected $attributeValue;



    /**
     * ProductImageRepository object
     *
     * @var array
     */
    protected $productImage;

    /**
     * ProductImageRepository object
     *
     * @var string
     */
    protected $productVideo;

    /**
     * Create a new controller instance.
     *
     *
     * @param AttributeRepository $attribute
     * @param AttributeOptionRepository $attributeOption
     * @param ProductAttributeValueRepository $attributeValue
     * @param ProductImageRepository $productImage
     * @param ProductVideoRepository $productVideo
     * @param App $app
     */
    public function __construct(
        AttributeRepository $attribute,
        AttributeOptionRepository $attributeOption,
        ProductAttributeValueRepository $attributeValue,
        ProductImageRepository $productImage,
        ProductVideoRepository $productVideo,
        App $app)
    {
        $this->attribute = $attribute;

        $this->attributeOption = $attributeOption;

        $this->attributeValue = $attributeValue;

        $this->productImage = $productImage;

        $this->productVideo = $productVideo;

        parent::__construct($app);
    }

    /**->where('product_flat.visible_individually', 1)
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Product\Contracts\Product';
    }

    /**
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function create(array $data)
    {
        //before store of the product
        Event::dispatch('catalog.marketplace.product.create.before');

        $product = $this->model->create($data);

        $nameAttribute = $this->attribute->findOneByField('code', 'status');
        $this->attributeValue->create([
                'product_id' => $product->id,
                'attribute_id' => $nameAttribute->id,
                'value' => 0
            ]);

        if (isset($data['super_attributes'])) {
            $super_attributes = [];

            foreach ($data['super_attributes'] as $attributeCode => $attributeOptions) {
                $attribute = $this->attribute->findOneByField('code', $attributeCode);

                $super_attributes[$attribute->id] = $attributeOptions;

                $product->super_attributes()->attach($attribute->id,['selected_attribute_options' => json_encode($attributeOptions)]);
            }

            foreach (array_permutation($super_attributes) as $permutation) {
                $this->createVariant($product, $permutation);
            }
        }

        //after store of the product
        Event::dispatch('catalog.marketplace.product.create.after', $product);

        return $product;
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {

        $data['locale'] = app()->getLocale();

        Event::dispatch('catalog.marketplace.product.update.before', $id);

        $product = $this->find($id);

        (new AttributesOptionsProductsAmountHelper($product, $data))->execute();
        if ($product->parent_id && $this->checkVariantOptionAvailabiliy($data, $product)) {
            $data['parent_id'] = NULL;
        }


        $attributes = $product->attribute_family->custom_attributes;


        foreach ($attributes as $attribute) {

            if ($attribute->type == 'multiselect') {
                if(!isset($data[$attribute->code])){
                    $data[$attribute->code] = [];
                }
            }
            if($attribute->type=='boolean'){
                if(!isset($data[$attribute->code])){
                    $data[$attribute->code] = 0;
                }
            }
        }

        $product->update($data);



        foreach ($attributes as $attribute) {
            if (! isset($data[$attribute->code]) || (in_array($attribute->type, ['date', 'datetime']) && ! $data[$attribute->code]))
                continue;
            if ($attribute->type == 'multiselect' || $attribute->type == 'checkbox') {
                  if(!empty($data[$attribute->code])){
                      if(is_array($data[$attribute->code])){
                          $data[$attribute->code] =implode(",", $data[$attribute->code]);
                      }
                  }else{
                      $data[$attribute->code]='';
                  }

            }

            if ($attribute->type == 'image' || $attribute->type == 'file') {
                $dir = 'product';
                if (gettype($data[$attribute->code]) == 'object') {
                    $data[$attribute->code] = request()->file($attribute->code)->store($dir);
                } else {
                    $data[$attribute->code] = NULL;
                }
            }

            $attributeValue = $this->attributeValue->findOneWhere([
                    'product_id' => $product->id,
                    'attribute_id' => $attribute->id
                ]);

            if (! $attributeValue) {
                $this->attributeValue->create([
                    'product_id' => $product->id,
                    'attribute_id' => $attribute->id,
                    'value' => $data[$attribute->code]
                ]);
            } else {
                $this->attributeValue->update([
                    ProductAttributeValue::$attributeTypeFields[$attribute->type] => $data[$attribute->code]
                    ], $attributeValue->id
                );

                if ($attribute->type == 'image' || $attribute->type == 'file') {
                    Storage::delete($attributeValue->text_value);
                }
            }
        }

        $route = request()->route() ? request()->route()->getName() : "";

        if ($route != 'admin.catalog.products.massupdate') {
            if  (isset($data['categories'])) {
                $product->categories()->sync($data['categories']);
            }

            if (isset($data['up_sell'])) {
                $product->up_sells()->sync($data['up_sell']);
            } else {
                $data['up_sell'] = [];
                $product->up_sells()->sync($data['up_sell']);
            }

            if (isset($data['cross_sell'])) {
                $product->cross_sells()->sync($data['cross_sell']);
            } else {
                $data['cross_sell'] = [];
                $product->cross_sells()->sync($data['cross_sell']);
            }

            if (isset($data['related_products'])) {
                $product->related_products()->sync($data['related_products']);
            } else {
                $data['related_products'] = [];
                $product->related_products()->sync($data['related_products']);
            }

            $previousVariantIds = $product->variants->pluck('id');

            if (isset($data['variants'])) {
                foreach ($data['variants'] as $variantId => $variantData) {
                    if (str_contains($variantId, 'variant_')) {
                        $permutation = [];
                        foreach ($product->super_attributes as $superAttribute) {
                            $permutation[$superAttribute->id] = $variantData[$superAttribute->code];
                        }

                        $this->createVariant($product, $permutation, $variantData);
                    } else {
                        if (is_numeric($index = $previousVariantIds->search($variantId))) {
                            $previousVariantIds->forget($index);
                        }

                        $variantData['channel'] = $data['channel'];
                        $variantData['locale'] = $data['locale'];
                        //check free shipping for configurable product
                        if($product->type=='configurable'){
                            $variantData['free_shipping']=0;
                            if(isset($data['free_shipping'])){
                                if($data['free_shipping']==1){
                                    $variantData['free_shipping']=1;
                                }
                            }
                        }

                        $this->updateVariant($variantData, $variantId);
                    }
                }
            }

            foreach ($previousVariantIds as $variantId) {
                $this->delete($variantId);
            }


            $this->productImage->uploadImages($data, $product);

            $this->productVideo->updateVideo($data, $product);
        }

        if (isset($data['channels'])) {
            $product['channels'] = $data['channels'];
        }




        Event::dispatch('catalog.marketplace.product.update.after', $product);

        return $product;
    }
    public function uploadProductImages($data){

        if(isset($data['product_id'])){
            $product=$this->find($data['product_id']);
            $data['images']=[];
            for($i=1;$i<$data['nb_of_images'] + 1;$i++){
                $data['images']['image_'.$i]=$data['image_'.$i];
            }
        }elseif(isset($data['sku'])){
            $product=$this->findWhere(['sku'=>$data['sku']])->first();
            $data['images']=[];
            for($i=1;$i<$data['nb_of_images'] + 1;$i++){
                $data['images']['image_'.$i]=$data['image_'.$i];
            }
        }else{
            return array('status'=>'failed');
        }


        $this->productImage->uploadImages($data, $product);
        return $product->images()->get()->toArray();
    }
    public function sortImagesOrder($data){
        return $this->productImage->sortImagesOrder($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        Event::dispatch('catalog.marketplace.product.delete.before', $id);

        parent::delete($id);

        Event::dispatch('catalog.marketplace.product.delete.after', $id);
    }

    public function creatNameByPermutation($permutations){
        $name='';
        $i=0;
        foreach ($permutations as $permutation){
         $option=   $this->attributeOption->find($permutation);
         if($i==sizeof($permutations ) -1){
             $name=$name.$option->admin_name;
         }else{
             $name=$name.$option->admin_name.'-';
         }

        $i++;
        }
     return $name;
    }

    /**
     * @param mixed $product
     * @param array $permutation
     * @param array $data
     * @return mixed
     */
    public function createVariant($product, $permutation, $data = [])
    {

        if (! count($data)) {
            $data = [
                    "sku" => $product->sku . '-variant-' . implode('-', $permutation),
                    "name" => $this->creatNameByPermutation($permutation),
                    "quantity" => 0,
                    "price" => 0,
                    "weight" => 0,
                    "status" => 1
                ];
        }

        $variant = $this->model->create([
                'parent_id' => $product->id,
                'type' => 'simple',
                'attribute_family_id' => $product->attribute_family_id,
                'sku' => $data['sku'],
            ]);

        foreach (['sku', 'name', 'price', 'weight', 'status'] as $attributeCode) {
            $attribute = $this->attribute->findOneByField('code', $attributeCode);

            if ($attribute->value_per_channel) {
                if ($attribute->value_per_locale) {
                    foreach (core()->getAllChannels() as $channel) {
                        foreach (core()->getAllLocales() as $locale) {
                            $this->attributeValue->create([
                                    'product_id' => $variant->id,
                                    'attribute_id' => $attribute->id,
                                    'value' => $data[$attributeCode]
                                ]);
                        }
                    }
                } else {
                    foreach (core()->getAllChannels() as $channel) {
                        $this->attributeValue->create([
                                'product_id' => $variant->id,
                                'attribute_id' => $attribute->id,
                                'value' => $data[$attributeCode]
                            ]);
                    }
                }
            } else {
                if ($attribute->value_per_locale) {
                    foreach (core()->getAllLocales() as $locale) {
                        $this->attributeValue->create([
                                'product_id' => $variant->id,
                                'attribute_id' => $attribute->id,
                                'value' => $data[$attributeCode]
                            ]);
                    }
                } else {
                    $this->attributeValue->create([
                            'product_id' => $variant->id,
                            'attribute_id' => $attribute->id,
                            'value' => $data[$attributeCode]
                        ]);
                }
            }
        }
        foreach ($permutation as $attributeId => $optionId) {
            $this->attributeValue->create([
                    'product_id' => $variant->id,
                    'attribute_id' => $attributeId,
                    'value' => $optionId
                ]);
        }



        return $variant;
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function updateVariant(array $data, $id)
    {
        $variant = $this->find($id);

        $variant->update($data);

        foreach (['sku', 'name', 'price', 'weight', 'status','free_shipping'] as $attributeCode) {
            $attribute = $this->attribute->findOneByField('code', $attributeCode);

            $attributeValue = $this->attributeValue->findOneWhere([
                    'product_id' => $id,
                    'attribute_id' => $attribute->id
                ]);
/*            'channel' => $attribute->value_per_channel ? $data['channel'] : null,
                    'locale' => $attribute->value_per_locale ? $data['locale'] : null*/
            if (! $attributeValue) {
                $this->attributeValue->create([
                        'product_id' => $id,
                        'attribute_id' => $attribute->id,
                        'value' => $data[$attribute->code]
                    ]);
            } else {
                $this->attributeValue->update([
                    ProductAttributeValue::$attributeTypeFields[$attribute->type] => $data[$attribute->code]
                ], $attributeValue->id);
            }
        }

        return $variant;
    }

    public function validateVariants($data,$product_id){

        $variants_array=[];
        $product=$this->findOrFail($product_id);

        if(isset($data['super_attributes'])){
           $super_attributes=json_decode($data['super_attributes'], true);
           foreach ($super_attributes as $super_attribute){
               $product->super_attributes()->updateExistingPivot($super_attribute['id'],['selected_attribute_options'=>$super_attribute['selected_attribute_options']]);
           }
        }
        if(isset($data['formatted_variants'])){
            $variants=json_decode($data['formatted_variants'], true);
            foreach ($variants as $i => $variant){

                if(!isset($variant['id'])){

                    $variant_data = [
                        "sku" => $variant['sku'],
                        "name" => $variant['name'],
                        "price" => $variant['price'],
                        "quantity" =>$variant['quantity'] ,
                        "weight" => (isset($data['weight'])) ? $data['weight'] : 0 ,
                        "status" => 1
                    ];

                    $arr1=$variant;
                    $arr2=$variant_data;

                    $variant_attributes=array_diff($arr1,$arr2);
                    foreach ($variant_attributes as $index => $variant_attribute){
                        $attribute = app('Webkul\Attribute\Repositories\AttributeRepository')->findOneByField('code',$index);
                        $permutation[$attribute->id]=$variant_attribute;
                    }

                    if(isset($variant['to_upload_images'])){
                        // upload code here
                    }

                    $created_variant=$this->createVariant($product, $permutation, $variant_data );
                    if($created_variant){
                        $variants_array[$created_variant->id]=[
                            "sku" => $created_variant->sku,
                            "name" => $variant['name'],
                            "quantity" => $variant['quantity'] ,
                            "price" => $variant['price'],
                            "weight" =>  (isset($data['weight'])) ? $data['weight'] : 0 ,
                            "status" => 1
                        ];
                        $variants_array[$created_variant->id]=array_merge($variants_array[$created_variant->id],$variant_attributes);
                    }

                }else{
                    if(isset($variant['to_upload_images'])){
                        //upload code here
                    }

                    $variants_array[$variant['id']]=
                        [
                            "sku" => $variant['sku'],
                            "name" => $variant['name'],
                            "quantity" => $variant['quantity'],
                            "price" => $variant['price'],
                            "weight" =>  (isset($data['weight'])) ? $data['weight'] : 0 ,
                            "status" => 1
                        ];

                }
            }
        }

       return $variants_array;
    }

    /**
     * @param array $data
     * @param mixed $product
     * @return mixed
     */
    public function checkVariantOptionAvailabiliy($data, $product)
    {
        $parent = $product->parent;

        $superAttributeCodes = $parent->super_attributes->pluck('code');

        $isAlreadyExist = false;

        foreach ($parent->variants as $variant) {
            if ($variant->id == $product->id)
                continue;

            $matchCount = 0;

            foreach ($superAttributeCodes as $attributeCode) {
                if (! isset($data[$attributeCode]))
                    return false;

                if ($data[$attributeCode] == $variant->{$attributeCode})
                    $matchCount++;
            }

            if ($matchCount == $superAttributeCodes->count()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param integer $categoryId
     * @return Collection
     */
    public function getAll($categoryId = null)
    {
        $params = request()->input();

        $results = app('Webkul\Product\Repositories\ProductFlatRepository')->scopeQuery(function($query) use($params, $categoryId) {
                $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

                $locale = request()->get('locale') ?: app()->getLocale();

                $qb = $query->distinct()
                        ->addSelect('product_flat.*')
                        ->addSelect(DB::raw('IF( product_flat.special_price_from IS NOT NULL
                            AND product_flat.special_price_to IS NOT NULL , IF( NOW( ) >= product_flat.special_price_from
                            AND NOW( ) <= product_flat.special_price_to, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) , IF( product_flat.special_price_from IS NULL , IF( product_flat.special_price_to IS NULL , IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , IF( NOW( ) <= product_flat.special_price_to, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) ) , IF( product_flat.special_price_to IS NULL , IF( NOW( ) >= product_flat.special_price_from, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) , product_flat.price ) ) ) AS final_price'))

                        ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
                        ->leftJoin('product_categories', 'products.id', '=', 'product_categories.product_id')
                        ->where('product_flat.channel', $channel)
                        ->where('product_flat.locale', $locale)
                        ->whereNotNull('product_flat.url_key');

                if ($categoryId) {
                    $qb->where('product_categories.category_id', $categoryId);
                }

                if (is_null(request()->input('status'))) {
                    $qb->where('product_flat.status', 1);
                }

          /*      if (is_null(request()->input('visible_individually'))) {
                    $qb->where('product_flat.visible_individually', 1);
                }*/

                $queryBuilder = $qb->leftJoin('product_flat as flat_variants', function($qb) use($channel, $locale) {
                    $qb->on('product_flat.id', '=', 'flat_variants.parent_id')
                        ->where('flat_variants.channel', $channel)
                        ->where('flat_variants.locale', $locale);
                });

                if (isset($params['search'])) {
                    $qb->where('product_flat.name', 'like', '%' . urldecode($params['search']) . '%');
                }

                if (isset($params['sort'])) {
                    $attribute = $this->attribute->findOneByField('code', $params['sort']);

                    if ($params['sort'] == 'price') {
                        if ($attribute->code == 'price') {
                            $qb->orderBy('final_price', $params['order']);
                        } else {
                            $qb->orderBy($attribute->code, $params['order']);
                        }
                    } else {
                        $qb->orderBy($params['sort'] == 'created_at' ? 'product_flat.created_at' : $attribute->code, $params['order']);
                    }
                }

                $qb = $qb->leftJoin('products as variants', 'products.id', '=', 'variants.parent_id');

                $qb = $qb->where(function($query1) use($qb) {
                    $aliases = [
                            'products' => 'filter_',
                            'variants' => 'variant_filter_'
                        ];

                    foreach($aliases as $table => $alias) {
                        $query1 = $query1->orWhere(function($query2) use($qb, $table, $alias) {

                            foreach ($this->attribute->getProductDefaultAttributes(array_keys(request()->input())) as $code => $attribute) {
                                $aliasTemp = $alias . $attribute->code;

                                $qb = $qb->leftJoin('product_attribute_values as ' . $aliasTemp, $table . '.id', '=', $aliasTemp . '.product_id');

                                $column = ProductAttributeValue::$attributeTypeFields[$attribute->type];

                                $temp = explode(',', request()->get($attribute->code));

                                if ($attribute->type != 'price') {
                                    $query2 = $query2->where($aliasTemp . '.attribute_id', $attribute->id);

                                    $query2 = $query2->where(function($query3) use($aliasTemp, $column, $temp) {
                                        foreach($temp as $code => $filterValue) {
                                            $columns = $aliasTemp . '.' . $column;
                                            $query3 = $query3->orwhereRaw("find_in_set($filterValue, $columns)");
                                        }
                                    });
                                } else {
                                    $query2 = $query2->where($aliasTemp . '.' . $column, '>=', core()->convertToBasePrice(current($temp)))
                                            ->where($aliasTemp . '.' . $column, '<=', core()->convertToBasePrice(end($temp)))
                                            ->where($aliasTemp . '.attribute_id', $attribute->id);
                                }
                            }
                        });
                    }
                });

                return $qb->groupBy('product_flat.id');
            })->paginate(isset($params['limit']) ? $params['limit'] : 12);

        return $results;
    }

    /**
     * Retrive product from slug
     *
     * @param string $slug
     * @return mixed
     */
    public function findBySlugOrFail($slug, $columns = null)
    {
        $product = app('Webkul\Product\Repositories\ProductFlatRepository')->findOneWhere([
                'url_key' => $slug,
                'locale' => app()->getLocale(),
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
     * Returns newly added product
     *
     * @return Collection
     */
    public function getNewProducts($count)
    {

        if (str_replace('www.','', parse_url(getenv('APP_URL'))['host']) != str_replace('www.','', parse_url(url()->previous())['host'])) {
            if (Cache::has('getNewProducts')) {
                return Cache::get('getNewProducts');
            }
        }else{
            if (Cache::has('getNewProductsForHomePage')) {
                return Cache::get('getNewProductsForHomePage');
            }
        }

        $results = app('Webkul\Product\Repositories\ProductFlatRepository')->scopeQuery(function ($query) {
            /*                $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

                            $locale = request()->get('locale') ?: app()->getLocale();*/

            $query = $query->distinct()
                ->addSelect('product_flat.*',  'product_flat.marketplace_seller_id', 'marketplace_sellers.is_verified')
                ->join('products', 'products.id', '=', 'product_flat.product_id')
                ->join('marketplace_sellers', 'product_flat.marketplace_seller_id', '=', 'marketplace_sellers.id')
                ->where('marketplace_sellers.is_verified', 1)
                ->where('product_flat.status', 1)
                ->whereRaw('products.parent_id is null');
            if (str_replace('www.','', parse_url(getenv('APP_URL'))['host']) != str_replace('www.','', parse_url(url()->previous())['host'])) {
                $query->where('product_flat.created_at', '>', \Carbon\Carbon::now()->addMonths(-1));
            }
            /*->where('product_flat.channel', $channel)*/ /*->where('product_flat.visible_individually', 1)*/ /*->where('product_flat.new', 1)*/
            /*->where('product_flat.locale', $locale)*/
            $query->whereRaw('(IF(products.type != "booking" AND products.type != "configurable",  product_flat.quantity - product_flat.ordered_quantity , 1 )) > 0');
            $query->orderBy('product_id', 'desc');


            return $query;
        })->paginate($count);

        $date = Carbon::now();
        $date->addHour();
        if (str_replace('www.','', parse_url(getenv('APP_URL'))['host']) != str_replace('www.','', parse_url(url()->previous())['host'])) {
            Cache::put('getNewProducts', $results, $date);
        }else{
            Cache::put('getNewProductsForHomePage', $results, $date);
        }
        return $results;
    }

    /**
     * Returns featured product
     *
     * @return Collection
     */
    public function getFeaturedProducts($count,$seller_id = -1)
    {

        $results = app('Webkul\Product\Repositories\ProductFlatRepository')->scopeQuery(function($query) use($seller_id) {

                $query = $query->distinct()
                    ->addSelect('product_flat.*','product_flat.marketplace_seller_id' ,'marketplace_sellers.is_verified')
                    ->join('products', 'products.id', '=', 'product_flat.product_id')
                    ->join('marketplace_sellers', 'product_flat.marketplace_seller_id', '=', 'marketplace_sellers.id')
                    ->where('marketplace_sellers.is_verified', 1)
                    ->where('product_flat.status', 1)
                    ->whereRaw('products.parent_id is null')
                    ->whereRaw('((product_flat.special_price IS not NULL AND product_flat.special_price != 0 AND product_flat.special_price_from IS NULL AND product_flat.special_price_to IS NULL ) OR
                                (product_flat.special_price IS not NULL AND product_flat.special_price != 0 AND product_flat.special_price_from IS NOT NULL AND product_flat.special_price_to IS Not NULL AND  NOW( ) >= product_flat.special_price_from  AND NOW( ) <= product_flat.special_price_to ))');
                if($seller_id != -1){
                    $query->where('product_flat.marketplace_seller_id', $seller_id);
                }

            $query->whereRaw('(IF(products.type != "booking" AND products.type != "configurable",  product_flat.quantity - product_flat.ordered_quantity , 1 )) > 0');
                return $query->orderBy('product_id', 'desc');
            })->paginate($count);



        return $results;
    }

    /**
     * Search Product by Attribute
     *
     * @return Collection
     */
    public function searchProductByAttribute($term)
    {
        $results = app('Webkul\Product\Repositories\ProductFlatRepository')->scopeQuery(function($query) use($term) {
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

        return $results;
    }

    /**
     * Returns product's super attribute with options
     *
     * @param Product $product
     * @return Collection
     */
    public function getSuperAttributes($product)
    {

        $superAttrbutes = [];

        foreach ($product->super_attributes as $key => $attribute) {
            $superAttrbutes[$key] = $attribute->toArray();

            foreach ($attribute->options as $option) {
                $superAttrbutes[$key]['options'][] = [
                    'id' => $option->id,
                    'admin_name' => $option->admin_name,
                    'sort_order' => $option->sort_order,
                    'swatch_value' => $option->swatch_value,
                ];
            }
        }
        return $superAttrbutes;
    }

    public function addSuperAttributes(array $data,$product,$auto_generate_variation){
        //check if this product has variant and super attributes , if yes we need to delete them before add new one
        $product->super_attributes()->detach();
        //delete child products / variants
        $childProducts=$this->findwhere(['parent_id'=> $product->id]);
        foreach ($childProducts as $childProduct) {
            $this->delete($childProduct->id);
        }
        //add new super attributes
        if (isset($data['super_attributes'])) {
            $super_attributes = [];
            foreach ($data['super_attributes'] as $attributeCode => $attributeOptions) {
                $attribute = $this->attribute->findOneByField('code', $attributeCode);

                $super_attributes[$attribute->id] = $attributeOptions;

                $product->super_attributes()->attach($attribute->id,['selected_attribute_options' => json_encode($attributeOptions)]);
            }
           // add new variants

                foreach (array_permutation($super_attributes) as $permutation) {
                    $this->createVariant($product, $permutation);
                }

        }
        return true;
    }

    public function generateVariants(array $data, $product)
    {
        if (isset($data['super_attributes'])) {
            $super_attributes = [];
            foreach ($data['super_attributes'] as $attributeCode => $attributeOptions) {
                $attribute = $this->attribute->findOneByField('code', $attributeCode);
                $super_attributes[$attribute->id] = $attributeOptions;
            }
            foreach (array_permutation($super_attributes) as $permutation) {
                $this->createVariant($product, $permutation);
            }
        }
        return true;
    }
    public function removeImage($image_id){
      return  $this->productImage->removeImage($image_id);
    }



}