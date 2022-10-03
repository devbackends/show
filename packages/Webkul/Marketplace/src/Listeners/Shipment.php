<?php

namespace Webkul\Marketplace\Listeners;

use Illuminate\Support\Facades\Mail;
use Webkul\Marketplace\Repositories\ShipmentRepository;

/**
 * Shipment event handler
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Shipment
{
    /**
     * ShipmentRepository object
     *
     * @var Product
    */
    protected $shipment;

    /**
     * Create a new customer event listener instance.
     *
     * @param  Webkul\Marketplace\Repositories\ShipmentRepository $order
     * @return void
     */
    public function __construct(
        ShipmentRepository $shipment
    )
    {
        $this->shipment = $shipment;
    }

    /**
     * After sales shipment creation, creater marketplace shipment
     *
     * @param mixed $shipment
     */
    public function afterShipment($shipment)
    {
        $this->shipment->create(['shipment' => $shipment]);
    }
}