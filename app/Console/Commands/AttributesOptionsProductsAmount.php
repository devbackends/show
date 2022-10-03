<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Webkul\Attribute\Models\AttributesOptionsProductsAmount as AttributesOptionsProductsAmountModel;
use Webkul\Product\Models\Product;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeOption;
class AttributesOptionsProductsAmount extends Command
{

    const CHUNK_PRODUCT_AMOUNT = 5000;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate-attribute-options-products-amount {next}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate fillterable attributes options products amount';

    /**
     * Execute the console command.
     *
     * @return null
     */
    public function handle()
    {
        $next = $this->argument('next');

        // Set memory limit
        ini_set('memory_limit', '6000M');

        // Truncate `attributes_options_products_amount` table
        if ($next == 0 || $next == 'all') {
            AttributesOptionsProductsAmountModel::truncate();
            $this->comment('Table cleaned');
        }
/*        $products=app('Webkul\Product\Repositories\ProductFlatRepository')->getAllIndexedData();*/
        // Get all products with options and categories
        $products = Product::query()->select('products.*', 'product_flat.marketplace_seller_id')
            ->Join('product_flat', 'products.id', '=', 'product_flat.product_id')
            ->where('product_flat.status',1)
            ->where('product_flat.parent_id',null)
            ->whereRaw('(IF(products.type != "booking" AND products.type != "configurable",  product_flat.quantity - product_flat.ordered_quantity , 1 )) > 0')
            ->get();

        $this->comment('All products received');

        if ($next != 'all') {
            $products = $products->slice($next, self::CHUNK_PRODUCT_AMOUNT);
        }

        // Group by options and categories
        $options = $this->groupProductsBySellersOptionsAndCategories($products);
        $this->comment('Options count: '.count($options));

        // Save products amount to db
        if ($this->saveDataToDB($options)) {
            $this->info('Options saved to db');
        } else {
            $this->error('Error while trying to store chunk in db');
        }

        if ($next != 'all' && $products->count() == self::CHUNK_PRODUCT_AMOUNT) {
            $this->info('Next argument: '.($next + self::CHUNK_PRODUCT_AMOUNT));
        } else {
            $this->info('Finish');
        }

        return 1;
    }

    protected function chunkProducts($products): array
    {
        // Get products count
        $count = $products->count();

        // Calculate how many chunks we need
        $chunksCount = $count / self::CHUNK_PRODUCT_AMOUNT;
        if ($chunksCount < 1) {
            return [$products];
        } else {
            $chunks = [];
            $chunksCount = ceil($chunksCount);

            for ($i = 0; $i < $chunksCount; $i++) {
                $index = $i * self::CHUNK_PRODUCT_AMOUNT;
                $chunks[] = $products->slice($index, self::CHUNK_PRODUCT_AMOUNT);
            }
            return $chunks;
        }
    }

