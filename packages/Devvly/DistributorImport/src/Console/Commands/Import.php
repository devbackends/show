<?php

namespace Devvly\DistributorImport\Console\Commands;

use Devvly\DistributorImport\Models\DistributorProducts;
use Devvly\DistributorImport\Services\Validator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use XPathSelector\Selector;
use DB;

class Import extends Command
{


    protected $signature = 'import-distribute';

    protected $description = 'Import products distributor feed';

    public function handle()
    {
        // Get data
        $this->comment('IMPORT | Fetch data');
        $this->setImportLog(0);
        $data = $this->getData();
        $this->comment('IMPORT | Data received successfully');

        // Save data , here we are truncating table and inserting data everytime
        $this->saveData($data);
        $this->comment('IMPORT | Data successfully saved in db');
        $this->comment('IMPORT | Products Imported to distributors table');
        $this->comment('IMPORT | Fetch Description');
        $descriptions = $this->getDescriptions();
        $this->saveDescriptions($descriptions);
        $this->comment('IMPORT | Fetch attributes');
        $attributes = $this->getAttributes();
        $this->comment('IMPORT | attributes received successfully');
        $this->saveAttributes($attributes);
        $this->comment('IMPORT | attributes Saved successfully');

        $this->info('IMPORT | DONE');
        Log::info('IMPORT | DONE');
        $this->setImportLog(1);
    }

    /**
     * @return array|array[]
     */
    protected function getData()
    {
        return (new Validator('main'))->execute();
    }

    protected function getAttributes()
    {
        $attributes = (new Validator('main'))->getAttributes();
        return $attributes;
    }


    protected function getDescriptions()
    {
        $descriptions = (new Validator('main'))->getDescriptions();
        return $descriptions;
    }


    /**
     * @param $data
     * @return mixed
     */
    protected function saveData($data)
    {
        // Prepare data for db insert
        $data = array_map(function ($item) {
            $quantity = $item['quantity'];
            unset($item['quantity']);
            return [
                'rsr_id' => $item['rsrStockId'],
                'quantity' => $quantity,
                'data' => json_encode($item),
            ];
        }, $data);

        // Create database and insert fresh data
        return DistributorProducts::truncate()->insert($data);
    }

    protected function saveDescriptions($descriptions)
    {
        $xs = Selector::loadXML($descriptions);
        $i = 0;
        $products = $xs->findAll('/product_sell_descriptions/product');
        $this->output->progressStart(sizeof($products));

        foreach ($products as $product) {
            $sku = $product->find('sku')->extract();
            $description = $product->find('sell_copy')->extract();
            $features = explode("\n", trim(preg_replace('/\t+/', '', $product->find('features')->extract())));
            $result = DistributorProducts::where('rsr_id', $sku)->get();
            if (isset($result[0])) {
                $distributorProduct = $result[0];
                $data = json_decode($distributorProduct->data);
                $data->unicode_description = $description;
                $data->features = json_encode($features);
                $distributorProduct->data = json_encode($data);
                $distributorProduct->save();
            }
            $i++;
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }

    protected function saveAttributes($attributes_array)
    {

        $this->output->progressStart(count($attributes_array));
        $i = 0;
        foreach ($attributes_array as $attribute_array) {
            $result = DistributorProducts::where('rsr_id', $attribute_array['rsr_stock_id'])->get();
            if (isset($result[0])) {
                $distributorProduct = $result[0];
                $data = json_decode($distributorProduct->data);
                if (!isset($data->attributes)) {
                    $data->attributes = $attribute_array;
                    $distributorProduct->data = json_encode($data);
                    $distributorProduct->save();
                }
            }
            $i++;
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();

    }

    function setImportLog($value){
        if($value==0){
            DB::table('rsr_logs')
                ->where('id',1)
                ->update(['import_distribute' =>$value,'import_latest_run' => date("Y-m-d H:i:s")]);
        }else{
            DB::table('rsr_logs')
                ->where('id',1)
                ->update(['import_distribute' =>$value]);
        }
    }


}
