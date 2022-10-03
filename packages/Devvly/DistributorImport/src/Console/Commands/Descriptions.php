<?php

namespace Devvly\DistributorImport\Console\Commands;

use Devvly\DistributorImport\Models\DistributorProducts;
use Devvly\DistributorImport\Services\Validator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use DB;

class Descriptions extends Command
{
    protected $signature = 'import-descriptions';

    protected $description = 'Import products description';

    public function handle()
    {
        // Get data

        $this->comment('Start');

        $products = app("Webkul\Product\Repositories\ProductRepository")->getAll();
        $this->output->progressStart(count($products));
        $i = 0;
        foreach ($products as $product) {

            $distributorProduct = DB::SELECT("SELECT *,JSON_UNQUOTE(JSON_EXTRACT(data, '$.upcCode')) as upcCode  FROM distributor_products where JSON_UNQUOTE(JSON_EXTRACT(data, '$.upcCode')) ='" . $product->sku . "'");

            if (isset($distributorProduct[0])) {

                $data = json_decode($distributorProduct[0]->data);

                $description = $this->validateProductName($data->description);


                DB::table('product_attribute_values')->where('attribute_id', 1066)->where('product_id', $product->id)->update(array('text_value' => $description));
                DB::table('product_attribute_values')->where('attribute_id', 1073)->where('product_id', $product->id)->update(array('text_value' => $data->description,));
                DB::table('product_attribute_values')->where('attribute_id', 1074)->where('product_id', $product->id)->update(array('text_value' => $this->validateProductDescription((array)$data)));
                $arr = array('short_description' => $data->description, 'name' => $this->validateProductName($data->description));

                    $arr['description'] = $this->validateProductDescription((array)$data);


                DB::table('product_flat')->where('product_id', $product->id)->update($arr);

            }
            $i++;
            $this->output->progressAdvance();

        }
        $this->output->progressFinish();
        $this->info('IMPORT | DONE');
        Log::info('IMPORT | DONE');
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


}
