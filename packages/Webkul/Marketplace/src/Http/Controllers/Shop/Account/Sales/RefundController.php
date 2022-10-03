<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Account\Sales;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Marketplace\Http\Controllers\Shop\Controller;
use Webkul\Marketplace\Repositories\OrderRepository;

use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Sales\Repositories\InvoiceRepository as BaseInvoiceRepository;
use Webkul\Marketplace\Repositories\RefundRepository;
use PDF;
use Webkul\Sales\Services\RefundProcessor;

/**
 * Invoice controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class RefundController extends Controller
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

    protected $refund;


    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Marketplace\Repositories\OrderRepository   $order
     * @param  Webkul\Marketplace\Repositories\RefundRepository  $refund
     * @param  Webkul\Marketplace\Repositories\SellerRepository  $seller
     * @param  Webkul\Sales\Repositories\InvoiceRepository       $baseInvoice
     * @return void
     */
    public function __construct(
        OrderRepository $order,
        refundRepository $refund,
        SellerRepository $seller,
        BaseInvoiceRepository $baseInvoice
    )
    {
        $this->order = $order;

        $this->refundRepository= $refund;

        $this->seller = $seller;

        $this->baseInvoice = $baseInvoice;

        $this->_config = request('_config');

        $this->middleware('marketplace-seller');
    }

    public function index(){
        return view($this->_config['view']);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param int $orderId
     * @return \Illuminate\Http\Response
     */
    public function create($orderId)
    {
/*        if (! core()->getConfigData('marketplace.settings.general.can_create_invoice'))
            return redirect()->back();*/

        $order = app('Webkul\Sales\Repositories\OrderRepository')->find($orderId);

        $availableProcessors = array_map(function ($processorCode) {
            return [
                'code' => $processorCode,
                'title' => __($processorCode.'::app.title'),
            ];
        }, array_keys(array_filter(
            config('services.refund.processors'),
            function ($processorClass) use ($order) {
                $processorObject = new $processorClass($order);
                if (!$processorObject instanceof RefundProcessor)
                    return false;
                return $processorObject->isAvailable();
            }
        )));

        return view($this->_config['view'], compact('order', 'availableProcessors'));
    }

    public function store($orderId){

        $order = app('Webkul\Sales\Repositories\OrderRepository')->findOrFail($orderId);

        if (! $order->canRefund()) {
            session()->flash('error', trans('admin::app.sales.refunds.creation-error'));

            return redirect()->back();
        }

        $this->validate(request(), [
            'refund.items.*' => 'required|numeric|min:0',
        ]);

        $data = request()->all();



        $totals = app('Webkul\Sales\Repositories\RefundRepository')->getOrderItemsRefundSummary($data['refund']['items'], $orderId);



        $maxRefundAmount = $totals['grand_total']['price'] - $order->refunds()->sum('base_adjustment_refund');


        $refundAmount = $totals['grand_total']['price'] - $totals['shipping']['price'];
        if(isset( $data['refund']['shipping'])){
            $refundAmount=$refundAmount + $data['refund']['shipping'];
        }
        if(isset($data['refund']['adjustment_refund'])){
            $refundAmount=$refundAmount + $data['refund']['adjustment_refund'];
        }
        if(isset($data['refund']['adjustment_fee'])){
            $refundAmount=$refundAmount - $data['refund']['adjustment_fee'];
        }


        if (! $refundAmount) {
            session()->flash('error', trans('admin::app.sales.refunds.invalid-refund-amount-error'));

            return redirect()->back();
        }

        if ($refundAmount > $maxRefundAmount) {
            session()->flash('error', trans('admin::app.sales.refunds.refund-limit-error', ['amount' => core()->formatBasePrice($maxRefundAmount)]));

            return redirect()->back();
        }

        app('Webkul\Sales\Repositories\RefundRepository')->create(array_merge($data, ['order_id' => $orderId]));

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Refund']));

        return redirect()->route($this->_config['redirect'], $orderId);
    }
    public function updateQty($orderId){
        $data = app('Webkul\Sales\Repositories\RefundRepository')->getOrderItemsRefundSummary(request()->all(), $orderId);

        if (! $data) {
            return response('');
        }

        return response()->json($data);
    }


    /**
     * Show the view for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\View
     */
    public function view($order_id)
    {
        $refund = app('Webkul\Sales\Repositories\RefundRepository')->findWhere(['order_id' => $order_id])->first();

        return view($this->_config['view'], compact('refund'));
    }

}