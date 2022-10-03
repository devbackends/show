<?php

namespace Webkul\Marketplace\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Seller Order Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrderItemRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Marketplace\Contracts\OrderItem';
    }

    /**
     * @param mixed $sellerOrderItem
     * @return mixed
     */
    public function collectTotals($sellerOrderItem)
    {
        $commissionPercentage =  $sellerOrderItem->order->commission_percentage;

        $sellerOrderItem->commission_invoiced = $sellerOrderItem->base_commission_invoiced = 0;
        $sellerOrderItem->seller_total_invoiced = $sellerOrderItem->base_seller_total_invoiced = 0;

        foreach ($sellerOrderItem->item->invoice_items as $invoiceItem) {
            $sellerOrderItem->commission_invoiced += $commission = ($invoiceItem->total * $commissionPercentage) / 100;
            $sellerOrderItem->base_commission_invoiced += $baseCommission = ($invoiceItem->base_total * $commissionPercentage) / 100;

            $sellerOrderItem->seller_total_invoiced += $invoiceItem->total + $invoiceItem->tax_amount - $commission;
            $sellerOrderItem->base_seller_total_invoiced += $invoiceItem->base_total + $invoiceItem->base_tax_amount - $baseCommission;
        }

        $sellerOrderItem->save();

        return $sellerOrderItem;
    }

    public function getOrdersItemsByProduct($product_id){
        return $this->findWhere([
            'product_id' => $product_id
        ]);
    }
}