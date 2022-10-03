<?php

namespace Devvly\DistributorImport\Console\Commands;

use Devvly\DistributorImport\Models\DistributorProducts;
use Devvly\DistributorImport\Services\Validator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use DB;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductFlatRepository;

class AddProductsSku extends Command
{
    protected $signature = 'add-product-sku';

    protected $description = 'Add Product sku';

    public function handle()
    {

        // Get data
        $this->comment('Add Product Sku');

        $products=app('Webkul\Product\Repositories\ProductFlatRepository')->All();
        $this->output->progressStart(count($products));
        $i = 0;
        foreach ($products as $product) {

            $sku=app('Webkul\Product\Repositories\ProductRepository')->findwhere(['id'=>$product->product_id])->first()->sku;
            $product->sku=$sku;
            $product->save();
            $i++;
            $this->output->progressAdvance();


        }
        $this->output->progressFinish();
    }
}
