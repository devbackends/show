<?php

namespace Devvly\DistributorImport\Console\Commands;

use Devvly\DistributorImport\Models\DistributorProducts;
use Devvly\DistributorImport\Services\Validator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductImage;
use DB;


class MigratingSubcategoryAttribute extends Command
{
    protected $signature = 'migrating-subcategory-attribute';

    protected $description = 'Migrating Subcategory Attribute';

    public function handle()
    {

/*        // Get data
        $this->comment('Migrating Subcategory Attribute');
        $this->output->progressAdvance();
        $this->output->progressFinish();*/

        DB::statement("update attribute_options set admin_name='Handgun Ammo' where admin_name='Handgun Ammunition'");
        DB::statement("update attribute_option_translations set label='Handgun Ammo' where label='Handgun Ammunition';");
        DB::statement("update attribute_options set admin_name='Rifle Ammo' where admin_name='Rifle Ammunition'");
        DB::statement("update attribute_option_translations set label='Rifle Ammo' where label='Rifle Ammunition'");
        DB::statement("update attribute_options set admin_name='Shotgun Ammo' where admin_name='Shot Shell Ammunition'");
        DB::statement("update attribute_option_translations set label='Shotgun Ammo' where label='Shot Shell Ammunition'");

        $productAttributeValues = app("Webkul\Product\Repositories\ProductAttributeValueRepository")->where('attribute_id', 1221)->get();
        $attributes_array = [];

        $this->info('fetch product attributes values');
        Log::info('fetch product attributes values');
        $this->output->progressStart(count($productAttributeValues));
        $i = 0;
        foreach ($productAttributeValues as $productAttributeValue) {
            $product_id=$productAttributeValue->product_id;
            $attribute_option_id=$productAttributeValue->integer_value;
            $attr_option = app('Webkul\Attribute\Repositories\AttributeOptionRepository')->find($attribute_option_id);
            if($attr_option){
                 //get category id
                $category = DB::select('select * from category_translations  where name="'.$attr_option->admin_name.'"');
                if($category){
                    if (!empty($category[0])) {
                        $checkProductIsMappedToCategory =DB::select("select * from product_categories  where product_id=".$productAttributeValue->product_id." and category_id=".$category[0]->id);
                        if(!$checkProductIsMappedToCategory){
                            $query = DB::table('product_categories')->insert(array('product_id' => $productAttributeValue->product_id, 'category_id' => $category[0]->id));
                            Log::info('product id '.$productAttributeValue->product_id.' has been mapped to '.$category[0]->id);
                        }else{
                            Log::info('product id '.$productAttributeValue->product_id.' is already mapped to '.$category[0]->id);
                        }
                    }else{
                        Log::info('Category '.$attr_option->admin_name.' is not found');
                    }
                }


            }
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();

        $this->info('Mapping finished');
        Log::info('Mapping finished');
        $this->info('Deleting attribute');
        Log::info('Deleting attribute');
        DB::statement("DELETE FROM `attributes_options_products_amount` WHERE `option_id` in (SELECT id from `attribute_options` WHERE `attribute_id`=1221)");
        DB::statement("DELETE FROM `attributes` WHERE code='sub_category'");
        $this->info('Attribute deleted');
        Log::info('Attribute deleted');
    }
}
