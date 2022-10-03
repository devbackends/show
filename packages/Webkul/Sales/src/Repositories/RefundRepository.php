<?php

namespace Webkul\Sales\Repositories;

use Exception;
use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\DB;
use Webkul\Sales\Contracts\Refund;
use Webkul\Core\Eloquent\Repository;
use Webkul\Sales\Services\RefundProcessor;
use Webkul\Sales\Models\Order;

class RefundRepository extends Repository
{
    /**
     * OrderRepository object
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * OrderItemRepository object
     *
     * @var \Webkul\Sales\Repositories\OrderItemRepository
     */
    protected $orderItemRepository;

    /**
     * RefundItemRepository object
     *
     * @var \Webkul\Sales\Repositories\RefundItemRepository
     */
    protected $refundItemRepository;

    /**
     * DownloadableLinkPurchasedRepository object
     *
     * @var \Webkul\Sales\Repositories\DownloadableLinkPurchasedRepository
     */
    protected $downloadableLinkPurchasedRepository;

    /**
     * Create a new repository instance.
     *
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Sales\Repositories\OrderItemRepository  $orderItemRepository
     * @param  \Webkul\Sales\Repositories\RefundItemRepository   $refundItemRepository
     * @param  \Webkul\Sales\Repositories\DownloadableLinkPurchasedRepository  $downloadableLinkPurchasedRepository
     * @param  \Illuminate\Container\Container  $app
     */
    public function __construct(
        OrderRepository $orderRepository,
        OrderItemRepository $orderItemRepository,
        RefundItemRepository $refundItemRepository,
        DownloadableLinkPurchasedRepository $downloadableLinkPurchasedRepository,
        App $app
    )
    {
        $this->orderRepository = $orderRepository;

        $this->orderItemRepository = $orderItemRepository;

        $this->refundItemRepository = $refundItemRepository;

        $this->downloadableLinkPurchasedRepository = $downloadableLinkPurchasedRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Refund::class;
    }

    /**
     * @param  array  $data
     * @return \Webkul\Sales\Contracts\Refund
     */
    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            Event::dispatch('sales.refund.save.before', $data);

            $order = $this->orderRepository->find($data['order_id']);

            $totalQty = array_sum($data['refund']['items']);

            $refund = parent::create([
                'order_id'               => $order->id,
                'total_qty'              => $totalQty,
                'state'                  => 'refunded',
                'base_currency_code'     => $order->base_currency_code,
                'channel_currency_code'  => $order->channel_currency_code,
                'order_currency_code'    => $order->order_currency_code,
                'adjustment_refund'      => isset($data['refund']['adjustment_refund']) ? core()->convertPrice($data['refund']['adjustment_refund'], $order->order_currency_code) : 0,
                'base_adjustment_refund' => isset($data['refund']['adjustment_refund']) ? $data['refund']['adjustment_refund'] : 0,
                'adjustment_fee'         => isset($data['refund']['adjustment_refund']) ? core()->convertPrice($data['refund']['adjustment_fee'], $order->order_currency_code) : 0,
                'base_adjustment_fee'    => isset($data['refund']['adjustment_refund']) ? $data['refund']['adjustment_fee'] : 0,
                'shipping_amount'        => isset($data['refund']['shipping']) ? core()->convertPrice($data['refund']['shipping'], $order->order_currency_code) : 0,
                'base_shipping_amount'   => isset($data['refund']['shipping']) ? $data['refund']['shipping'] : 0,
            ]);

