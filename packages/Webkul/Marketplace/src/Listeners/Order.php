<?php

namespace Webkul\Marketplace\Listeners;

use Illuminate\Support\Facades\Mail;
use Webkul\Marketplace\Repositories\OrderRepository;
use Webkul\Marketplace\Mail\NewOrderNotification;
use Webkul\Marketplace\Mail\NewInvoiceNotification;
use Webkul\Marketplace\Mail\NewShipmentNotification;

/**
 * Order event handler
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Order
{
    /**
     * OrderRepository object
     *
     * @var Product
    */
    protected $order;

    /**
     * Create a new customer event listener instance.
     *
     * @param  Webkul\Marketplace\Repositories\OrderRepository $order
     * @return void
     */
    public function __construct(
        OrderRepository $order
    )
    {
        $this->order = $order;
    }

    /**
     * After sales order creation, add entry to marketplace order table
     *
     * @param mixed $order
     */
    public function afterPlaceOrder($order)
    {
        $this->order->create(['order' => $order]);
    }

    /**
     * After sales order cancellation
     *
     * @param mixed $order
     */
    public function afterOrderCancel($order)
    {
        $this->order->cancel(['order' => $order]);
    }

        /**
     * @param mixed $order
     *
     * Send new order confirmation mail to the customer
     */
    public function sendNewOrderMail($order)
    {
        try {
            Mail::to($order->seller->customer->email)->send(new NewOrderNotification($order));
        } catch (\Exception $e) {

        }
    }

    /**
     * @param mixed $invoice
     *
     * Send new invoice mail to the customer
     */
    public function sendNewInvoiceMail($invoice)
    {
        try {
            Mail::send(new NewInvoiceNotification($invoice));
        } catch (\Exception $e) {
        }
    }

    /**
     * @param mixed $shipment
     *
     * Send new shipment mail to the customer
     */
    public function sendNewShipmentMail($shipment)
    {
        try {
            //Mail::send(new NewShipmentNotification($shipment)); Disable per managment team request
        } catch (\Exception $e) {

        }
    }
}