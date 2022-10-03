<?php

namespace Devvly\DistributorImport\Console\Commands;

use Devvly\DistributorImport\Models\DistributorProducts;
use Devvly\DistributorImport\Services\Validator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use DB;
use Webkul\Attribute\Models\AttributeOption;
use Webkul\Attribute\Models\AttributeOptionTranslation;

class AddProductAttribute extends Command
{
    protected $signature = 'add-product-attribute';

    protected $description = 'Add Product Attribute';

    public function handle()
    {

        // Get data
        $this->comment('Add Product Attribute');

        $products = app("Webkul\Product\Repositories\ProductRepository")->getAll();
        $this->output->progressStart(count($products));
        $i = 0;
        foreach ($products as $product) {

            //$distributorProduct = DistributorProducts::where(JSON_UNQUOTE(JSON_EXTRACT(data, '$.upcCode')), $product->sku)->get()->first();
            $distributorProduct = DB::SELECT("SELECT *,JSON_UNQUOTE(JSON_EXTRACT(data, '$.upcCode')) as upcCode  FROM distributor_products where JSON_UNQUOTE(JSON_EXTRACT(data, '$.upcCode')) ='" . $product->sku . "'");

            if (isset($distributorProduct[0])) {
                $distributorProduct = $distributorProduct[0];
                $distributorProduct = (array)$distributorProduct;

                $product->getTypeInstance()->update($this->getUpdateOptions($distributorProduct), $product->id, 'id');

            }
            $i++;
            $this->output->progressAdvance();


        }
        $this->output->progressFinish();
    }