            foreach ($data['refund']['items'] as $itemId => $qty) {
                if (! $qty) {
                    continue;
                }

                $orderItem = $this->orderItemRepository->find($itemId);

                if ($qty > $orderItem->qty_to_refund) {
                    $qty = $orderItem->qty_to_refund;
                }

                $refundItem = $this->refundItemRepository->create([
                    'refund_id'            => $refund->id,
                    'order_item_id'        => $orderItem->id,
                    'name'                 => $orderItem->name,
                    'sku'                  => $orderItem->sku,
                    'qty'                  => $qty,
                    'price'                => $orderItem->price,
                    'base_price'           => $orderItem->base_price,
                    'total'                => $orderItem->price * $qty,
                    'base_total'           => $orderItem->base_price * $qty,
                    'tax_amount'           => ( ($orderItem->tax_amount / $orderItem->qty_ordered) * $qty ),
                    'base_tax_amount'      => ( ($orderItem->base_tax_amount / $orderItem->qty_ordered) * $qty ),
                    'discount_amount'      => ( ($orderItem->discount_amount / $orderItem->qty_ordered) * $qty ),
                    'base_discount_amount' => ( ($orderItem->base_discount_amount / $orderItem->qty_ordered) * $qty ),
                    'product_id'           => $orderItem->product_id,
                    'product_type'         => $orderItem->product_type,
                    'additional'           => $orderItem->additional,
                ]);

                if ($orderItem->getTypeInstance()->isComposite()) {
                    foreach ($orderItem->children as $childOrderItem) {
                        $finalQty = $childOrderItem->qty_ordered
                                    ? ($childOrderItem->qty_ordered / $orderItem->qty_ordered) * $qty
                                    : $orderItem->qty_ordered;

                        $refundItem->child = $this->refundItemRepository->create([
                            'refund_id'            => $refund->id,
                            'order_item_id'        => $childOrderItem->id,
                            'parent_id'            => $refundItem->id,
                            'name'                 => $childOrderItem->name,
                            'sku'                  => $childOrderItem->sku,
                            'qty'                  => $finalQty,
                            'price'                => $childOrderItem->price,
                            'base_price'           => $childOrderItem->base_price,
                            'total'                => $childOrderItem->price * $finalQty,
                            'base_total'           => $childOrderItem->base_price * $finalQty,
                            'tax_amount'           => 0,
                            'base_tax_amount'      => 0,
                            'discount_amount'      => 0,
                            'base_discount_amount' => 0,
                            'product_id'           => $childOrderItem->product_id,
                            'product_type'         => $childOrderItem->product_type,
                            'additional'           => $childOrderItem->additional,
                        ]);

                        if ($childOrderItem->getTypeInstance()->isStockable() || $childOrderItem->getTypeInstance()->showQuantityBox()) {
                            $this->refundItemRepository->returnQtyToProductInventory($childOrderItem, $finalQty);
                        }

                        $this->orderItemRepository->collectTotals($childOrderItem);
                    }

                } else {
                    if ($orderItem->getTypeInstance()->isStockable() || $orderItem->getTypeInstance()->showQuantityBox()) {
                        $this->refundItemRepository->returnQtyToProductInventory($orderItem, $qty);
                    }
                }

                $this->orderItemRepository->collectTotals($orderItem);

                if ($orderItem->qty_ordered == $orderItem->qty_refunded + $orderItem->qty_canceled) {
                    $this->downloadableLinkPurchasedRepository->updateStatus($orderItem, 'expired');
                }

                try {
                  $inventory=app('Webkul\Product\Repositories\ProductRepository')->getProductInventory($orderItem->additional['product_id'] ,$orderItem->additional['seller_info']['seller_id']);
                  $webhook=app('Webkul\Product\Repositories\ProductRepository')->sendWebhook($inventory,$orderItem->additional['seller_info']['seller_id'], $orderItem->additional['product_id']);
                }catch (\Exception $e){
                }
            }

            $this->collectTotals($refund);

            // Refund processing code
            if (isset($data['refund_processor']) && $data['refund_processor'] !== 'manual') {
                $result = $this->executeRefundProcessor($order, (float)$refund->base_grand_total);
                if (!$result) return false;
                    /*throw new Exception()*/;
            }


            $this->orderRepository->collectTotals($order);

            $this->orderRepository->updateOrderStatus($order);

