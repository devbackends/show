<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Account\Sales;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Marketplace\Http\Controllers\Shop\Controller;
use Webkul\Marketplace\Repositories\OrderRepository;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Sales\Repositories\OrderItemRepository as BaseOrderItem;
use Webkul\Sales\Repositories\ShipmentRepository;

/**
 * Shipment controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ShipmentController extends Controller
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
     * SellerRepository object
     *
     * @var mixed
     */
    protected $seller;

    /**
     * OrderItemRepository object
     *
     * @var mixed
     */
    protected $baseOrderItem;

    /**
     * ShipmentRepository object
     *
     * @var mixed
     */
    protected $shipment;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Marketplace\Repositories\OrderRepository    $order
     * @param  Webkul\Marketplace\Repositories\SellerRepository   $seller
     * @param  Webkul\Sales\Repositories\OrderItemRepository      $baseOrderItem
     * @param  Webkul\Marketplace\Repositories\ShipmentRepository $shipment
     * @return void
     */
    public function __construct(
        OrderRepository $order,
        SellerRepository $seller,
        BaseOrderItem $baseOrderItem,
        ShipmentRepository $shipment
    )
    {
        $this->order = $order;

        $this->seller = $seller;

        $this->baseOrderItem = $baseOrderItem;

        $this->shipment = $shipment;

        $this->_config = request('_config');

        $this->middleware('marketplace-seller');
    }

    /**
     * Show the view for the specified resource.
     *
     * @param  int  $orderId
     * @return \Illuminate\Http\Response
     */
    public function create($orderId)
    {
        if (! core()->getConfigData('marketplace.settings.general.can_create_shipment'))
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

        if (! $sellerOrder->canShip()) {
            session()->flash('error', 'Order shipment creation is not allowed.');

            return redirect()->back();
        }

        $this->validate(request(), [
            'shipment.carrier_title' => 'required',
            'shipment.track_number' => 'required',
            'shipment.items.*' => 'required|numeric|min:0',
        ]);

        $data = array_merge(request()->all(), [
                'vendor_id' => $sellerOrder->marketplace_seller_id
            ]);

        if (! $this->isInventoryValidate($data)) {
            session()->flash('error', 'Requested quantity is invalid or not available.');

            return redirect()->back();
        }

        $this->shipment->create(array_merge($data, [
                'order_id' => $orderId
            ]));

        session()->flash('success', 'Shipment created successfully.');

        return redirect()->route($this->_config['redirect'], $orderId);
    }

    /**
     * Checks if requested quantity available or not
     *
     * @param array $data
     * @return boolean
     */
    public function isInventoryValidate(&$data)
    {

        $valid = false;
        if(isset($data['shipment']['items'])) {
            foreach ($data['shipment']['items'] as $itemId => $qty ) {

                    $orderItem = $this->baseOrderItem->find($itemId);

                    $product = ($orderItem->type == 'configurable')
                        ? $orderItem->child->product
                        : $orderItem->product;



                    if ($orderItem->qty_to_ship < $qty ) {
                        return false;
                    }

                    $valid = true;

            }
        }
        return $valid;
    }
}