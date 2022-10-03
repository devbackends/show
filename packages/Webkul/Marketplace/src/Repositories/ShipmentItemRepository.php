<?php

namespace Webkul\Marketplace\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Seller Shipment Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ShipmentItemRepository extends Repository
{
    protected $guarded = ['id', 'child', 'created_at', 'updated_at'];
    
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Marketplace\Contracts\ShipmentItem';
    }

    /**
     * @param array $data
     * @return void
     */
    public function updateProductInventory($data)
    {

/*        $data['product']->productFlat->ordered_quantity= $data['product']->productFlat->ordered_quantity - $data['qty'];
        $data['product']->productFlat->quantity= $data['product']->productFlat->quantity - $data['qty'];
        $data['product']->productFlat->save();*/

    }
}