            Event::dispatch('sales.refund.save.after', $refund);
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return $refund;
    }

    /**
     * @param  \Webkul\Sales\Contracts\Refund  $refund
     * @return \Webkul\Sales\Contracts\Refund
     */
    public function collectTotals($refund)
    {
        $refund->sub_total = $refund->base_sub_total = 0;
        $refund->tax_amount = $refund->base_tax_amount = 0;
        $refund->discount_amount = $refund->base_discount_amount = 0;

        foreach ($refund->items as $refundItem) {
            $refund->sub_total += $refundItem->total;
            $refund->base_sub_total += $refundItem->base_total;

            $refund->tax_amount += $refundItem->tax_amount;
            $refund->base_tax_amount += $refundItem->base_tax_amount;

            $refund->discount_amount += $refundItem->discount_amount;
            $refund->base_discount_amount += $refundItem->base_discount_amount;
        }

        $refund->grand_total = $refund->sub_total + $refund->tax_amount + $refund->shipping_amount + $refund->adjustment_refund - $refund->adjustment_fee - $refund->discount_amount;
        $refund->base_grand_total = $refund->base_sub_total + $refund->base_tax_amount + $refund->base_shipping_amount + $refund->base_adjustment_refund - $refund->base_adjustment_fee - $refund->base_discount_amount;

        $refund->save();

        return $refund;
    }

    /**
     * @param  array  $data
     * @param  integer  $orderId
     * @return array
     */
    public function getOrderItemsRefundSummary($data, $orderId)
    {
        $order = $this->orderRepository->find($orderId);

        $summary = [
            'subtotal'    => ['price' => 0],
            'discount'    => ['price' => 0],
            'tax'         => ['price' => 0],
            'shipping'    => ['price' => 0],
            'grand_total' => ['price' => 0],
        ];

        foreach ($data as $orderItemId => $qty) {
            if (! $qty) {
                continue;
            }

            $orderItem = $this->orderItemRepository->find($orderItemId);

            if ($qty > $orderItem->qty_to_refund) {
                return false;
            }

            $summary['subtotal']['price'] += $orderItem->base_price * $qty;

            $summary['discount']['price'] += ($orderItem->base_discount_amount / $orderItem->qty_ordered) * $qty;

            $summary['tax']['price'] += ($orderItem->tax_amount / $orderItem->qty_ordered) * $qty;
        }

        $summary['shipping']['price'] += $order->base_shipping_invoiced - $order->base_shipping_refunded - $order->base_shipping_discount_amount;

        $summary['grand_total']['price'] += $summary['subtotal']['price'] + $summary['tax']['price'] + $summary['shipping']['price'] - $summary['discount']['price'];

        $summary['subtotal']['formated_price'] = core()->formatBasePrice($summary['subtotal']['price']);

        $summary['discount']['formated_price'] = core()->formatBasePrice($summary['discount']['price']);

        $summary['tax']['formated_price'] = core()->formatBasePrice($summary['tax']['price']);

        $summary['shipping']['formated_price'] = core()->formatBasePrice($summary['shipping']['price']);

        $summary['grand_total']['formated_price'] = core()->formatBasePrice($summary['grand_total']['price']);

        return $summary;
    }

    /**
     * @param Order $order
     * @param float $amount
     * @return bool
     */
    protected function executeRefundProcessor(Order $order, float $amount): bool
    {
        if (!$order->payment || empty($order->payment->additional)) {
            return true;
        }

        $orderPaymentAdditional = json_decode($order->payment->additional, 1);
        $processors = array_intersect_key(config('services.refund.processors'), $orderPaymentAdditional);
        if (empty($processors)) return true;
        $processorKey = array_key_first($processors);

        $processor = new $processors[$processorKey]($order);
        if (!$processor instanceof RefundProcessor) return false;
        if (!$processor->isAvailable()) return true;

        return $processor->refund($amount);
    }
}