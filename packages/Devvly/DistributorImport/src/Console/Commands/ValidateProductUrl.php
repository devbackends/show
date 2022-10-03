<?php

namespace Devvly\DistributorImport\Console\Commands;

use Devvly\DistributorImport\Models\DistributorProducts;
use Devvly\DistributorImport\Services\Validator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use DB;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductFlatRepository;

class ValidateProductUrl extends Command
{
    protected $signature = 'validate-products-url';

    protected $description = 'validate-products-url';

    public function handle()
    {

        // Get data
        $this->comment('validate products url');

        $product_attributes=app('Webkul\Product\Repositories\ProductAttributeValueRepository')->findwhere([['attribute_id','=',1067]]);
        $this->output->progressStart(count($product_attributes));
        $i = 0;
        foreach ($product_attributes as $product_attribute) {
          $product_attribute_id=$product_attribute->product_id;
          $product_attribute_url=$product_attribute->text_value;

          $product_flat=app('Webkul\Product\Repositories\ProductFlatRepository')->findwhere([['product_id', '=', $product_attribute_id]])->first();
          if($product_flat){
              if($product_flat->url_key != $product_attribute_url){
                  $product_attribute->text_value=$product_flat->url_key;
                  $product_attribute->save();
              }
          }
            $i++;
            $this->output->progressAdvance();


        }
        $this->output->progressFinish();
    }
}
