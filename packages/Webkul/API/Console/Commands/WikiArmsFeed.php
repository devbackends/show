<?php

namespace Webkul\API\Console\Commands;

use Illuminate\Support\Facades\Log;
use Webkul\SAASCustomizer\Repositories\ProductRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Webkul\Marketplace\Helpers\Marketplace;
use Webkul\Marketplace\Models\Seller;
use Webkul\Product\Type\AbstractType;
use Webkul\SAASCustomizer\Models\Product\ProductFlat;


class WikiArmsFeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wikiarmsfeed:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Caches the product feed';

    /**
     * ProductRepository Object
     */
    protected $productRepository;

    /**
     * ProductsFeed constructor.
     *
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
        parent::__construct();
    }

    public function handle()
    {
        $this->getProducts();
    }

    protected function getProducts(){
        // since we dont need pagination, we set it to unlimited
        $stop = null;
        // attribute_family_ids:
        // 47: Ammunition
        // 48: Firearms
        $attrFamilyIds = [47, 48];
        $catIds = [66,74];
        $products = $this->productRepository->getAll(null, $attrFamilyIds, ['limit' => 1000000]);
        $productItems = $products->items();
        $skipped = 0;
        $productsFeed = [];
        if ($productItems) {

            $helper = new Marketplace();
            /** @var ProductFlat $productFlat */
            foreach ($productItems as $productFlat) {
                /** @var AbstractType $productType */
                    $productFlat->seller = $productFlat->marketplace_seller_id;

                // skip the product if not in stock:
                $fProduct = $helper->getFormattedProduct($productFlat, $productFlat->marketplace_seller_id );
                $productType = $productFlat->product->getTypeInstance();
                $sellerId = $productFlat->marketplace_seller_id ;
                $available = $productType->isSaleable($sellerId);
                if(!$available){
                    $skipped++;
                    continue;
                }
                // Collect feed data:
                $productViewHelper = app('Webkul\Product\Helpers\View');
                $customAttributes = $productViewHelper->getAdditionalData($fProduct);
                $foundAttributes = [];
                $requiredFields = [
                    'man_part_num',
                    'caliber_multiselect',
                    'round_count',
                    'UPC',
                ];
                foreach ($customAttributes as $customAttribute) {
                    if (in_array($customAttribute['code'], $requiredFields)) {
                        $foundAttributes[$customAttribute['code']] = $customAttribute;
                    }
                }
                $priceComponents = $productType->getPriceComponents($productFlat->marketplace_seller_id );
                $cleanPrice = number_format((float)$priceComponents['price'], 2, '.', '');
                $url = url()->to('/') . '/product/' . $fProduct->url_key;
                $type = $fProduct->attribute_family_id == 47? "ammunition": 'firearm';
                $productFeed = [
                    'type' => $type,
                    'description' => $fProduct->name,
                    'url' => $url,
                    'price' => $cleanPrice,
                ];
                // check if the following attributes exist and their values are set:
                if (isset($foundAttributes['round_count']) && $foundAttributes['round_count']['value']) {
                    $productFeed['numrounds'] = $foundAttributes['round_count']['value'];
                }
                if (isset($foundAttributes['caliber_multiselect']) && !empty($foundAttributes['caliber_multiselect']['value'])) {
                    $productFeed['caliber'] = $foundAttributes['caliber_multiselect']['value'];
                }
                if (isset($foundAttributes['man_part_num']) && !empty($foundAttributes['man_part_num']['value'])) {
                    $productFeed['MPN'] = $foundAttributes['man_part_num']['value'];
                }
                if (isset($foundAttributes['UPC']) && !empty($foundAttributes['UPC']['value'])) {
                    $productFeed['UPC'] = $foundAttributes['UPC']['value'];
                }
                // skip the product if any of the required fields are missing:
                $requiredFields = [
                    'description',
                    'url',
                    'price',
                    'numrounds'
                ];
                foreach ($requiredFields as $field) {
                    $valid = false;
                    if (isset($productFeed[$field])) {
                        if (is_string($productFeed[$field]) && !empty($productFeed[$field])){
                            $valid = true;
                        }
                        else if(is_int($productFeed[$field])) {
                            $valid = true;
                        }
                    }
                    // exclude ammunition products that don't have numrounds field
                    elseif ($field === 'numrounds' && $type !== 'ammunition'){
                        $valid = true;
                    }
                    if (!$valid) {
                        break;
                    }
                }
                if (!$valid) {
                    continue;
                }
                array_push($productsFeed, $productFeed);
            }
        }
        $json_feed = json_encode($productsFeed);
        Storage::disk('wassabi_private')->put('wikiarms_feed.json', $json_feed);
    }
}
