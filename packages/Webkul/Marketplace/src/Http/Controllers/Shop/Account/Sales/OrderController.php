<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Account\Sales;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Marketplace\Http\Controllers\Shop\Controller;
use Webkul\Marketplace\Repositories\OrderRepository;
use Webkul\Marketplace\Service\SellerType;
use Webkul\Sales\Models\Order;
use Webkul\Marketplace\Repositories\SellerRepository;

/**
 * Order controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrderController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * SellerRepository object
     *
     * @var mixed
     */
    protected $sellerRepository;

    /**
     * OrderRepository object
     *
     * @var mixed
     */
    protected $orderRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Marketplace\Repositories\SellerRepository $sellerRepository
     * @param  Webkul\Marketplace\Repositories\OrderRepository  $orderRepository
     * @return void
     */
    public function __construct(
        SellerRepository $sellerRepository,
        OrderRepository $orderRepository
    )
    {
        $this->sellerRepository = $sellerRepository;

        $this->orderRepository = $orderRepository;

        $this->_config = request('_config');

        $this->middleware('marketplace-seller');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isSeller = $this->sellerRepository->isSeller(auth()->guard('customer')->user()->id);

        if (! $isSeller) {
            return redirect()->route('marketplace.account.seller.create');
        }

        return view($this->_config['view']);
    }

    /**
     * Show the view for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $seller = $this->sellerRepository->findOneWhere([
            'customer_id' => auth()->guard('customer')->user()->id
        ]);

        $sellerOrder = $this->orderRepository->findOneWhere([
            'order_id' => $id,
            'marketplace_seller_id' => $seller->id
        ]);

        if (! $sellerOrder){
            abort('404');
        }

        return view($this->_config['view'], compact('sellerOrder'));
    }

    /**
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        $seller = $this->sellerRepository->findOneWhere([
            'customer_id' => auth()->guard('customer')->user()->id
        ]);

        $sellerOrder = $this->orderRepository->findOneWhere([
            'order_id' => $id,
            'marketplace_seller_id' => $seller->id
        ]);

        $sellerOrder->order = $sellerOrder->order()->first();
        $sellerOrder->order->shipping_address = $sellerOrder->order->getShippingAddressAttribute();
        $sellerOrder->seller = $sellerOrder->seller()->first();

        return response()->json([
            'order' => $sellerOrder
        ]);
    }

    /**
     * Cancel action for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        if (! core()->getConfigData('marketplace.settings.general.can_cancel_order'))
            return redirect()->back();

        $result = $this->orderRepository->sellerCancelOrder($id);

        if ($result) {
            session()->flash('success', trans('admin::app.response.cancel-success', ['name' => 'Order']));
        } else {
            session()->flash('error', trans('admin::app.response.cancel-error', ['name' => 'Order']));
        }

        return redirect()->back();
    }

    public function payCashsale($order_id){

        return view($this->_config['view'], compact('order_id'));
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function payCashsaleStore($order_id){
        $this->validate(request(), [
            'cashsale_transaction_number' => 'required'
        ]);
        $order = app('Webkul\Sales\Repositories\OrderRepository')->findOrFail($order_id);
        $marketplace_order=app('Webkul\Marketplace\Repositories\OrderRepository')->findWhere(['order_id'=>$order_id])->first();

        // Charge order commission if needed
        if (in_array($order->payment->method, ['cashsale', 'check', 'banktransfer'])) {
            $seller = $this->sellerRepository->findOneWhere([
                'customer_id' => auth()->guard('customer')->user()->id
            ]);

            $sellerOrder = $this->orderRepository->findOneWhere([
                'order_id' => $order_id,
                'marketplace_seller_id' => $seller->id
            ]);

            (new SellerType($seller))->orderCommission($sellerOrder, isset($order->payment->method) ? $order->payment->method : '' );
        }


        $note = request()->request->get('note');
        if ($note) {
            $order->notes = $note;
        }
        $marketplace_order->status='processing';
        $marketplace_order->save();
        $order->status='processing';
        $order->cashsale_transaction_number = request()->request->get('cashsale_transaction_number');
        $order->cashsale_status = 'paid';
        $order->save();

        session()->flash('success', 'Cashsale order is paid successfully');

        return redirect()->route('marketplace.account.orders.view', $order_id);
    }

}