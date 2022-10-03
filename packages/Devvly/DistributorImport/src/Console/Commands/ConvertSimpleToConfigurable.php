<?php

namespace Devvly\DistributorImport\Console\Commands;

use Devvly\DistributorImport\Models\DistributorProducts;
use Devvly\DistributorImport\Services\Validator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use DB;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductFlat;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductImage;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductFlatRepository;

class ConvertSimpleToConfigurable extends Command
{
    protected $signature = 'convert-simple-products-to-configurable';

    protected $description = 'Convert Simple Products To Configurable';

    public function handle()
    {

        // Get data
        $this->comment('Start Converting Simple Products To Configurable');
        $distributorProducts = DB::SELECT("SELECT *,JSON_UNQUOTE(JSON_EXTRACT(data, '$.upcCode')) as upcCode  FROM distributor_products where JSON_UNQUOTE(JSON_EXTRACT(data, '$.productType')) ='configurable'");
        foreach ($distributorProducts as $distributorProduct) {
            $checkProduct = app("Webkul\Product\Repositories\ProductRepository")->where('sku', $distributorProduct->upcCode)->first();

            if ($checkProduct) {

                if (empty($checkProduct->parent_id)) {

                    $string_arr = explode('-', $distributorProduct->rsr_id);

                    $product_skus = DB::SELECT("SELECT JSON_UNQUOTE(JSON_EXTRACT(data, '$.upcCode')) as upcCode  FROM distributor_products where rsr_id like '$string_arr[0]-%'");

                    if (count($product_skus) == 0) {
                        if (count($string_arr) > 1) {
                            $product_skus = DB::SELECT("SELECT JSON_UNQUOTE(JSON_EXTRACT(data, '$.upcCode')) as upcCode  FROM distributor_products where rsr_id like '$string_arr[0]-$string_arr[1]-%'");
                        }
                    }

                    if (count($product_skus) > 0) {
                        $product_skus = array_map(function ($value) {
                            return $value->upcCode;
                        }, $product_skus);

                        $products = app("Webkul\Product\Repositories\ProductRepository")->whereIn('sku', $product_skus)->get();

                        if (count($products) > 0) {

                            $newProduct = new Product();
                            $newProduct->sku = hexdec(uniqid());
                            $newProduct->type = 'configurable';
                            $newProduct->created_at = $products[0]->created_at;
                            $newProduct->updated_at = $products[0]->updated_at;
                            $newProduct->attribute_family_id = $products[0]->attribute_family_id;
                            $newProduct->save();

                            $productFlats = app("Webkul\Product\Repositories\ProductFlatRepository")->whereIn('sku', $product_skus)->get();
                            if (count($productFlats) > 0) {
                                $newProductFlat = new ProductFlat();
                                $newProductFlat->sku = $newProduct->sku;
                                $newProductFlat->name = $productFlats[0]->name;
                                $newProductFlat->description = $productFlats[0]->description;
                                $newProductFlat->url_key = $productFlats[0]->url_key . "-main";
                                $newProductFlat->new =0;
                                $newProductFlat->featured =0;
                                $newProductFlat->status = 1;
                                $newProductFlat->created_at = $productFlats[0]->created_at;
                                $newProductFlat->updated_at = $productFlats[0]->updated_at;
                                $newProductFlat->locale = $productFlats[0]->locale;
                                $newProductFlat->channel = $productFlats[0]->channel;
                                $newProductFlat->product_id = $newProduct->id;
                                $newProductFlat->visible_individually = 1;
                                $newProductFlat->short_description = $productFlats[0]->short_description;
                                $newProductFlat->save();
                            }

                            $this->comment($checkProduct->id);
                            $this->comment('A new product has been imported with id: ' . $newProduct->id);
                            if ($newProduct) {
                                foreach ($products as $product) {
                                    $product->parent_id = $newProduct->id;
                                    $product->save();
                                    $productImages = app("Webkul\Product\Repositories\ProductImageRepository")->where('product_id', $product->id)->get();

                                    foreach ($productImages as $productImage) {
                                        $newProductImage = new ProductImage();
                                        $newProductImage->path = $productImage->path;
                                        $newProductImage->product_id = $newProduct->id;
                                        $newProductImage->sort_order = $productImage->sort_order;
                                        $newProductImage->save();
                                    }
                                }
                                foreach ($productFlats as $productFlat) {
                                    $productFlat->parent_id = $newProductFlat->id;
                                    $productFlat->save();
                                }
                                $productAttributeValues = app("Webkul\Product\Repositories\ProductAttributeValueRepository")->where('product_id', $products[0]->id)->get();
                                $attributes_array = [];
                                foreach ($productAttributeValues as $productAttributeValue) {
                                    $newProductAttributeValue = new ProductAttributeValue();
                                    $newProductAttributeValue->locale = $productAttributeValue->locale;
                                    $newProductAttributeValue->channel = $productAttributeValue->channel;
                                    $newProductAttributeValue->text_value = $productAttributeValue->text_value;
                                    $newProductAttributeValue->boolean_value = $productAttributeValue->boolean_value;
                                    $newProductAttributeValue->integer_value = $productAttributeValue->integer_value;
                                    $newProductAttributeValue->float_value = $productAttributeValue->float_value;
                                    $newProductAttributeValue->datetime_value = $productAttributeValue->datetime_value;
                                    $newProductAttributeValue->date_value = $productAttributeValue->date_value;
                                    $newProductAttributeValue->json_value = $productAttributeValue->json_value;
                                    $newProductAttributeValue->product_id = $newProduct->id;
                                    $newProductAttributeValue->attribute_id = $productAttributeValue->attribute_id;
                                    $newProductAttributeValue->save();
                                    if (!in_array($productAttributeValue->attribute_id, $attributes_array)) {
                                        array_push($attributes_array, $productAttributeValue->attribute_id);
                                    }
                                }
                                foreach ($attributes_array as $attribute) {
                                    $checkAttribute=app('Webkul\Attribute\Repositories\AttributeRepository')->where('id',$attribute)->get()->first();
                                    if($checkAttribute->type=='select' || $checkAttribute->type=='multiselect'){
                                        DB::table('product_super_attributes')->insertGetId(
                                            [
                                                'product_id' => $newProduct->id,
                                                'attribute_id' => $attribute
                                            ]);
                                    }


                                }


                            }

                        }
                    }
                }
            }


        }
    }
}
