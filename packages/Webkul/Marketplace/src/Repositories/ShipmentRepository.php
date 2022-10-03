<?php

namespace Webkul\Marketplace\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Event;
use Webkul\Product\Repositories\ProductFlatRepository;
/**
 * Seller Shipment Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ShipmentRepository extends Repository
{
    /**
     * SellerRepository object
     *
     * @var Object
     */
    protected $sellerRepository;

    /**
     * ProductFlatRepository object
     *
     * @var Object
     */
    protected $productFlatRepository;

    /**
     * OrderRepository object
     *
     * @var Object
     */
    protected $orderRepository;

    /**
     * ShipmentItemRepository object
     *
     * @var Object
     */
    protected $shipmentItemRepository;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\Marketplace\Repositories\SellerRepository       $sellerRepository
     * @param  Webkul\Product\Repositories\ProductFlatRepository      $productFlatRepository
     * @param  Webkul\Marketplace\Repositories\OrderRepository        $orderRepository
     * @param  Webkul\Marketplace\Repositories\ShipmentItemRepository $shipmentItemRepository
     * @param  Illuminate\Container\Container                         $app
     * @return void
     */
    public function __construct(
        SellerRepository $sellerRepository,
        ProductFlatRepository $productFlatRepository,
        OrderRepository $orderRepository,
        ShipmentItemRepository $shipmentItemRepository,
        App $app
    )
    {
        $this->sellerRepository = $sellerRepository;

        $this->productFlatRepository = $productFlatRepository;

        $this->orderRepository = $orderRepository;

        $this->shipmentItemRepository = $shipmentItemRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Marketplace\Contracts\Shipment';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $shipment = $data['shipment'];

        Event::dispatch('marketplace.sales.shipment.save.before', $data);

        $sellerShipments = [];

        foreach ($shipment->items()->get() as $item) {
            if (isset($item->additional['seller_info']) && !$item->additional['seller_info']['is_owner']) {
                $seller = $this->sellerRepository->find($item->additional['seller_info']['seller_id']);
                $sellers[] = $this->sellerRepository->find($item->additional['seller_info']['seller_id']);
            } else {
                $seller = $this->productFlatRepository->getSellerByProductId($item->product_id);
                $sellers[] = $this->productFlatRepository->getSellerByProductId($item->product_id);
            }

            if (! $seller)
                continue;

            $sellerOrder = $this->orderRepository->findOneWhere([
                    'order_id' => $shipment->order->id,
                    'marketplace_seller_id' => $seller->id,
                ]);

            if (! $sellerOrder)
                continue;

            $sellerShipment = $this->findOneWhere([
                    'shipment_id' => $shipment->id,
                    'marketplace_order_id' => $sellerOrder->id,
                ]);

            if (! $sellerShipment) {
                $sellerShipments[] = $sellerShipment = parent::create([
                        'total_qty' => $item->qty,
                        'shipment_id' => $shipment->id,
                        'marketplace_order_id' => $sellerOrder->id,
                    ]);
            } else {
                $sellerShipment->total_qty += $item->qty;

                $sellerShipment->save();
            }

            $sellerShipmentItem = $this->shipmentItemRepository->create([
                    'marketplace_shipment_id' => $sellerShipment->id,
                    'shipment_item_id' => $item->id,
                ]);


            $product = ($item->order_item->type == 'configurable')
                    ? $item->order_item->child->product
                    : $item->order_item->product;

            $this->shipmentItemRepository->updateProductInventory([
                    'shipment' => $shipment,
                    'product' => $product,
                    'qty' => $item->qty,
                    'vendor_id' => $seller->id
                ]);
        }

        foreach ($sellers as $seller) {
            if ($seller) {
                $sellerOrders = $this->orderRepository->findWhere(['order_id' => $shipment->order->id, 'marketplace_seller_id' => $seller->id]);

                foreach ($sellerOrders as $sellerOrder) {
                    $this->orderRepository->updateOrderStatus($sellerOrder);
                }
            }
        }

        foreach ($sellerShipments as $sellerShipment) {
            Event::dispatch('marketplace.sales.shipment.save.after', $sellerShipment);
        }
    }
}