    protected function groupProductsBySellersOptionsAndCategories($products): array
    {
        $optionsBySeller = [];
        $multiselectAttriburtesIds = Attribute::query()->select('*')->where('type', '=', 'multiselect')->get()->pluck('id')->toArray();
        $selectAttriburtesIds = Attribute::query()->select('*')->where('type', '=', 'select')->get()->pluck('id')->toArray();

        $this->output->progressStart(count($products));
        foreach ($products as $product) {
            /*$product= app('Webkul\Product\Repositories\productRepository')->find($product->product_id);*/
            foreach ($product->attribute_values as $attribute_value) {

                if (!in_array($attribute_value->attribute_id, $selectAttriburtesIds) && !in_array($attribute_value->attribute_id, $multiselectAttriburtesIds))
                    continue;

                if (in_array($attribute_value->attribute_id, $selectAttriburtesIds) && !$attribute_value->integer_value)
                    continue;
                if(in_array($attribute_value->attribute_id, $selectAttriburtesIds)){
                    if (!AttributeOption::query()->select('*')->find($attribute_value->integer_value))
                        continue;
                }

                if (in_array($attribute_value->attribute_id, $multiselectAttriburtesIds) && (!$attribute_value->text_value))
                    continue;

                if (in_array($attribute_value->attribute_id, $multiselectAttriburtesIds)){
                    $attribute_options = explode(",", $attribute_value->text_value);
                    $toBeRemovedKey=[];
                    foreach ($attribute_options as $key =>  $attribute_option) {
                        if (!AttributeOption::query()->select('*')->find($attribute_option)){
                            array_push($toBeRemovedKey,$key);
                        }
                    }
                    foreach ($toBeRemovedKey as $key){
                        unset($attribute_options[$key]);
                    }
                    if(sizeof($toBeRemovedKey) > 0){
                        $attribute_value->text_value=implode(',',$attribute_options);
                        $attribute_value->save();
                    }
                }

                if (is_null($product->marketplace_seller_id)) {
                    $product->marketplace_seller_id = 0;
                }

                if (!isset($optionsBySeller[$product->marketplace_seller_id]))
                    $optionsBySeller[$product->marketplace_seller_id] = [];
                if (in_array($attribute_value->attribute_id, $selectAttriburtesIds)) {
                    if (!isset($optionsBySeller[$product->marketplace_seller_id][(int)$attribute_value->integer_value]))
                        $optionsBySeller[$product->marketplace_seller_id][(int)$attribute_value->integer_value] = [];
                }

                if (in_array($attribute_value->attribute_id, $multiselectAttriburtesIds)) {
                    if($attribute_value->text_value){
                        $attribute_options = explode(",", $attribute_value->text_value);
                        foreach ($attribute_options as $attribute_option) {
                            if (!isset($optionsBySeller[$product->marketplace_seller_id][(int)$attribute_option]))
                                $optionsBySeller[$product->marketplace_seller_id][(int)$attribute_option] = [];
                        }
                    }
                }



                foreach ($product->categories as $category) {
                    if (in_array($attribute_value->attribute_id, $selectAttriburtesIds)) {
                        if (!isset($optionsBySeller[$product->marketplace_seller_id][(int)$attribute_value->integer_value][(int)$category->id])) {
                            $optionsBySeller[$product->marketplace_seller_id][(int)$attribute_value->integer_value][(int)$category->id] = 0;
                        }
                         $optionsBySeller[$product->marketplace_seller_id][(int)$attribute_value->integer_value][(int)$category->id]++;

                    }
                    if (in_array($attribute_value->attribute_id, $multiselectAttriburtesIds)) {
                        foreach ($attribute_options as $attribute_option) {
                            if (!isset($optionsBySeller[$product->marketplace_seller_id][(int)$attribute_option][(int)$category->id])) {
                                $optionsBySeller[$product->marketplace_seller_id][(int)$attribute_option][(int)$category->id] = 0;
                            }
                                $optionsBySeller[$product->marketplace_seller_id][(int)$attribute_option][(int)$category->id]++;

                        }
                    }
                }

            }
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();

        return $optionsBySeller;
    }

    protected function saveDataToDB(array $options): bool
    {
        $dbRows = [];
        foreach ($options as $sellerId => $data) {
            foreach ($data as $optionId => $categories) {
                foreach ($categories as $categoryId => $amount) {
                    array_push($dbRows, [
                        'seller_id' => $sellerId,
                        'category_id' => $categoryId,
                        'option_id' => $optionId,
                        'products_amount' => $amount,
                    ]);
                }
            }
        }

        $this->comment('Db rows count: '.count($dbRows));
        if ($this->argument('next') === 'all') {
            return AttributesOptionsProductsAmountModel::insert($dbRows);
        } else {
            foreach ($dbRows as $row) {
                try {
                    AttributesOptionsProductsAmountModel::query()->updateOrInsert([
                        'seller_id' => $row['seller_id'],
                        'category_id' => $row['category_id'],
                        'option_id' => $row['option_id']
                    ], [
                        'products_amount' => DB::raw('products_amount+'.$row['products_amount'])
                    ]);
                } catch (\Throwable $exception) {}
            }
            return true;
        }
    }

}
