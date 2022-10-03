<?php

namespace Webkul\Sales\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Event;
use Webkul\Core\Eloquent\Repository;
use Webkul\Sales\Contracts\OrderItem;

class OrderItemRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return OrderItem::class;
    }

    /**
     * @param  array  $data
     * @return \Webkul\Sales\Contracts\OrderItem
     */
    public function create(array $data)
    {
        if (isset($data['product']) && $data['product']) {
            $data['product_id'] = $data['product']->id;
            $data['product_type'] = get_class($data['product']);

            unset($data['product']);
        }

        return parent::create($data);
    }

    /**
     * @param  \Webkul\Sales\Contracts\OrderItem  $orderItem
     * @return \Webkul\Sales\Contracts\OrderItem
     */
    public function collectTotals($orderItem)
    {
        $qtyShipped = $qtyInvoiced = $qtyRefunded = 0;

        $totalInvoiced = $baseTotalInvoiced = 0;
        $taxInvoiced = $baseTaxInvoiced = 0;

        $totalRefunded = $baseTotalRefunded = 0;
        $taxRefunded = $baseTaxRefunded = 0;

        if ($orderItem->product->type === 'virtual') {
            $qtyShipped = $orderItem->qty_ordered;
        }

        foreach ($orderItem->invoice_items as $invoiceItem) {
            $qtyInvoiced += $invoiceItem->qty;

            $totalInvoiced += $invoiceItem->total;
            $baseTotalInvoiced += $invoiceItem->base_total;

            $taxInvoiced += $invoiceItem->tax_amount;
            $baseTaxInvoiced += $invoiceItem->base_tax_amount;
        }

        foreach ($orderItem->shipment_items as $shipmentItem) {
            $qtyShipped += $shipmentItem->qty;
        }

        foreach ($orderItem->refund_items as $refundItem) {
            $qtyRefunded += $refundItem->qty;

            $totalRefunded += $refundItem->total;
            $baseTotalRefunded += $refundItem->base_total;

            $taxRefunded += $refundItem->tax_amount;
            $baseTaxRefunded += $refundItem->base_tax_amount;
        }

        $orderItem->qty_shipped = $qtyShipped;
        $orderItem->qty_invoiced = $qtyInvoiced;
        $orderItem->qty_refunded = $qtyRefunded;

        $orderItem->total_invoiced = $totalInvoiced;
        $orderItem->base_total_invoiced = $baseTotalInvoiced;

        $orderItem->tax_amount_invoiced = $taxInvoiced;
        $orderItem->base_tax_amount_invoiced = $baseTaxInvoiced;

        $orderItem->amount_refunded = $totalRefunded;
        $orderItem->base_amount_refunded = $baseTotalRefunded;

        $orderItem->tax_amount_refunded = $taxRefunded;
        $orderItem->base_tax_amount_refunded = $baseTaxRefunded;

        $orderItem->save();

        return $orderItem;
    }

    /**
     * @param  \Webkul\Sales\Contracts\OrderItem  $orderItem
     * @return void
     */
    public function manageInventory($orderItem)
    {
        $orderItems = [];

        if ($orderItem->getTypeInstance()->isComposite()) {
            foreach ($orderItem->children as $child) {
                $orderItems[] = $child;
            }
        } else {
            $orderItems[] = $orderItem;
        }

        foreach ($orderItems as $item) {
            if (! $item->product) {
                continue;
            }

            $qty = $item->qty_ordered ?: $item->parent->qty_ordered;
            $item->product->productFlat->ordered_quantity=$item->product->productFlat->ordered_quantity+$qty;
            $item->product->productFlat->save();
        }
    }

    /**
     * Returns qty to product inventory after order cancelation
     *
     * @param  \Webkul\Sales\Contracts\OrderItem  $orderItem
     * @return void
     */
    public function returnQtyToProductInventory($orderItem)
    {

        $old_quantity=$orderItem->product->productFlat->quantity;


        if (($qty = $old_quantity - ($orderItem->qty_ordered ? $orderItem->qty_to_cancel : $orderItem->parent->qty_ordered)) < 0) {
            $qty = 0;
        }

        $orderItem->product->productFlat->ordered_quantity= $qty;
        $orderItem->product->productFlat->save();

        try {
            $inventory=$orderItem->product->productFlat->quantity - $orderItem->product->productFlat->ordered_quantity;
            $webhook=app('Webkul\Product\Repositories\ProductRepository')->sendWebhook($inventory,$orderItem->product->productFlat->seller_id, $orderItem->product->id);
        }catch (\Exception $e){
        }

    }
    public function getOrdersItemsByProduct($product_id){
        return $this->findWhere([
            'product_id' => $product_id
        ]);
    }
}