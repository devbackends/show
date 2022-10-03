<?php

namespace Devvly\DistributorImport\Console\Commands;

use Devvly\DistributorImport\Models\DistributorProducts;
use Devvly\DistributorImport\Services\Validator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use XPathSelector\Selector;
use DB;

class UpdateDistribute extends Command
{


    protected $signature = 'update-rsr-distribute';

    protected $description = 'Update products distributor feed';

    public function handle()
    {
        $checkProductsImported=DB::table('rsr_logs')->select('*')->where('id', 1)->get();
        if(isset($checkProductsImported[0])){
            $log=$checkProductsImported[0];
            if($log->import_distribute==1){
                // Get data
                $this->setUpdateLog(0);
                $this->comment('Update | Start Updating');
                $call = $this->call('update-distribute');
                // Call next commands
                $this->comment('Update | Fetch Restrictions on products');
                $restrictions = $this->getRestrictions();
                $this->comment('Update | Add Restrictions on products');
                $restrictions = $this->group_by(0, $restrictions);
                $this->addProductRestriction($restrictions);
                $this->comment('Update | Finish adding Restrictions on products');

                $this->comment('Update | Fetch product Warnings on products');
                $productWarnings = $this->getProductWarnings();
                $this->comment('Update | Add Warnings on products');
                $this->addProductWarnings($productWarnings);
                $this->comment('Update | Finish adding Warnings on products');

                $this->comment('Update | start updating inventory');
                $call = $this->call('update-inventory');
                $this->comment('Update | end updating inventory');

                $this->comment('Update | start updating products amount');
                $call = $this->call('calculate-attribute-options-products-amount',['next'=>'all']);
                $this->comment('Update | end updating products amount');


                $this->comment('Update | start updating elastic search');
                $call = $this->call('elasticsearch:index');
                $this->comment('Update | end updating elastic search');

                $this->info('Update | DONE');
                Log::info('Update | DONE');
                $this->setUpdateLog(1);
            }else{
                $this->info('Update rsr script cannot be run because the import is not finished');
                Log::info('Update rsr script cannot be run because the import is not finished');
            }
        }else{
            $this->info('Update rsr script cannot be run because the import is not finished');
            Log::info('Update rsr script cannot be run because the import is not finished');
        }
    }



    protected function getRestrictions()
    {
        $restrictions = (new Validator('main'))->getRestrictions();
        return $restrictions;
    }

    protected function getProductWarnings()
    {
        $productWarnings = (new Validator('main'))->getProductWarnings();
        return $productWarnings;
    }


    public function addProductRestriction($data)
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
                if ($state_name) {
                    array_push($state_names, $state_name->default_name);
                }
            }
            $attribute_option_ids = [];
            foreach ($state_names as $state_name) {
                $attribute_option_id = $this->validateProductAttribute($state_name, 'unavailable_in_states');

                if (isset($attribute_option_id[0])) {
                    array_push($attribute_option_ids, $attribute_option_id[0]);
                }
            }
            $rsr_id = $j_value[0];
            $distributorProduct = DB::SELECT("SELECT *,JSON_UNQUOTE(JSON_EXTRACT(data, '$.upcCode')) as upcCode  FROM distributor_products where rsr_id ='" . $rsr_id . "'");
            if (sizeof($attribute_option_ids) > 0) {
                if (isset($distributorProduct[0])) {
                    $distributorProduct = $distributorProduct[0];
                    $product = app("Webkul\Product\Repositories\ProductRepository")->findwhere(['sku' => $distributorProduct->upcCode])->first();
                    if (isset($product->id)) {
                        //check record is already imported
                        $checked_product_attribute_value = app('Webkul\Product\Repositories\ProductAttributeValueRepository')->findwhere(["product_id" => $product->id, "attribute_id" => 1105, "text_value" => implode(",", $attribute_option_ids)])->first();
                        // code below is to add unavailable states to the product attribute values
                        if (!$checked_product_attribute_value) {
                            $product_attribute_values = app('Webkul\Product\Repositories\ProductAttributeValueRepository')->create(["product_id" => $product->id, "attribute_id" => 1105, "value" => implode(",", $attribute_option_ids)]);
                        }

                    }

                }
            }

            $this->output->progressAdvance();

        }
        $this->output->progressFinish();
    }

    public function addProductWarnings($data)
    {
        $attribute_id = app('Webkul\Attribute\Repositories\AttributeRepository')->findwhere(["code" => 'prop65'])->first();

        if ($attribute_id) {
            $this->output->progressStart(count($data));
            foreach ($data as $i => $item) {
                if ($i > 0) {
                    $upcCode = $item[1];

                    $product = app("Webkul\Product\Repositories\ProductRepository")->findwhere(['sku' => $upcCode])->first();
                    if (isset($product->id)) {
                        //check record is already imported
                        $checked_product_attribute_value = app('Webkul\Product\Repositories\ProductAttributeValueRepository')->findwhere(["product_id" => $product->id, "attribute_id" => $attribute_id->id, "text_value" => $item[4]])->first();
                        // code below is to add unavailable states to the product attribute values
                        if (!$checked_product_attribute_value) {
                            $product_attribute_values = app('Webkul\Product\Repositories\ProductAttributeValueRepository')->create(["product_id" => $product->id, "attribute_id" => $attribute_id->id, "value" => $item[4]]);
                        }

                    }
                }
                $this->output->progressAdvance();

            }
            $this->output->progressFinish();
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
    public function validateProductAttribute($attribute, $attribute_name)
    {

        if (!empty($attribute)) {
            return (new Validator('main'))->validateProductAttribute($attribute, $attribute_name);
        }

        return '';
    }
    function setUpdateLog($value){
        if($value==0){
            DB::table('rsr_logs')
                ->where('id',1)
                ->update(['update_rsr_distribute' =>$value,'update_latest_run' => date("Y-m-d H:i:s")]);
        }else{
            DB::table('rsr_logs')
                ->where('id',1)
                ->update(['update_rsr_distribute' =>$value]);
        }
    }

}
