<?php

namespace Webkul\Sales\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Webkul\Sales\Contracts\RefundItem;

class RefundItemRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return RefundItem::class;
    }

    /**
     * Returns qty to product inventory after order refund
     *
     * @param  \Webkul\Sales\Contracts\Order  $orderItem
     * @param  int  $quantity
     * @return void
     */
    public function returnQtyToProductInventory($orderItem, $quantity)
    {
        if (! $product = $orderItem->product) {
            return;
        }

        if ($orderItem->qty_shipped && $quantity > $orderItem->qty_ordered - $orderItem->qty_shipped) {
            $nonShippedQty = $orderItem->qty_ordered - $orderItem->qty_shipped;

            if (($totalShippedQtyToRefund = $quantity - $nonShippedQty) > 0) {
                $shipmentItems = $orderItem->parent ? $orderItem->parent->shipment_items : $orderItem->shipment_items;

                foreach ($shipmentItems as $shipmentItem) {
                    if (! $totalShippedQtyToRefund) {
                        break;
                    }




                    if ($orderItem->parent) {
                        $shippedQty = $orderItem->qty_ordered
                                      ? ($orderItem->qty_ordered / $orderItem->parent->qty_ordered) * $shipmentItem->qty
                                      : $orderItem->parent->qty_ordered;
                    } else {
                        $shippedQty = $shipmentItem->qty;
                    }
                    
                    $shippedQtyToRefund = $totalShippedQtyToRefund > $shippedQty ? $shippedQty : $totalShippedQtyToRefund;

                    $totalShippedQtyToRefund = $totalShippedQtyToRefund > $shippedQty ? $totalShippedQtyToRefund - $shippedQty : 0;

                    $product->productFlat->quantity=$product->productFlat->quantity + $shippedQtyToRefund;
                    $product->productFlat->save();

                }

                $quantity -= $totalShippedQtyToRefund;
            }
        } elseif (! $orderItem->getTypeInstance()->isStockable()
                  && $orderItem->getTypeInstance()->showQuantityBox()
        ) {


            $product->productFlat->quantity=$product->productFlat->quantity + $quantity;
            $product->productFlat->save();
        }

        if ($quantity) {
/*            $orderedInventory = $product->ordered_inventories()
                                        ->where('channel_id', $orderItem->order->channel->id)
                                        ->first();

            if (! $orderedInventory) {
                return;
            }*/

            if (($qty = $product->productFlat->ordered_quantity - $quantity) < 0) {
                $qty = 0;
            }

            $product->productFlat->ordered_quantity= $qty;
            $product->productFlat->save();
        }
    }
}