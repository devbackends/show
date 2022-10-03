<?php

namespace Devvly\DistributorImport\Console\Commands;

use Devvly\DistributorImport\Models\DistributorProducts;
use Devvly\DistributorImport\Services\Validator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

use Webkul\Product\Repositories\ProductRepository;

class RsrDeleteProducts extends Command
{
    protected $signature = 'rsr-delete-products';

    protected $description = 'periodically look at the rsrdeletedinv file and remove products they are no longer supplying';

    public function handle()
    {
        // Get data
        $this->comment('Fetch Deleted RSR Products From data');
        Log::info('Fetch Deleted RSR Products From data');
        $data = $this->getData();
        $this->comment('Deleted RSR Products has been fetched');
        Log::info('Fetch Deleted RSR Products From data');
        $this->deleteRsrProducts($data);
        $this->comment('Remove RSR products that are no longer supplying');
        Log::info('Remove RSR products that are no longer supplying');
    }

    /**
     * @return array|array[]
     */
    protected function getData()
    {
        return (new Validator('main'))->getRsrDeletedProducts();
    }


    protected function deleteRsrProducts($rsrProducts)
    {   $i=0;
        $this->output->progressStart(sizeof($rsrProducts));
        foreach ($rsrProducts as $rsrProduct){
            if($rsrProduct[2]=="DELETED"){
                $result = DistributorProducts::where('rsr_id', $rsrProduct[0])->get();
                if (isset($result[0])) {

                    $data = (array)json_decode($result[0]['data']);
                    $sku=$data['upcCode'];
                    if($sku){
                        $deletedProduct=app('Devvly\DistributorImport\Services\Product')->deleteRsrProduct($sku);
                        if(isset($deletedProduct['status'])){
                            if($deletedProduct['status']=='success'){
                                if($deletedProduct['action']=='delete'){
                                    Log::info('Product with Sku '.$sku.' has been deleted');
                                }else{
                                    Log::info('Product with Sku '.$sku.' has been unpublished');
                                }
                            }
                        }
                    }
                }
            }
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();
    }

}
