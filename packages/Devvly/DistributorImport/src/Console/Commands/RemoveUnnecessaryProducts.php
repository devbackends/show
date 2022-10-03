<?php

namespace Devvly\DistributorImport\Console\Commands;

use Devvly\DistributorImport\Models\DistributorProducts;
use Devvly\DistributorImport\Services\Validator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use DB;
use Webkul\Product\Repositories\ProductRepository;

class RemoveUnnecessaryProducts extends Command
{
    protected $signature = 'rsr-unnecessary-products';

    protected $description = 'Remove all RSR products, except Firearms, Ammo and Knives';

    public function handle()
    {
        $this->comment('Delete RSR Products except Firearms, Ammo and Knives');
        Log::info('Delete RSR Products except Firearms, Ammo and Knives');
        $this->deleteRsrProducts();
        $this->comment('RSR products except Firearms, Ammo and Knives Are deleted');
        Log::info('RSR products except Firearms, Ammo and Knives Are deleted');
    }


    protected function deleteRsrProducts()
    {
        $distributorProducts = DB::SELECT("SELECT *,JSON_UNQUOTE(JSON_EXTRACT(data, '$.upcCode')) as upcCode  FROM distributor_products");
        $this->output->progressStart(sizeof($distributorProducts));
        foreach ($distributorProducts as $distributorProduct){
            $data=json_decode($distributorProduct->data);
            if(isset($data->upcCode)){
                $product = app("Webkul\Product\Repositories\ProductRepository")->findwhere(['sku' => $data->upcCode])->first();
                if($product){
                    if(isset($product->inventories()->get()[0]->qty)){
                        if($product->inventories()->get()[0]->qty==0){
                            if($product->sku){
                                $deletedProduct=app('Devvly\DistributorImport\Services\Product')->deleteRsrProduct($product->sku);
                                if(isset($deletedProduct['status'])){
                                    if($deletedProduct['status']=='success'){

                                        if($deletedProduct['action']=='delete'){
                                            Log::info('Product with Sku '.$product->sku.' has been deleted (out of stock)');
                                            continue;
                                        }else{
                                            Log::info('Product with Sku '.$product->sku.' has been unpublished  (out of stock) ');
                                            continue;
                                        }
                                    }
                                }

                            }
                        }
                    }
                    $delete=true;
                    foreach ($product->categories as $category) {
                        if(in_array($category->id,[70,71,72,75,76,77,126,81])){
                                $delete=false;
                        }
                    }
                    if($delete){
                        if($product->sku){
                            $deletedProduct=app('Devvly\DistributorImport\Services\Product')->deleteRsrProduct($product->sku);
                            if(isset($deletedProduct['status'])){
                                if($deletedProduct['status']=='success'){
                                    if($deletedProduct['action']=='delete'){
                                        Log::info('Product with Sku '.$product->sku.' has been deleted');
                                    }else{
                                        Log::info('Product with Sku '.$product->sku.' has been unpublished');
                                    }
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
