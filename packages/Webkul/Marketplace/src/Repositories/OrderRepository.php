<?php

namespace Webkul\Marketplace\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Event;
use Webkul\Marketplace\Service\SellerType;
use Webkul\Sales\Repositories\OrderItemRepository as OrderItem;
use Webkul\Sales\Repositories\OrderRepository as Order;
use Webkul\Product\Repositories\ProductFlatRepository;
/**
 * Seller Order Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrderRepository extends Repository
{

    const COMMISION_30_CENTS = false;

    /**
     * SellerRepository object
     *
     * @var Object
     */
    protected $sellerRepository;

    /**
     * OrderItemRepository object
     *
     * @var Object
     */
    protected $orderItemRepository;

    /**
     * TransactionRepository object
     *
     * @var Object
     */
    protected $transactionRepository;

    /**
     * ProductRepository object
     *
     * @var Object
     */
    protected $productRepository;

    /**
     * Order object
     *
     * @var Object
     */
    protected $order;

    /**
     * OrderItem object
     *
     * @var Object
     */
    protected $orderItem;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\Product\Repositories\SellerRepository      $sellerRepository
     * @param  Webkul\Product\Repositories\OrderItemRepository   $orderItemRepository
     * @param  Webkul\Product\Repositories\TransactionRepository $transactionRepository
     * @param  Webkul\Product\Repositories\ProductFlatRepository     $productFlatRepository
     * @param  Webkul\Sales\Repositories\OrderItemRepository     $orderItem
     * @param  Webkul\Sales\Repositories\OrderRepository         $Order;
     * @param  Illuminate\Container\Container                    $app
     * @return void
     */
    public function __construct(
        SellerRepository $sellerRepository,
        OrderItemRepository $orderItemRepository,
        TransactionRepository $transactionRepository,
        ProductFlatRepository $productFlatRepository,
        OrderItem $orderItem,
        Order $order,
        App $app
    )
    {
        $this->sellerRepository = $sellerRepository;

        $this->orderItemRepository = $orderItemRepository;

        $this->transactionRepository = $transactionRepository;

        $this->productFlatRepository = $productFlatRepository;

        $this->orderItem = $orderItem;

        $this->order = $order;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Marketplace\Contracts\Order';
    }

    /**
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function create(array $data)
    {
        $order = $data['order'];

        Event::dispatch('marketplace.sales.order.save.before', $data);

        $commissionPercentage = 0; //core()->getConfigData('marketplace.settings.general.commission_per_unit'); We don't need this logic for now
        $shipmentAdjustmentPercentage = core()->getConfigData('marketplace.settings.general.shipment_processing');

        $seller = $this->sellerRepository->find($order->cart->seller_id);

        try {
            //Automated Messaging For Cash Payments between buyer and seller
            $cart_payyment=\Webkul\Checkout\Models\CartPayment::Where('cart_id', $order->cart->id)->get()->first();
            $subject = "New Order #" . $order->id;
            $query = "Congrats! ". $order->cart->customer_first_name.' '.$order->cart->customer_last_name ." has submitted a new order (#" . $order->id . ")";
            if (isset($cart_payyment->method) && $cart_payyment->method == 'cashsale' ) {
                $query .= "with cash based payment method. Please coordinate payment with buyer.";
                $subject = "New Cash Payment Order #" . $order->id;
            }
            $message = [
                "name" => $order->cart->customer_first_name.' '.$order->cart->customer_last_name,
                "email" => $order->cart->customer_email,
                "subject" => $subject,
                "query" =>  $query,
                "to" =>   $seller->customer_id,
                "from" => $order->cart->customer_id
            ];
            $sent_message = app('Webkul\Marketplace\Repositories\MessageRepository')->createNewMessage($message);
        }
        catch (\Exception $e) {
            $stop = null;
        }
        if (!$seller || !$seller->is_approved) return false;

        $sellerOrder = parent::create([
            'status' => 'pending',
            'seller_payout_status' => 'pending',
            'order_id' => $order->id,
            'marketplace_seller_id' => $seller->id,
            'commission_percentage' => $commissionPercentage,
            'shipment_processing_percentage' => (float)$shipmentAdjustmentPercentage,
            'is_withdrawal_requested' => 0,
            'shipping_amount' => $order->shipping_amount,
            'base_shipping_amount' => $order->base_shipping_amount,
        ]);

        foreach ($order->items()->get() as $item) {
            $sellerProduct = $this->productFlatRepository->findOneWhere([
                'product_id' => $item->product->id,
                'marketplace_seller_id' => $seller->id,
            ]);

            if (! $sellerProduct->is_seller_approved)
                continue;

            // We don't this logic for now
            /*if ($seller->commission_enable) {
                $commissionPercentage = $seller->commission_percentage;
            }*/

            $commission = $baseCommission = 0;
            $sellerTotal = $baseSellerTotal = 0;

            if (isset($commissionPercentage)) {
                $commission = ($item->total * $commissionPercentage) / 100;
                $baseCommission = ($item->base_total * $commissionPercentage) / 100;

                $sellerTotal = $item->total - $commission;
                $baseSellerTotal = $item->base_total - $baseCommission;
            }

            $sellerOrderItem = $this->orderItemRepository->create([
                'product_id' => $sellerProduct->product_id,
                'marketplace_order_id' => $sellerOrder->id,
                'order_item_id' => $item->id,
                'commission' => $commission,
                'base_commission' => $baseCommission,
                'seller_total' => $sellerTotal + $item->tax_amount - $item->discount_amount,
                'base_seller_total' => $baseSellerTotal + $item->base_tax_amount - $item->base_discount_amount
            ]);

            if ($childItem = $item->child) {
                $childProduct = $this->productFlatRepository->findOneWhere([
                    'product_id' => $childItem->product->id,
                    'marketplace_seller_id' => $seller->id,
                ]);

                $childOrderItem = $this->orderItemRepository->create([
                    'product_id' => $childProduct->product_id,
                    'marketplace_order_id' => $sellerOrder->id,
                    'order_item_id' => $childItem->id,
                    'parent_id' => $sellerOrderItem->id
                ]);
            }
        }

        $this->collectTotals($sellerOrder);

        if (!in_array($order->payment->method, ['cashsale', 'check', 'banktransfer'])) {
            // Charge order commission if needed
            (new SellerType($seller))->orderCommission($sellerOrder,$order->payment->method);
        }

        Event::dispatch('marketplace.sales.order.save.after', $sellerOrder);

        session()->forget('table_rate_shipping_rates');
        session()->forget('flat_rate_shipping_rates');
        session()->forget('onlyWithoutShipping');
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function cancel(array $data)
    {
        $order = $data['order'];

        $sellerOrders = $this->findWhere(['order_id' => $order->id]);

        foreach ($sellerOrders as $sellerOrder) {
            Event::dispatch('marketplace.sales.order.cancel.before', $sellerOrder);

            $this->updateOrderStatus($sellerOrder);

            Event::dispatch('marketplace.sales.order.cancel.after', $sellerOrder);
        }
    }

    /**
     * @param int $orderId
     * @return mixed
     */
    public function sellerCancelOrder($orderId)
    {
        $seller = $this->sellerRepository->findOneWhere([
            'customer_id' => auth()->guard('customer')->user()->id
        ]);

        $sellerOrders = $this->findWhere([
            'order_id' => $orderId,
            'marketplace_seller_id' => $seller->id
        ]);

        foreach ($sellerOrders as $sellerOrder) {
            if (! $sellerOrder->canCancel())
                return false;

            Event::dispatch('marketplace.sales.order.cancel.before', $sellerOrder);

            foreach ($sellerOrder->items as $item) {
                if ($item->item->qty_to_cancel) {
                    if($item->item->qty_refunded ==0){
                        $this->orderItem->returnQtyToProductInventory($item->item);
                    }
                    $item->item->qty_canceled += $item->item->qty_to_cancel;

                    $item->item->save();
                }
            }

            $this->updateOrderStatus($sellerOrder);

            $result = $this->order->isInCanceledState($sellerOrder->order);

            if ($result)
                $sellerOrder->order->update(["status" => "canceled"]);

            Event::dispatch('marketplace.sales.order.cancel.after', $sellerOrder);

            return true;
        }
    }

    /**
     * @param mixed $order
     * @return void
     */
    public function isInCompletedState($order)
    {
        $totalQtyOrdered = 0;
        $totalQtyInvoiced = 0;
        $totalQtyShipped = 0;
        $totalQtyRefunded = 0;
        $totalQtyCanceled = 0;

        foreach ($order->items  as $sellerOrderItem) {
            $totalQtyOrdered += $sellerOrderItem->item->qty_ordered;
            $totalQtyInvoiced += $sellerOrderItem->item->qty_invoiced;
            $totalQtyShipped += $sellerOrderItem->item->qty_shipped;
            $totalQtyRefunded += $sellerOrderItem->item->qty_refunded;
            $totalQtyCanceled += $sellerOrderItem->item->qty_canceled;
        }

        if ($totalQtyOrdered != ($totalQtyRefunded + $totalQtyCanceled) &&
            $totalQtyOrdered == $totalQtyInvoiced + $totalQtyRefunded + $totalQtyCanceled &&
            $totalQtyOrdered == $totalQtyShipped + $totalQtyRefunded + $totalQtyCanceled)
            return true;

        return false;
    }

    /**
     * @param mixed $order
     * @return void
     */
    public function isInCanceledState($order)
    {
        $totalQtyOrdered = 0;
        $totalQtyCanceled = 0;

        foreach ($order->items as $sellerOrderItem) {
            $totalQtyOrdered += $sellerOrderItem->item->qty_ordered;
            $totalQtyCanceled += $sellerOrderItem->item->qty_canceled;
        }

        if ($totalQtyOrdered == $totalQtyCanceled)
            return true;

        return false;
    }

    /**
     * @param mixed $order
     * @return void
     */
    public function isInClosedState($order)
    {
        $totalQtyOrdered = 0;
        $totalQtyRefunded = 0;
        $totalQtyCanceled = 0;

        foreach ($order->items  as $sellerOrderItem) {
            $totalQtyOrdered += $sellerOrderItem->item->qty_ordered;
            $totalQtyRefunded += $sellerOrderItem->item->qty_refunded;
            $totalQtyCanceled += $sellerOrderItem->item->qty_canceled;
        }

        if ($totalQtyOrdered == $totalQtyRefunded + $totalQtyCanceled)
            return true;

        return false;
    }

    /**
     * @param mixed $order
     * @return void
     */
    public function updateOrderStatus($order)
    {
        $status = 'processing';

        if ($this->isInCompletedState($order))
            $status = 'completed';

        if ($this->isInCanceledState($order))
            $status = 'canceled';
        elseif ($this->isInClosedState($order))
            $status = 'closed';

        $order->status = $status;
        $order->save();
    }

    /**
     * Updates marketplace order totals
     *
     * @param $order
     * @return Order
     * @throws \Exception
     */
    public function collectTotals($order)
    {
        $order->grand_total = $order->base_grand_total = 0;
        $order->sub_total = $order->base_sub_total = 0;
        $order->tax_amount = $order->base_tax_amount = 0;
        $order->discount_amount_invoiced = $order->base_discount_amount_invoiced = 0;
        $order->commission = $order->base_commission = 0;
        $order->seller_total = $order->base_seller_total = 0;
        $order->total_item_count = $order->total_qty_ordered = 0;
        $order->discount_amount = $order->base_discount_amount = 0;

        $shippingCodes = explode('_', $order->order->shipping_method);
        $carrier = current($shippingCodes);
        $shippingMethod = end($shippingCodes);

        if ($carrier === 'flatrate') {
            $rates = session()->has('flat_rate_shipping_rates') ? session()->get('flat_rate_shipping_rates') : [];
            if (isset($rates[$order->marketplace_seller_id])) {
                $order->shipping_amount = $rates[$order->marketplace_seller_id]['amount'];
                $order->base_shipping_amount = $rates[$order->marketplace_seller_id]['base_amount'];
            }
        } else {
            $marketplaceShippingRates = session()->get('marketplace_shipping_rates');
            if (isset($marketplaceShippingRates[$carrier])
                && isset($marketplaceShippingRates[$carrier][$shippingMethod])
                && isset($marketplaceShippingRates[$carrier][$shippingMethod][$order->marketplace_seller_id])) {
                $sellerShippingRate = $marketplaceShippingRates[$carrier][$shippingMethod][$order->marketplace_seller_id];

                $order->shipping_amount = $sellerShippingRate['amount'];
                $order->base_shipping_amount = $sellerShippingRate['base_amount'];
            }
        }

        foreach ($order->items()->get() as $sellerOrderItem) {
            $item = $sellerOrderItem->item;
            $order->discount_amount += $item->discount_amount;
            $order->base_discount_amount += $item->base_discount_amount;
            $order->grand_total += $item->total + $item->tax_amount - $item->discount_amount;

            $order->base_grand_total += $item->base_total + $item->base_tax_amount - $item->base_discount_amount;

            $order->sub_total += $item->total;
            $order->base_sub_total += $item->base_total;

            $order->tax_amount += $item->tax_amount;
            $order->base_tax_amount += $item->base_tax_amount;

            $order->commission += $sellerOrderItem->commission;
            $order->base_commission += $sellerOrderItem->base_commission;

            $order->seller_total += $sellerOrderItem->seller_total;
            $order->base_seller_total += $sellerOrderItem->base_seller_total;

            $order->total_qty_ordered += $item->qty_ordered;

            $order->total_item_count += 1;
        }

        // We need to add 30 cents fixed commision per order
/*        if (self::COMMISION_30_CENTS) {
            $order->seller_total -= 0.3;
            $order->base_seller_total -= 0.3;

            $order->commission += 0.3;
            $order->base_commission += 0.3;
        }*/

        if ($order->shipping_amount > 0) {
            $order->grand_total += $order->shipping_amount;
            $order->base_grand_total += $order->base_shipping_amount;

            $order->seller_total += $order->shipping_amount;
            $order->base_seller_total += $order->base_shipping_amount;

/*            if ($order->shipment_processing_percentage > 0) {
                $order->shipment_processing = $order->shipping_amount / (100 + $order->shipment_processing_percentage) * $order->shipment_processing_percentage;
                $order->base_shipment_processing = $order->base_shipping_amount / (100 + $order->shipment_processing_percentage) * $order->shipment_processing_percentage;

                $order->seller_total -= $order->shipment_processing;
                $order->base_seller_total -= $order->base_shipment_processing;
            }*/
        }

        $order->sub_total_invoiced = $order->base_sub_total_invoiced = 0;
        $order->shipping_invoiced = $order->base_shipping_invoiced = 0;
        $order->commission_invoiced = $order->base_commission_invoiced = 0;
        $order->shipment_processing_invoiced = $order->base_shipment_processing_invoiced = 0;
        $order->seller_total_invoiced = $order->base_seller_total_invoiced = 0;
        $order->base_grand_total_invoiced = $order->grand_total_invoiced = 0;
        $order->base_tax_amount_invoiced = $order->tax_amount_invoiced = 0;

        foreach ($order->invoices as $invoice) {
            $order->sub_total_invoiced += $invoice->sub_total;
            $order->base_sub_total_invoiced += $invoice->base_sub_total;

            $order->shipping_invoiced += $invoice->shipping_amount;
            $order->base_shipping_invoiced += $invoice->base_shipping_amount;

            $order->tax_amount_invoiced += $invoice->tax_amount;
            $order->base_tax_amount_invoiced += $invoice->base_tax_amount;

            $order->discount_amount_invoiced += $invoice->discount_amount;
            $order->base_discount_amount_invoiced += $invoice->base_discount_amount;

            $order->commission_invoiced += $commissionInvoiced = ($invoice->sub_total * $order->commission_percentage) / 100;
            $order->base_commission_invoiced += $baseCommissionInvoiced = ($invoice->base_sub_total * $order->commission_percentage) / 100;

            $order->shipment_processing_invoiced += $processingInvoiced = $invoice->shipping_amount ;  /*$invoice->shipping_amount / (100 + $order->shipment_processing_percentage) * $order->shipment_processing_percentage*/
            $order->base_shipment_processing_invoiced += $baseProcessingInvoiced = $invoice->base_shipping_amount ; /* $invoice->base_shipping_amount / (100 + $order->shipment_processing_percentage) * $order->shipment_processing_percentage*/

            $order->seller_total_invoiced += $invoice->sub_total - $commissionInvoiced - $processingInvoiced - $invoice->discount_amount + $invoice->shipping_amount + $invoice->tax_amount;
            $order->base_seller_total_invoiced += $invoice->base_sub_total - $baseCommissionInvoiced - $baseProcessingInvoiced - $invoice->base_discount_amount + $invoice->base_shipping_amount + $invoice->base_tax_amount;
        }

        // We need to add 30 cents fixed commision per order
        if (self::COMMISION_30_CENTS) {
            $order->seller_total_invoiced -= 0.3;
            $order->base_seller_total_invoiced -= 0.3;

            $order->commission_invoiced += 0.3;
            $order->base_commission_invoiced += 0.3;
        }

        $order->grand_total_invoiced = $order->sub_total_invoiced + $order->shipping_invoiced + $order->tax_amount_invoiced - $order->discount_amount_invoiced;
        $order->base_grand_total_invoiced = $order->base_sub_total_invoiced + $order->base_shipping_invoiced + $order->base_tax_amount_invoiced - $order->base_discount_amount_invoiced;

        foreach ($order->refunds as $refund) {
            $order->sub_total_refunded += $refund->sub_total;
            $order->base_sub_total_refunded += $refund->base_sub_total;

            $order->shipping_refunded += $refund->shipping_amount;
            $order->base_shipping_refunded += $refund->base_shipping_amount;

            $order->tax_amount_refunded += $refund->tax_amount;
            $order->base_tax_amount_refunded += $refund->base_tax_amount;

            $order->discount_refunded += $refund->discount_amount;
            $order->base_discount_refunded += $refund->base_discount_amount;
        }

        $order->grand_total_refunded = $order->sub_total_refunded + $order->shipping_refunded + $order->tax_amount_refunded - $order->discount_refunded;

        $order->base_grand_total_refunded = $order->base_sub_total_refunded + $order->base_shipping_refunded + $order->base_tax_amount_refunded - $order->base_discount_refunded;

        $order->save();

        return $order;
    }
    public function getOrderByOrderItem($marketplace_order_id){
        return $this->find($marketplace_order_id);
    }
}