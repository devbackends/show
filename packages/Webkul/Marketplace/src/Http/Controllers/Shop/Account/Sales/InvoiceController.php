<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Account\Sales;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Marketplace\Http\Controllers\Shop\Controller;
use Webkul\Marketplace\Repositories\OrderRepository;
use Webkul\Marketplace\Repositories\InvoiceRepository;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Sales\Repositories\InvoiceRepository as BaseInvoiceRepository;
use PDF;

/**
 * Invoice controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class InvoiceController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * OrderRepository object
     *
     * @var mixed
     */
    protected $order;

    /**
     * InvoiceRepository object
     *
     * @var mixed
     */
    protected $invoice;

    /**
     * SellerRepository object
     *
     * @var mixed
     */
    protected $seller;

    /**
     * InvoiceRepository object
     *
     * @var mixed
     */
    protected $baseInvoice;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Marketplace\Repositories\OrderRepository   $order
     * @param  Webkul\Marketplace\Repositories\InvoiceRepository $invoice
     * @param  Webkul\Marketplace\Repositories\SellerRepository  $seller
     * @param  Webkul\Sales\Repositories\InvoiceRepository       $baseInvoice
     * @return void
     */
    public function __construct(
        OrderRepository $order,
        InvoiceRepository $invoice,
        SellerRepository $seller,
        BaseInvoiceRepository $baseInvoice
    )
    {
        $this->order = $order;

        $this->invoice = $invoice;

        $this->seller = $seller;

        $this->baseInvoice = $baseInvoice;

        $this->_config = request('_config');

        $this->middleware('marketplace-seller');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param int $orderId
     * @return \Illuminate\Http\Response
     */
    public function create($orderId)
    {
        if (! core()->getConfigData('marketplace.settings.general.can_create_invoice'))
            return redirect()->back();

        $seller = $this->seller->findOneWhere([
            'customer_id' => auth()->guard('customer')->user()->id
        ]);

        $sellerOrder = $this->order->findOneWhere([
            'order_id' => $orderId,
            'marketplace_seller_id' => $seller->id
        ]);

        return view($this->_config['view'], compact('sellerOrder'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param int $orderId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $orderId)
    {
        $seller = $this->seller->findOneWhere([
            'customer_id' => auth()->guard('customer')->user()->id
        ]);

        $sellerOrder = $this->order->findOneWhere([
            'order_id' => $orderId,
            'marketplace_seller_id' => $seller->id
        ]);

        if (! $sellerOrder->canInvoice()) {
            session()->flash('error', 'Order invoice creation is not allowed.');

            return redirect()->back();
        }

        $this->validate(request(), [
            'invoice.items.*' => 'required|numeric|min:0',
        ]);

        $data = request()->all();

        $haveProductToInvoice = false;
        foreach ($data['invoice']['items'] as $itemId => $qty) {
            if ($qty) {
                $haveProductToInvoice = true;
                break;
            }
        }

        if (! $haveProductToInvoice) {
            session()->flash('error', 'Invoice can not be created without products.');

            return redirect()->back();
        }

        $this->baseInvoice->create(array_merge($data, ['order_id' => $orderId]));

        session()->flash('success', 'Invoice created successfully.');

        return redirect()->route($this->_config['redirect'], $orderId);
    }

    /**
     * Print and download the for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print($id)
    {
        $sellerInvoice = $this->invoice->findOneByField('marketplace_order_id', $id);

        $pdf = PDF::loadView('marketplace::shop.sellers.account.sales.invoices.pdf', compact('sellerInvoice'))->setPaper('a4');

        return $pdf->download('invoice-' . $sellerInvoice->invoice->created_at->format('d-m-Y') . '.pdf');
    }
}