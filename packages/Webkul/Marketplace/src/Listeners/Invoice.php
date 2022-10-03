<?php

namespace Webkul\Marketplace\Listeners;

use Illuminate\Support\Facades\Mail;
use Webkul\Marketplace\Repositories\InvoiceRepository;

/**
 * Invoice event handler
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Invoice
{
    /**
     * InvoiceRepository object
     *
     * @var Product
    */
    protected $invoice;

    /**
     * Create a new customer event listener instance.
     *
     * @param  Webkul\Marketplace\Repositories\InvoiceRepository $order
     * @return void
     */
    public function __construct(
        InvoiceRepository $invoice
    )
    {
        $this->invoice = $invoice;
    }

    /**
     * After sales invoice creation, creater marketplace invoice
     *
     * @param mixed $invoice
     */
    public function afterInvoice($invoice)
    {
        $this->invoice->create(['invoice' => $invoice]);
    }
}