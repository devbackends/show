<?php

namespace Webkul\Sales\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Event;
use Webkul\Core\Eloquent\Repository;
use Webkul\Sales\Contracts\InvoiceItem;

class InvoiceItemRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return InvoiceItem::class;
    }

    /**
     * @param  array  $data
     * @return void
     */
    public function updateProductInventory($data)
    {
        $data['product']->productFlat->ordered_quantity= $data['product']->productFlat->ordered_quantity - $data['qty'];
        $data['product']->productFlat->quantity= $data['product']->productFlat->quantity + $data['qty'];
        $data['product']->productFlat->save();

        Event::dispatch('catalog.product.update.after', $data['product']);
    }
}