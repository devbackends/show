<?php

namespace Devvly\DistributorImport\Console\Commands;

use Devvly\DistributorImport\Models\DistributorProducts;
use Devvly\DistributorImport\Services\Validator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use DB;

class UpdateInventory extends Command
{
    protected $signature = 'update-inventory';

    protected $description = 'Update products inventories from distributor feed';

    public function handle()
    {

        Log::info("Start Updating Inventory");
        // Get data


            $this->comment('Start Fetch data');
            $data = $this->getData();

            $this->comment('Data received successfully');

            $this->comment('Start updating inventory');
            // Update Inventory data
            $update=$this->updateInventory($data);
            $this->comment('Inventories Updated successfully');
        

        Log::info("Finish Updating Inventory");

    }

    protected function getData(){
        // Get remote content
        $content = trim(app('Devvly\DistributorImport\Services\Distributor')->get(config('distimport.files.main.inventory')));

        // Parse file by lines
        $content = explode("\n", $content);

        return $content;
    }
    protected function updateInventory($data){

        $this->output->progressStart(count($data));
        foreach ($data as $item) {

            $x=explode(',',$item);
            $rsr_id=$x[0];
            $quantity=$x[1];

            $distributorProduct=DB::SELECT("SELECT *,JSON_UNQUOTE(JSON_EXTRACT(data, '$.upcCode')) as upcCode  FROM distributor_products where rsr_id ='" . $rsr_id . "'");

            if(isset($distributorProduct[0])) {
                $distributorProduct=$distributorProduct[0];

                $product = app("Webkul\Product\Repositories\ProductFlatRepository")->findwhere(['sku'=>$distributorProduct->upcCode])->first();
                if($product ){
                    $product->quantity=(int)$quantity + (int) $product->ordered_quantity;
                    $product->save();

                }
            }
            $this->output->progressAdvance();
        }
    }


}