    public function getUpdateOptions($distributorProduct)
    {

        $data = (array)json_decode($distributorProduct['data']);
        $attributes = (isset($data['attributes'])) ? (array)$data['attributes'] : [];

        $channel = core()->getDefaultChannel();

        return [
            'channel' => $channel->name,
            'locale' => 'en',
            'barrel_length' => (isset($attributes['barrel_length'])) ? $this->validateProductAttribute($attributes['barrel_length'], 'barrel_length') : '', //map rsr barrel_length to 2agun barrel_length
            'type_of_barrel' => (isset($attributes['type_of_barrel'])) ? $this->validateProductAttribute($attributes['type_of_barrel'], 'type_of_barrel') : '', //map rsr type_of_barrel to 2agun type_of_barrel
            'capacity' => (isset($attributes['capacity'])) ? $this->validateProductAttribute($attributes['capacity'], 'capacity') : '', //map rsrcapacity to 2agun capacity
            'Finish' => (isset($attributes['finish'])) ? $this->validateProductAttribute($attributes['finish'], 'finish') : '', //map rsr finish to 2agun finish
            'compatible_with' => (isset($attributes['fit'])) ? $attributes['fit'] : '',
            'Hand' => (isset($attributes['hand'])) ? $this->validateProductAttribute($attributes['hand'], 'Hand') : '', //map rsr finish to 2agun finish
            'color' => (isset($attributes['color'])) ? $this->validateProductAttribute($attributes['color'], 'color') : '',
            'size' => (isset($attributes['size'])) ? $this->validateProductAttribute($attributes['size'], 'size') : '', //map rsr finish to 2agun finish
            'man_part_num' => (isset($attributes['manufacturer_part_number'])) ? $attributes['manufacturer_part_number'] : '',
            'Model' => (isset($attributes['model'])) ? $attributes['model'] : '',
            'gun_size' => (isset($attributes['size'])) ? $this->validateProductAttribute($attributes['size'], 'gun_size') : '',
            'Type' => (isset($attributes['type'])) ? $attributes['type'] : '',
            'units_per_box' => (isset($attributes['units_per_box'])) ? $attributes['units_per_box'] : '',
            'material' => (isset($attributes['material'])) ? $this->validateProductAttribute($attributes['material'], 'material') : '',
            'sku' => $data['upcCode'],
            'name' => $this->validateProductName($data['description']),
            'url_key' => $this->cleanUrl(urlencode($data['shortDescription'])),
            'tax_category_id' => '',
            'visible_individually' => '1',
            'status' => '1',
            'ground_only' => '1',
            'adult_sig' => '1',
            'brand' => (isset($attributes['manufacturer'])) ? $this->validateProductAttribute($attributes['manufacturer'], 'brand') : '',
            'preorder_qty' => '0',
            'condition' => $this->getCondition(),
            //   'used_condition' => '', //map rsr condition to 2agun condition
            'short_description' => $data['description'],
            'description' => $this->validateProductDescription($data),
            'meta_title' => $data['shortDescription'],
            'meta_keywords' => $data['shortDescription'],
            'meta_description' => $data['shortDescription'],
            'price' => $data['retailPrice'],
            'cost' => '',
            'special_price' => '',
            'special_price_from' => '',
            'special_price_to' => '',
            'width' => $data['width'],
            'height' => $data['height'],
            'depth' => '',
            'weight' => $data['weight'],
            'shipping_price' => '',
            'inventories' => [
                $channel->id => $distributorProduct['quantity'],
            ],
            'channels' => [
                0 => $channel->id,
            ],
            'marketplaceCategories' => [],
            'action' => (isset($attributes['action'])) ? $this->validateProductAttribute($attributes['action'], 'action') : '', //map rsrcapacity to 2agun capacity
            'chamber' => (isset($attributes['chamber'])) ? $this->validateProductAttribute($attributes['chamber'], 'chamber') : '', //map rsr chamber to 2agun chamber
            'shell_length' => (isset($attributes['chamber'])) ? $this->validateProductAttribute($attributes['chamber'], 'chamber') : '', //map rsr chamber to 2agun shell length
            'chokes' => (isset($attributes['chokes'])) ? $attributes['chokes'] : '',
            'edge' => (isset($attributes['edge'])) ? $this->validateProductAttribute($attributes['edge'], 'edge') : '', //map rsr edge to 2agun edge
            'velocity' => (isset($attributes['feet_per_second'])) ? $this->validateProductAttribute($attributes['feet_per_second'], 'velocity') : '',
            'caliber_singleselect' => (isset($attributes['caliber'])) ? $this->validateProductAttribute($attributes['caliber'], 'caliber_singleselect') : '',
            'caliber_multiselect' => (isset($attributes['caliber'])) ? $this->validateProductAttribute($attributes['caliber'], 'caliber_multiselect') : '',
            'grains' => (isset($attributes['grain_weight'])) ? $attributes['grain_weight'] : '',
            'grips' => (isset($attributes['grips'])) ? $this->validateProductAttribute($attributes['grips'], 'grips') : '',
            'manufacturer_ammunition' => (isset($attributes['manufacturer'])) ? $this->validateProductAttribute($attributes['manufacturer'], 'manufacturer_ammunition') : '',
            'manufacturer_firearm' => (isset($attributes['manufacturer'])) ? $this->validateProductAttribute($attributes['manufacturer'], 'manufacturer_firearm') : '',
            'moa' => (isset($attributes['moa'])) ? $this->validateProductAttribute($attributes['moa'], 'moa') : '',
            'objective' => (isset($attributes['objective'])) ? $this->validateProductAttribute($attributes['objective'], 'objective') : '',
            'ounce_of_shot' => (isset($attributes['ounce_of_shot'])) ? $attributes['ounce_of_shot'] : '',
            'Power' => (isset($attributes['power'])) ? $this->validateProductAttribute($attributes['power'], 'Power') : '',
            'reticle' => (isset($attributes['reticle'])) ? $attributes['reticle'] : '',
            'safety' => (isset($attributes['safety'])) ? $attributes['safety'] : '',
            'Sights' => (isset($attributes['sights'])) ? $attributes['sights'] : '',
            'shot_size' => (isset($attributes['size'])) ? $this->validateProductAttribute($attributes['size'], 'shot_size') : '',
            'units_per_case' => (isset($attributes['units_per_case'])) ? $attributes['units_per_case'] : '',
            'stock_type' => (isset($attributes['stock'])) ? $this->validateProductAttribute($attributes['stock'], 'stock_type') : '',
            'lens_color' => (isset($attributes['lens_color'])) ? $this->validateProductAttribute($attributes['lens_color'], 'lens_color') : '',
            'handle_color' => (isset($attributes['handle_color'])) ? $this->validateProductAttribute($attributes['handle_color'], 'handle_color') : '',
            'new_stock_number' => (isset($attributes['new_stock_number'])) ? $attributes['new_stock_number'] : ''


        ];
    }

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

