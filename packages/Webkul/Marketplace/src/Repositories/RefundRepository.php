<?php

namespace Webkul\Marketplace\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Event;
use Webkul\Product\Repositories\ProductFlatRepository;
/**
 * Seller Refund Reposotory
 *
 * @author    Naresh Verma <naresh.verma327@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class RefundRepository extends Repository
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
     * OrderItemRepository object
     *
     * @var Object
     */
    protected $orderItemRepository;

    /**
     * InvoiceItemRepository object
     *
     * @var Object
     */
    protected $invoiceItemRepository;

    /**
     * RefundItemRepository object
     *
     * @var Object
     */
    protected $refundItemRepository;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\Marketplace\Repositories\SellerRepository      $sellerRepository
     * @param  Webkul\Product\Repositories\ProductFlatRepository     $productFlatRepository
     * @param  Webkul\Marketplace\Repositories\OrderRepository       $orderRepository
     * @param  Webkul\Marketplace\Repositories\OrderItemRepository   $orderItemRepository
     * @param  Webkul\Marketplace\Repositories\InvoiceItemRepository $invoiceItemRepository
     * @param  Webkul\Marketplace\Repositories\RefundItemRepository  $refundItemRepository
     * @param  Illuminate\Container\Container                        $app
     * @return void
     */
    public function __construct(
        SellerRepository $sellerRepository,
        ProductFlatRepository $productFlatRepository,
        OrderRepository $orderRepository,
        OrderItemRepository $orderItemRepository,
        InvoiceItemRepository $invoiceItemRepository,
        RefundItemRepository $refundItemRepository,
        App $app
    )
    {
        $this->sellerRepository = $sellerRepository;

        $this->productFlatRepository = $productFlatRepository;

        $this->orderRepository = $orderRepository;

        $this->orderItemRepository = $orderItemRepository;

        $this->invoiceItemRepository = $invoiceItemRepository;

        $this->refundItemRepository = $refundItemRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Marketplace\Contracts\Refund';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {

        $refund = $data['refund'];

        Event::dispatch('marketplace.sales.refund.save.before', $data);

        $sellerRefunds = [];

        foreach ($refund['items'] as $item) {
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
                'order_id' => $refund->order->id,
                'marketplace_seller_id' => $seller->id,
            ]);

            if (! $sellerOrder)
                continue;

            $sellerOrderItem = $this->orderItemRepository->findOneWhere([
                'marketplace_order_id' => $sellerOrder->id,
                'order_item_id' => $item->order_item->id,
            ]);

            if (! $sellerOrderItem)
                continue;

            $sellerRefund = $this->findOneWhere([
                'refund_id' => $refund->id,
                'marketplace_order_id' => $sellerOrder->id,
            ]);

            if (! $sellerRefund) {
                $sellerRefunds[] = $sellerRefund = parent::create([
                    'total_qty' => $item->qty,
                    'state' => 'refunded',
                    'refund_id' => $refund->id,
                    'marketplace_order_id' => $sellerOrder->id,
                    'adjustment_refund' => core()->convertPrice($data['refund']['adjustment_refund'], $sellerOrder->order_currency_code),
                    'base_adjustment_refund' => $data['refund']['adjustment_refund'],
                    'adjustment_fee' => core()->convertPrice($data['refund']['adjustment_fee'], $sellerOrder->order_currency_code),
                    'base_adjustment_fee' => $data['refund']['adjustment_fee'],
                    'shipping_amount' => core()->convertPrice($data['refund']['shipping_amount'], $sellerOrder->order_currency_code),
                    'base_shipping_amount' => $data['refund']['shipping_amount']
                ]);
            } else {
                $sellerRefund->total_qty += $item->qty;

                $sellerRefund->save();
            }

            $sellerRefundItem = $this->refundItemRepository->create([
                    'marketplace_refund_id' => $sellerRefund->id,
                    'refund_item_id' => $item->id,
            ]);

            $this->orderItemRepository->collectTotals($sellerOrderItem);
        }

        foreach ($sellerRefunds as $sellerRefund) {
            $this->collectTotals($sellerRefund);
        }

        foreach ($sellers as $seller) {
            if ($seller) {
                foreach ($this->orderRepository->findWhere(['order_id' => $refund->order->id, 'marketplace_seller_id' => $seller->id]) as $order) {
                    // $order->sellerCount = count($sellers);

                    $this->orderRepository->collectTotals($order);

                    $this->orderRepository->updateOrderStatus($order);

                    $this->orderRepository->update(['seller_payout_status' => 'refunded'], $order->id);
                }
            }
        }

        foreach ($sellerRefunds as $sellerRefund) {
            Event::dispatch('marketplace.sales.refund.save.after', $sellerRefund);
        }
    }

    /**
     * @param mixed $sellerRefund
     * @return mixed
     */
    public function collectTotals($sellerRefund)
    {
        $sellerRefund->sub_total = $sellerRefund->base_sub_total = 0;
        $sellerRefund->tax_amount = $sellerRefund->base_tax_amount = 0;
        $sellerRefund->grand_total = $sellerRefund->base_grand_total = 0;
        $sellerRefund->discount_amount = $sellerRefund->base_discount_amount = 0;

        foreach ($sellerRefund->items as $sellerRefundItem) {
            $sellerRefund->sub_total += $sellerRefundItem->item->total;
            $sellerRefund->base_sub_total += $sellerRefundItem->item->base_total;

            $sellerRefund->tax_amount += $sellerRefundItem->item->tax_amount;
            $sellerRefund->base_tax_amount += $sellerRefundItem->item->base_tax_amount;

            $sellerRefund->discount_amount += $sellerRefundItem->item->discount_amount;
            $sellerRefund->base_discount_amount += $sellerRefundItem->item->base_discount_amount;
        }

        $sellerRefund->grand_total = $sellerRefund->sub_total + $sellerRefund->tax_amount + $sellerRefund->shipping_amount + $sellerRefund->adjustment_refund - $sellerRefund->adjustment_fee - $sellerRefund->discount_amount;

        $sellerRefund->base_grand_total = $sellerRefund->base_sub_total + $sellerRefund->base_tax_amount + $sellerRefund->base_shipping_amount + $sellerRefund->base_adjustment_refund - $sellerRefund->base_adjustment_fee - $sellerRefund->base_discount_amount;

        $sellerRefund->save();

        return $sellerRefund;
    }
}