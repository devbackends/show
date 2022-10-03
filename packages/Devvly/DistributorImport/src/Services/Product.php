<?php

namespace Devvly\DistributorImport\Services;

use Devvly\DistributorImport\Models\DistributorProducts;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Repositories\ProductRepository;

use DB;

class Product
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var ProductImageRepository
     */
    protected $productImageRepository;





    /**
     * Product constructor.
     * @param ProductRepository $productRepository
     * @param ProductImageRepository $productImageRepository
     */
    public function __construct(ProductRepository $productRepository,
                                ProductImageRepository $productImageRepository)
    {
        $this->productImageRepository = $productImageRepository;

        $this->productRepository = $productRepository;
    }

    /**
     * @param $rsr_id
     * @return false|\Webkul\Product\Contracts\Product
     */
    public function create($rsr_id)
    {
        $distributorProduct = $this->getDistributorProduct($rsr_id);

        if ($distributorProduct) {
            $sku=!empty($distributorProduct['upcCode']) ? $distributorProduct['upcCode'] : $rsr_id ;
            // Create product wireframe
            $product = $this->createWireframe($sku, $distributorProduct['familyId'], $distributorProduct['productType']);


            $data=$this->getUpdateOptions($distributorProduct,$rsr_id);

            // Update product
            $product = $this->update($product->id, $data);

            // add product category
            $this->addProductCategory($product->id, $distributorProduct['productCategory']);

            // Upload product image
            if($distributorProduct['image']) {
                try{
                    $this->uploadImage($product, $distributorProduct['image']);
                }catch(\Exception $e){
                }
            }
            return $product;

        }

        return false;
    }

    public function updateProducts($rsr_id)
    {
        $distributorProduct = $this->getDistributorProduct($rsr_id);
        if ($distributorProduct) {
            $sku=!empty($distributorProduct['upcCode']) ? $distributorProduct['upcCode'] : $rsr_id ;
            $product = $this->productRepository->findWhere(['sku' => $sku])->first();
            if($product){

                $data=$this->getUpdateOptions($distributorProduct,$rsr_id);

                // Update product
                $product = $this->update($product->id,$data);

                // add product category
                $this->addProductCategory($product->id, $distributorProduct['productCategory']);

                // Upload product image
                if(sizeof($product->images)==0){
                    if($distributorProduct['image']){
                        try {
                            $this->uploadImage($product, $distributorProduct['image']);
                        }catch (\Exception $e){
                        }
                    }
                }

                return $product;
            }

        }

        return false;


    }

    /**
     * @param $sku
     * @param $familyId
     * @return \Webkul\Product\Contracts\Product
     */
    protected function createWireframe($sku, $familyId, $productType)
    {
        // Collect options
        $options = [
            'type' => $productType,
            'attribute_family_id' => $familyId,
            'sku' => $sku,
        ];

        // Create product wireframe

        $product = $this->productRepository->create($options);

        return $product;
    }

    /**
     * @param $productId
     * @param $options
     * @return \Webkul\Product\Contracts\Product
     */
    protected function update($productId, $options)
    {
        // Update product
        return $this->productRepository->update($options, $productId);
    }

    /**
     * @param $product
     * @param $remoteImageName
     * @return mixed
     */
    protected function uploadImage($product, $remoteImageName)
    {
        $imageService = app('Devvly\DistributorImport\Services\Image');

        return $imageService->execute($remoteImageName, $product->id);
    }

    /**
     * @param $distributorProduct
     * @return array
     */
    protected function getUpdateOptions($distributorProduct,$rsr_id)
    {
        
        $channel = core()->getCurrentChannel();

        $productData= [
            'channel' => $channel->code,
            'locale' => 'en',
            'barrel_length' => (isset($distributorProduct['attributes']['barrel_length'])) ? $this->validateProductAttribute($distributorProduct['attributes']['barrel_length'], 'barrel_length') : '', //map rsr barrel_length to 2agun barrel_length
            'type_of_barrel' => (isset($distributorProduct['attributes']['type_of_barrel'])) ? $this->validateProductAttribute($distributorProduct['attributes']['type_of_barrel'], 'type_of_barrel') : '', //map rsr type_of_barrel to 2agun type_of_barrel
            'capacity' => (isset($distributorProduct['attributes']['capacity'])) ? $this->validateProductAttribute($distributorProduct['attributes']['capacity'], 'capacity') : '', //map rsrcapacity to 2agun capacity
            'Finish' => (isset($distributorProduct['attributes']['finish'])) ? $this->validateProductAttribute($distributorProduct['attributes']['finish'], 'finish') : '', //map rsr finish to 2agun finish
            'compatible_with' =>  (isset($distributorProduct['attributes']['fit'])) ? $distributorProduct['attributes']['caliber'] : ((isset($distributorProduct['attributes']['fit1'])) ? $distributorProduct['attributes']['fit1'] : ''),
            'Hand' => (isset($distributorProduct['attributes']['hand'])) ? $this->validateProductAttribute($distributorProduct['attributes']['hand'], 'Hand') : '', //map rsr finish to 2agun finish
            'color' => (isset($distributorProduct['attributes']['color'])) ? $this->validateProductAttribute($distributorProduct['attributes']['color'], 'color') : '',
            'size' => (isset($distributorProduct['attributes']['size'])) ? $this->validateProductAttribute($distributorProduct['attributes']['size'], 'size') : '', //map rsr finish to 2agun finish
            'man_part_num' => (isset($distributorProduct['attributes']['manufacturer_part_number'])) ? $distributorProduct['attributes']['manufacturer_part_number'] : ((isset($distributorProduct['manufacturerPartNumber'])) ? $distributorProduct['manufacturerPartNumber'] : ''),
            'Model' => (isset($distributorProduct['attributes']['model'])) ? $distributorProduct['attributes']['model'] : ((isset($distributorProduct['model'])) ? $distributorProduct['model'] : ''),
            'gun_size' => (isset($distributorProduct['attributes']['size'])) ? $this->validateProductAttribute($distributorProduct['attributes']['size'], 'gun_size') : '',
            'Type' => (isset($distributorProduct['attributes']['type'])) ? $distributorProduct['attributes']['type'] : '',
            'units_per_box' => (isset($distributorProduct['attributes']['units_per_box'])) ? $distributorProduct['attributes']['units_per_box'] : '',
            'material' => (isset($distributorProduct['attributes']['material'])) ? $this->validateProductAttribute($distributorProduct['attributes']['material'], 'material') : '',
            'sku' => !empty($distributorProduct['upcCode']) ? $distributorProduct['upcCode'] : $rsr_id,
            'name' => $this->validateProductName($distributorProduct['shortDescription']),
            'url_key' => $this->cleanUrl(urlencode($distributorProduct['shortDescription'])),
            'tax_category_id' => '',
            'visible_individually' => '1',
            'status' => '1',
            'ground_only' => $distributorProduct['groundShipmentsOnly']=='Y'? 1 : 0,
            'adult_sig' => $distributorProduct['adultSignatureRequired']=='Y'? 1 : 0,
            'brand' => (isset($distributorProduct['attributes']['manufacturer'])) ? $this->validateProductAttribute($distributorProduct['attributes']['manufacturer'], 'brand') : '',
            'preorder_qty' => '0',
            'condition' => $this->getCondition(), //(isset($distributorProduct['attributes']['condition'])) ? $this->validateProductAttribute($distributorProduct['attributes']['condition'], 'condition') : '', //map rsr condition to 2agun condition
            //'used_condition' => '', //map rsr condition to 2agun condition
            'short_description' => $this->validateProductShortDescription($distributorProduct['shortDescription']) ,
            'description' => $this->validateProductDescription($distributorProduct),
            'meta_title' =>  $this->validateProductShortDescription($distributorProduct['shortDescription']),
            'meta_keywords' =>  $this->validateProductShortDescription($distributorProduct['shortDescription']),
            'meta_description' =>  $this->validateProductShortDescription($distributorProduct['shortDescription']),
            'price' => $this->getPrice($distributorProduct),
            'cost' => '',
            'special_price' => '',
            'special_price_from' => '',
            'special_price_to' => '',
            'width' => $distributorProduct['width'],
            'height' => $distributorProduct['height'],
            'depth' => '',
            'weight_lbs' => number_format((float)$distributorProduct['weight'] / 16, 2, '.', ''), //we divided here over 16 to convert from OZ to Pounds
            'weight' => number_format((float)$distributorProduct['weight'] , 2, '.', ''),
            'shipping_price' => '',
            'channels' => [
                0 => $channel->id,
            ],
            'marketplaceCategories' => [],
            'action' => (isset($distributorProduct['attributes']['action'])) ? $this->validateProductAttribute($distributorProduct['attributes']['action'], 'action') : '', //map rsrcapacity to 2agun capacity
            'chamber' => (isset($distributorProduct['attributes']['chamber'])) ? $this->validateProductAttribute($distributorProduct['attributes']['chamber'], 'chamber') : '', //map rsr chamber to 2agun chamber
            'shell_length' => (isset($distributorProduct['attributes']['chamber'])) ? $this->validateProductAttribute($distributorProduct['attributes']['chamber'], 'shell_length') : '', //map rsr chamber to 2agun shell length
            'chokes' => (isset($distributorProduct['attributes']['chokes'])) ? $distributorProduct['attributes']['chokes'] : '',
            'edge' => (isset($distributorProduct['attributes']['edge'])) ? $this->validateProductAttribute($distributorProduct['attributes']['edge'], 'edge') : '', //map rsr edge to 2agun edge
            'velocity' => (isset($distributorProduct['attributes']['feet_per_second'])) ? $this->validateProductAttribute($distributorProduct['attributes']['feet_per_second'], 'velocity') : '',
            'caliber_singleselect' => (isset($distributorProduct['attributes']['caliber'])) ? $this->validateProductAttribute($distributorProduct['attributes']['caliber'], 'caliber_singleselect') : ((isset($distributorProduct['attributes']['caliber1'])) ? $this->validateProductAttribute($distributorProduct['attributes']['caliber1'], 'caliber_singleselect') : ''),
            'caliber_multiselect' => (isset($distributorProduct['attributes']['caliber'])) ? $this->validateProductAttribute($distributorProduct['attributes']['caliber'], 'caliber_multiselect') : ((isset($distributorProduct['attributes']['caliber1'])) ? $this->validateProductAttribute($distributorProduct['attributes']['caliber1'], 'caliber_multiselect') : ''),
            'grains' => (isset($distributorProduct['attributes']['grain_weight'])) ? $distributorProduct['attributes']['grain_weight'] : '',
            'grips' => (isset($distributorProduct['attributes']['grips'])) ? $this->validateProductAttribute($distributorProduct['attributes']['grips'], 'grips') : '',
            'manufacturer_ammunition' => (isset($distributorProduct['attributes']['manufacturer'])) ? $this->validateProductAttribute($distributorProduct['attributes']['manufacturer'], 'manufacturer_ammunition') : '',
            'manufacturer_firearm' => (isset($distributorProduct['attributes']['manufacturer'])) ? $this->validateProductAttribute($distributorProduct['attributes']['manufacturer'],'manufacturer_firearm') : ((isset($distributorProduct['manufacturerName'])) ?  $this->validateProductAttribute($distributorProduct['manufacturerName'],'manufacturer_firearm') : ''),
            'moa' => (isset($distributorProduct['attributes']['moa'])) ? $this->validateProductAttribute($distributorProduct['attributes']['moa'], 'moa') : '',
            'objective' => (isset($distributorProduct['attributes']['objective'])) ? $this->validateProductAttribute($distributorProduct['attributes']['objective'], 'objective') : '',
            'ounce_of_shot' => (isset($distributorProduct['attributes']['ounce_of_shot'])) ? $distributorProduct['attributes']['ounce_of_shot'] : '',
            'Power' => (isset($distributorProduct['attributes']['power'])) ? $this->validateProductAttribute($distributorProduct['attributes']['power'], 'Power') : '',
            'reticle' => (isset($distributorProduct['attributes']['reticle'])) ? $distributorProduct['attributes']['reticle'] : '',
            'safety' => (isset($distributorProduct['attributes']['safety'])) ? $distributorProduct['attributes']['safety'] : '',
            'Sights' => (isset($distributorProduct['attributes']['sights'])) ? $distributorProduct['attributes']['sights'] : '',
            'shot_size' => (isset($distributorProduct['attributes']['size'])) ? $this->validateProductAttribute($distributorProduct['attributes']['size'], 'shot_size') : '',
            'units_per_case' => (isset($distributorProduct['attributes']['units_per_case'])) ? $distributorProduct['attributes']['units_per_case'] : '',
            'stock_type' => (isset($distributorProduct['attributes']['stock'])) ? $this->validateProductAttribute($distributorProduct['attributes']['stock'], 'stock_type') : '',
            'lens_color' => (isset($distributorProduct['attributes']['lens_color'])) ? $this->validateProductAttribute($distributorProduct['attributes']['lens_color'], 'lens_color') : '',
            'handle_color' => (isset($distributorProduct['attributes']['handle_color'])) ? $this->validateProductAttribute($distributorProduct['attributes']['handle_color'], 'handle_color') : '',
            'new_stock_number' => (isset($distributorProduct['attributes']['new_stock_number'])) ? $distributorProduct['attributes']['new_stock_number'] : '',
            'vendor_id' => 0,
            'UPC' => !empty($distributorProduct['upcCode']) ? $distributorProduct['upcCode'] : '',
        ];

        if($distributorProduct['departmentId'] == '01' || $distributorProduct['departmentId'] == '02' || $distributorProduct['departmentId'] == '03' || $distributorProduct['departmentId'] == '05' || $distributorProduct['departmentId'] == '07'){
            $productData['shipping_type']='flat_rate';
            $productData['shipping_price']=30;
        }

        return $productData;

    }

    /**
     * @param $id
     * @return false|array
     */
    protected function getDistributorProduct($id)
    {
        // Try to fetch product from db
        $result = DistributorProducts::where('rsr_id', $id)->get();

        // Parse product data
        if (isset($result[0])) {
            $product = $result[0];
            $productData = json_decode($product->data, 1);
            $productData['quantity'] = $product->quantity;
            return $productData;
        }

        return false;
    }

    public function addProductCategory($product_id, $product_category)
    {
        $check_category = DB::select("select * from product_categories where product_id=" . $product_id . " and category_id=" . $product_category);
        if (!isset($check_category[0])) {
            $query = DB::table('product_categories')->insert(array('product_id' => $product_id, 'category_id' => $product_category));
            $category = DB::select("select * from categories where id=" . $product_category);
            $rootCategory = DB::select("select * from categories inner join category_translations on categories.id=category_translations.category_id where slug='root'");
            if (!empty($category[0]->parent_id) and $category[0]->parent_id != $rootCategory[0]->id) {
                $query = DB::table('product_categories')->insert(array('product_id' => $product_id, 'category_id' => $category[0]->parent_id));
                $parentCategory = DB::select("select * from categories where id=" . $category[0]->parent_id);
                if (!empty($parentCategory[0]->parent_id) and $parentCategory[0]->parent_id != $rootCategory[0]->id) {
                    $query = DB::table('product_categories')->insert(array('product_id' => $product_id, 'category_id' => $parentCategory[0]->parent_id));
                }
            }
        }
    }

    public function validateProductAttribute($attribute, $attribute_name)
    {

        if (!empty($attribute)) {
            return (new Validator('main'))->validateProductAttribute($attribute, $attribute_name);
        }

        return '';
    }

    public function validateProductDescription($data)
    {
        $description='';
        if(isset($data['attributes'])){
            $attributes = (array)$data['attributes'];
            $description = (isset($attributes['description'])) ? (!empty($attributes['description'])) ? $attributes['description'] : $data['description'] : $data['description'];
            if (isset($attributes['accessories'])) {
                if (!empty($attributes['accessories'])) {
                    $description = $description . " ,<br> Accesories :" . $attributes['accessories'];
                }
            }
            if (isset($attributes['diameter'])) {
                if (!empty($attributes['diameter'])) {
                    $description = $description . " ,<br> Diameter :" . $attributes['diameter'];
                }
            }
            if (isset($attributes['dram'])) {
                if (!empty($attributes['dram'])) {
                    $description = $description . " ,<br> Dram :" . $attributes['dram'];
                }
            }
        }else{
            if(isset($data['description'])){
                $description=str_replace('"',' ',$data['description']);;
            }
        }
        return $description;
    }

    function cleanUrl($string)
    {
        $string = strtolower($string);
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = str_replace('+', '-', $string); // Replaces all + with -.
        $string = str_replace('/', '-', $string); // Replaces all + with -.
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }

    public function getCondition()
    {
        $attribute = DB::select("select id from attributes where lower(code)='condition'");
        if ($attribute[0]->id) {
            $attribute_id = $attribute[0]->id;
            $attribute_options = DB::select("select * from attribute_options  where lower(attribute_options.admin_name)='new' and attribute_options.attribute_id=$attribute_id");
            $attribute_option_id = $attribute_options[0]->id;
            return $attribute_option_id;
        }
        return '';
    }

    public function validateProductName($description)
    {
        $description=str_replace('"',' ',$description);
        if (strpos($description, ', Fits') !== false) {
            return explode(', Fits', $description)[0];
        }
        if (strpos($description, '. Fits') !== false) {
            return explode('. Fits', $description)[0];
        }
        if (strpos($description, '/') !== false) {
            return explode('/', $description)[0];
        }
        return $description;

    }

    public function validateProductShortDescription($description){
        $description=str_replace('"',' ',$description);
        return $description;
    }
    public function deleteRsrProduct($sku)
    {
        $product = $this->productRepository->findWhere(['sku' => $sku])->first();
        $allow_delete=true;
        if (isset($product->id)) {
            $orders_items=app('Webkul\Marketplace\Repositories\OrderItemRepository')->getOrdersItemsByProduct($product->id);
            if($orders_items){
                foreach ($orders_items as $orders_item){
                    $order=app('Webkul\Marketplace\Repositories\OrderRepository')->getOrderByOrderItem($orders_item->marketplace_order_id);
                    if($order){
                        if($order->status=='processing'){$allow_delete=false;}
                    }
                }
            }
            if($allow_delete){
                $compare = \Webkul\Marketplace\Models\MarketplaceCustomerComprassion::where(['product_id' => $product->id])->get()->first();
                if($compare){
                    $compare->delete();
                }

                $wishlist = \Webkul\Marketplace\Models\MarketplaceCustomerWishlist::where(['product_id' => $product->id])->get()->first();
                if($wishlist){
                    $wishlist->delete();
                }

                $this->productRepository->delete($product->id);
                return array('status'=>'success','action'=>'delete');
            }else{
                $product=app('Webkul\Product\Repositories\ProductFlatRepository')->findwhere(['sku'=>$sku])->first();
                $product->status=0;
                $product->save();
                return array('status'=>'success','action'=>'unpublish');
            }
            return array('status'=>'error');
        }
        return array('status'=>'error');
    }
    public function addProductRestriction($data){
        $data = $this->group_by(0, $data);

        $update = $this->addRestrictions($data);
    }
    protected function addRestrictions($data)
    {

        $this->output->progressStart(count($data));

        foreach ($data as $i => $i_value) {
            $state_codes = [];
            $state_names = [];
            foreach ($i_value as $j => $j_value) {
                if ($j_value[3] == 'S' && $j_value[4] == 'Y') {
                    array_push($state_codes, $j_value[2]);
                }
            }

            foreach ($state_codes as $state_code) {
                $state_name = app('Webkul\Core\Repositories\CountryStateRepository')
                    ->findWhere(['country_code' => 'US', 'code' => $state_code])->first();
                if($state_name){
                    array_push($state_names,$state_name->default_name);
                }
            }
            $attribute_option_ids=[];
            foreach ($state_names as $state_name){
                $attribute_option_id= $this->validateProductAttribute($state_name, 'unavailable_in_states');

                if(isset($attribute_option_id[0])){
                    array_push($attribute_option_ids,$attribute_option_id[0]);
                }
            }

            $rsr_id=$j_value[0];
            $distributorProduct = DB::SELECT("SELECT *,JSON_UNQUOTE(JSON_EXTRACT(data, '$.upcCode')) as upcCode  FROM distributor_products where rsr_id ='" . $rsr_id . "'");
            if(sizeof($attribute_option_ids) > 0){
                if (isset($distributorProduct[0])) {
                    $distributorProduct = $distributorProduct[0];
                    $product = app("Webkul\Product\Repositories\ProductRepository")->findwhere(['sku' => $distributorProduct->upcCode])->first();
                    if(isset($product->id)){
                        //check record is already imported
                        $checked_product_attribute_value = app('Webkul\Product\Repositories\ProductAttributeValueRepository')->findwhere(["product_id" => $product->id, "attribute_id" => 1105, "value" => implode(",", $attribute_option_ids)])->first();
                        // code below is to add unavailable states to the product attribute values
                        if (!$checked_product_attribute_value) {
                            $product_attribute_values = app('Webkul\Product\Repositories\ProductAttributeValueRepository')->create(["product_id" => $product->id, "attribute_id" => 1105, "value" => implode(",", $attribute_option_ids)]);
                        }
                    }

                }
            }

            $this->output->progressAdvance();

        }


    }

    /**
     * Function that groups an array of associative arrays by some key.
     *
     * @param {String} $key Property to sort by.
     * @param {Array} $data Array that stores multiple associative arrays.
     */
    function group_by($key, $data)
    {
        $result = array();

        foreach ($data as $val) {
            if (array_key_exists($key, $val)) {
                $result[$val[$key]][] = $val;
            } else {
                $result[""][] = $val;
            }
        }

        return $result;
    }
    public function getPrice($distributorProduct){
        $price= (float)$distributorProduct['rsrPrice'] + 0.15 * (float)$distributorProduct['rsrPrice'];
        $add_shipping_fee=0;
        if($distributorProduct['departmentId'] == '01' || $distributorProduct['departmentId'] == '02' || $distributorProduct['departmentId'] == '03' || $distributorProduct['departmentId'] == '05' || $distributorProduct['departmentId'] == '07'){
          if((float)$distributorProduct['rsrPrice'] < 500){
            $add_shipping_fee=20;
          }
          $price= $add_shipping_fee + (float)$distributorProduct['rsrPrice'] + 0.1 * (float)$distributorProduct['rsrPrice'];
        }
        if(isset($distributorProduct['retailMap'])){
            if((float)$distributorProduct['retailMap']){
                $price=$distributorProduct['retailMap'];
                if($price < 500){
                  $add_shipping_fee=20;
                  $price=$price + $add_shipping_fee;
                }
            }
        }
        return $price;
    }


}