    public function validateProductAttribute($rsr_attribute, $attribute_name)
    {

        if (!empty($rsr_attribute)) {
            $attribute = DB::select("select * from attributes where lower(code)='$attribute_name'");
            $attribute_id = $attribute[0]->id;

            if ($attribute[0]->type == 'multiselect') {

                $multiAttributesOptons = explode(',', $rsr_attribute);
                $arr=array();

                foreach ($multiAttributesOptons as $attribute_option) {
                    $attribute_option=rtrim(ltrim($attribute_option));
                    $count = AttributeOption::where(['admin_name' => $attribute_option], ['attribute_id' => $attribute_id])->get()->count();
                    if ($count == 0) {
                       $attribute_option_id= $this->addProductOption($attribute_option,$attribute_id);
                    }else{
                        $attribute_option_id = AttributeOption::where(['admin_name'=>$attribute_option],['attribute_id'=>$attribute_id])->get()->first()->id;
                    }
                      array_push($arr,$attribute_option_id);
                }
                return $arr;
            }

            $count=AttributeOption::where(['admin_name'=>$rsr_attribute],['attribute_id'=>$attribute_id])->get()->count();

            if ($count == 0) {

                return  $this->addProductOption($rsr_attribute,$attribute_id);
            } else {
                $attribute_option = AttributeOption::where(['admin_name'=>$rsr_attribute],['attribute_id'=>$attribute_id])->get()->first();
                if (isset($attribute_option->id)) {
                    return $attribute_option->id;
                }
            }
        }

        return '';
    }

    public function addProductOption($rsr_attribute,$attribute_id){
        $attribute_option = new AttributeOption();
        $attribute_option->admin_name = $rsr_attribute;
        $attribute_option->sort_order = 1;
        $attribute_option->attribute_id = $attribute_id;
        $attribute_option->save();
        $attribute_option_translations = new AttributeOptionTranslation();

        $attribute_option_translations->locale = 'en';
        $attribute_option_translations->label = ucfirst($rsr_attribute);
        $attribute_option_translations->attribute_option_id = $attribute_option->id;
        $attribute_option_translations->save();
        return $attribute_option->id;
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

    public function validateProductDescription($data)
    {
        $attributes = (isset($data['attributes'])) ? (array)$data['attributes'] : [];

        $description = '';
        if (isset($data['unicode_description'])) {
            if (!empty($data['unicode_description'])) {
                $description = $data['unicode_description'];
            } else {
                $description = (isset($data['attributes'])) ? (!empty($attributes['description'])) ? $attributes['description'] : $data['description'] : $data['description'];
            }
        } else {
            $description = (isset($data['attributes'])) ? (!empty($attributes['description'])) ? $attributes['description'] : $data['description'] : $data['description'];
        }

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

        if (isset($data['features'])) {
            if (!empty($data['features'])) {
                $features = json_decode($data['features']);
                $description = $description . '<br>';
                foreach ($features as $feature) {
                    $description = $description . "<li>" . $feature . "</li>";

                }
            }
        }


        return $description;
    }

    public function validateProductName($description)
    {

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


    function cleanUrl($string)
    {
        $string = strtolower($string);
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = str_replace('+', '-', $string); // Replaces all + with -.
        $string = str_replace('/', '-', $string); // Replaces all + with -.
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }


}
