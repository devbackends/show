<?php

namespace Devvly\DistributorImport\Console\Commands;

use Devvly\DistributorImport\Models\DistributorProducts;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Update extends Command
{
    protected $signature = 'update-distribute';

    protected $description = 'Update products in our db with products exported from remote server';

    public function handle()
    {
        $this->comment('UPDATE | Start');
        $this->info('UPDATE | Start');

        $rsrProducts= DistributorProducts::all();
        $this->output->progressStart(sizeof($rsrProducts));
        foreach ($rsrProducts as $key => $rsrProduct){
            if(isset($rsrProduct->data)){
                if($rsrProduct->data){
                    if(isset(json_decode($rsrProduct->data)->upcCode)){
                        if(json_decode($rsrProduct->data)->upcCode){
                            $sku=json_decode($rsrProduct->data)->upcCode;
                            $product = app("Webkul\Product\Repositories\ProductRepository")->findWhere(['sku' => $sku])->first();
                            if($product){
                                $this->updateProduct($rsrProduct->rsr_id);
                            }else{
                                $this->addNewProduct($rsrProduct->rsr_id);
                            }
                        }else{
                                $sku=$rsrProduct->rsr_id;
                                $product = app("Webkul\Product\Repositories\ProductRepository")->findWhere(['sku' => $sku])->first();
                                if($product){
                                    $this->updateProduct($rsrProduct->rsr_id);
                                }else{
                                    $this->addNewProduct($rsrProduct->rsr_id);
                                }


                        }
                    }
                }
            }
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();

        $this->comment('UPDATE | DONE');
        $this->info('UPDATE | DONE');
    }

    /**
     * @param $products
     */
    protected function addNewProduct($rsr_id)
    {
        if($rsr_id){
            app('Devvly\DistributorImport\Services\Product')->create($rsr_id);
        }
    }

    /**
     * @param $products
     */
    protected function updateProduct($rsr_id)
    {
        if($rsr_id){
            app('Devvly\DistributorImport\Services\Product')->updateProducts($rsr_id);
            $this->comment('Product updated, rsr_id: ' . $rsr_id);
        }
    }

}