<?php

namespace Webkul\Sales\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Event;
use Webkul\Sales\Contracts\ShipmentItem;

class ShipmentItemRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return ShipmentItem::class;
    }

    /**
     * @param  array  $data
     * @return void
     */
    public function updateProductInventory($data)
    {
        if (! $data['product']) {
            return;
        }

        if($data['product']->productFlat->ordered_quantity - $data['qty'] >= 0){

            $data['product']->productFlat->ordered_quantity=$data['product']->productFlat->ordered_quantity -  $data['qty'];
            $data['product']->productFlat->save();

        }else{
            $data['product']->productFlat->ordered_quantity=0;
            $data['product']->productFlat->save();
        }

        $quantity = $data['product']->productFlat->quantity;

        if (! $quantity) {
            return;
        }

        if (($qty = $quantity - $data['qty']) < 0) {
            $qty = 0;
        }

        $data['product']->productFlat->quantity= $qty;
        $data['product']->productFlat->save();
        Event::dispatch('catalog.product.update.after', $data['product']);
    }
}