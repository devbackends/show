<?php

namespace Devvly\DistributorImport\Console\Commands;

use DB;
use Illuminate\Console\Command;

class RemoveMarketplaceProducts extends Command
{
        protected $signature = 'remove-marketplace-products';

    protected $description = 'remove marketplace Product';

    public function handle()
    {

        // Get data
        $this->comment('copying data Marketplace Products');

        $marketplaceProducts = DB::SELECT("SELECT *  FROM marketplace_products");
        $this->output->progressStart(sizeof($marketplaceProducts));
        foreach ($marketplaceProducts as $marketplaceProduct) {

            if (isset($marketplaceProduct->product_id)) {
                $product = app("Webkul\Product\Repositories\ProductFlatRepository")->findWhere(['product_id' => $marketplaceProduct->product_id])->first();
                if ($product) {
                    $product->is_listing_fee_charged = $marketplaceProduct->is_listing_fee_charged;
                    $product->is_seller_new = $marketplaceProduct->new;
                    $product->is_seller_featured = $marketplaceProduct->featured;
                    $product->is_seller_approved = $marketplaceProduct->is_approved;
                    $product->shipping_type = $marketplaceProduct->shipping_type;
                    $product->marketplace_seller_id = $marketplaceProduct->marketplace_seller_id;
                    $product->save();
                }
            }
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();


        $this->comment('changing marketplace_product_id in marketplace_order_items to product_id');
        $marketplaceOrderItems = app('Webkul\Marketplace\Repositories\OrderItemRepository')->all();
        $this->output->progressStart(sizeof($marketplaceOrderItems));
        foreach ($marketplaceOrderItems as $item) {
            if (isset($item->marketplace_product_id)) {
                $marketplaceProduct = DB::SELECT("SELECT *  FROM marketplace_products where id=".$item->marketplace_product_id);
                if(isset($marketplaceProduct[0])) {
                    $product_id=$marketplaceProduct[0]->product_id;
                    $item->product_id=$product_id;
                    $item->save();
                }
            }
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();


        $this->comment('Move inventories into product_flat level');
        $productInventories = DB::SELECT("SELECT *  FROM product_inventories");
        $this->output->progressStart(sizeof($productInventories));
        foreach ($productInventories as $productInventory) {
            if(isset($productInventory->product_id)){
                $productFlat =  app('Webkul\Product\Repositories\ProductFlatRepository')->findWhere(['product_id'=>$productInventory->product_id,'marketplace_seller_id'=>$productInventory->vendor_id])->first();
                if($productFlat) {
                    $productFlat->quantity=$productInventory->qty;
                    $productFlat->save();
                }
            }
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();

        $this->comment('Move ordered_inventories into product_flat level');
        $product_ordered_inventories = DB::SELECT("SELECT *  FROM product_ordered_inventories");
        $this->output->progressStart(sizeof($product_ordered_inventories));
        foreach ($product_ordered_inventories as $product_ordered_inventory) {
            if(isset($product_ordered_inventory->product_id)){
                $productFlat =  app('Webkul\Product\Repositories\ProductFlatRepository')->findWhere(['product_id'=>$product_ordered_inventory->product_id])->first();
                if($productFlat) {
                    $productFlat->ordered_quantity=$product_ordered_inventory->qty;
                    $productFlat->save();
                }
            }
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();


    }
}