<?php

namespace Devvly\DistributorImport\Console\Commands;

use Devvly\DistributorImport\Models\DistributorProducts;
use Devvly\DistributorImport\Services\Validator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use DB;
use Webkul\Attribute\Models\AttributeOption;
use Webkul\Attribute\Models\AttributeOptionTranslation;

class UpdateProductAttributeValuesOptions extends Command
{
    protected $signature = 'update-product-attribute-values-options';

    protected $description = 'update-product-attribute-values-options';

    public function handle()
    {

        // Get data
        $this->comment('update Product Attribute values option');

        $productAttributes=app('Webkul\Product\Repositories\ProductAttributeValueRepository')->whereIn('attribute_id',[1068,1087,1088,1094,1095,1096,1097,1101,1102,1103,1104,1105,1195,1196,1197,1199,1200,1201,1203,1204,1206,1208,1210,1214,1218,1219,1220,1221,1351,1352,1353,1354,1357]);

        $this->output->progressStart(count($productAttributes));
        $i = 0;
        foreach ($productAttributes as $productAttribute) {


                $attribute_option = app('Webkul\Attribute\Repositories\AttributeOptionRepository')->findwhere(['id' => $productAttribute->text_value, 'attribute_id' => $productAttribute->attribute_id])->first();
                $attr_option = app('Webkul\Attribute\Repositories\AttributeOptionRepository')->findwhere(['id' => $productAttribute->text_value])->first();
                if (is_null($attribute_option) and !is_null($attr_option)) {
                    $new_attribute_option = app('Webkul\Attribute\Repositories\AttributeOptionRepository')->findwhere(['attribute_id' => $productAttribute->attribute_id, 'admin_name' => $attr_option->admin_name])->first();
                     if($new_attribute_option){
                         $productAttribute->text_value=$new_attribute_option->id;
                         $productAttribute->save();
                     }

                }

            $i++;
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }

}
