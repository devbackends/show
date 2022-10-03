<?php

namespace Devvly\DistributorImport\Console\Commands;

use Devvly\DistributorImport\Models\DistributorProducts;
use Devvly\DistributorImport\Services\Validator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use DB;
use function Webmozart\Assert\Tests\StaticAnalysis\length;

class AddProductRestrictions extends Command
{
    protected $signature = 'add-product-restrictions';

    protected $description = 'Add products restrictions';

    public function handle()
    {

        // Get data
        $this->comment('Start Fetch data');
        $data = $this->getData();

        $this->comment('Data received successfully');

        $this->comment('Start Adding Restrictions');
        // Add Product Restrictions

        $data = $this->group_by(0, $data);

        $update = $this->addRestrictions($data);
        $this->comment('Restrictions has been Added successfully');

    }

    protected function getData()
    {
        // Get remote content
        $content = trim(app('Devvly\DistributorImport\Services\Distributor')->get(config('distimport.files.main.restrictions')));

        // Parse file by lines
        $content = explode("\n", $content);

        return array_map(function ($value) {
            return explode(";", $value);
        }, $content);
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


    }

    public function validateProductAttribute($attribute, $attribute_name)
    {

        if (!empty($attribute)) {
            return (new Validator('main'))->validateProductAttribute($attribute, $attribute_name);
        }

        return '';
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

}
