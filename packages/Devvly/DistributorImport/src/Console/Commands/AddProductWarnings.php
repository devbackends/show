<?php

namespace Devvly\DistributorImport\Console\Commands;

use Devvly\DistributorImport\Models\DistributorProducts;
use Devvly\DistributorImport\Services\Validator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use DB;
use function Webmozart\Assert\Tests\StaticAnalysis\length;

class AddProductWarnings extends Command
{
    protected $signature = 'add-product-warnings';

    protected $description = 'Add products warnings';

    public function handle()
    {

        // Get data
        $this->comment('Start Fetch data');
        $data = $this->getData();

        $this->comment('Data received successfully');
        $this->comment('Start Adding Restrictions');
        // Add Product Restrictions

        $update = $this->addWarnings($data);


    }

    protected function getData()
    {
        // Get remote content
        $content = trim(app('Devvly\DistributorImport\Services\Distributor')->get(config('distimport.files.main.product-warnings')));

        // Parse file by lines
        $content = explode("\n", $content);

        return array_map(function ($value) {
            return explode(";", $value);
        }, $content);
    }

    protected function addWarnings($data)
    {

        $this->output->progressStart(count($data));

        $attribute_id = app('Webkul\Attribute\Repositories\AttributeRepository')->findwhere(["code" => 'prop65'])->first();

        if ($attribute_id) {
            foreach ($data as $i => $item) {
                if ($i > 0) {
                    $upcCode = $item[1];

                    $product = app("Webkul\Product\Repositories\ProductRepository")->findwhere(['sku' => $upcCode])->first();
                    if (isset($product->id)) {
                        //check record is already imported
                        $checked_product_attribute_value = app('Webkul\Product\Repositories\ProductAttributeValueRepository')->findwhere(["product_id" => $product->id, "attribute_id" => $attribute_id->id ,"text_value" => $item[4] ])->first();
                        // code below is to add unavailable states to the product attribute values
                        if (!$checked_product_attribute_value) {
                            $product_attribute_values = app('Webkul\Product\Repositories\ProductAttributeValueRepository')->create(["product_id" => $product->id, "attribute_id" => $attribute_id->id ,"value" => $item[4] ]);
                        }

                    }
                }
                $this->output->progressAdvance();

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
