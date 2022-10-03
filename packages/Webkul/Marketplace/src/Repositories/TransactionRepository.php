<?php

namespace Webkul\Marketplace\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Event;

/**
 * Seller Transaction Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TransactionRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Marketplace\Contracts\Transaction';
    }

    /**
     * Pay seller
     *
     * @param integer $data
     * @return boolean
     */
    public function paySeller($data)
    {
        $orderRepository = app('Webkul\Marketplace\Repositories\OrderRepository');

        $sellerOrder = $orderRepository->findOneWhere([
            'order_id' => $data['order_id'],
            'marketplace_seller_id' => $data['seller_id']
        ]);

        if (! $sellerOrder) {
            session()->flash('error', trans('marketplace::app.admin.orders.order-not-exist'));

            return;
        }

        $totalPaid = $this->scopeQuery(function($query) use($sellerOrder) {
            return $query->where('marketplace_transactions.marketplace_seller_id', $sellerOrder->marketplace_seller_id)
                ->where('marketplace_transactions.marketplace_order_id', $sellerOrder->id);
        })->sum('base_total');

        $amount = $sellerOrder->base_seller_total_invoiced - $totalPaid;

        if (! $amount) {
            session()->flash('error', trans('marketplace::app.admin.orders.no-amount-to-paid'));

            return;
        }

        Event::dispatch('marketplace.sales.transaction.create.before', $data);

        $transaction = $this->create([
                'type' => isset($data['type']) ? $data['type'] : 'manual',
                'method' => isset($data['method']) ? $data['method'] : 'manual',
                'transaction_id' => $data['order_id'] . '-' . str_random(10),
                'comment' => $data['comment'],
                'base_total' => $amount,
                'marketplace_order_id' => $sellerOrder->id,
                'marketplace_seller_id' => $sellerOrder->marketplace_seller_id,
            ]);

        if (($amount + $totalPaid) == $sellerOrder->base_seller_total) {
            $orderRepository->update(['seller_payout_status' => 'paid'], $sellerOrder->id);
        }

        Event::dispatch('marketplace.sales.transaction.create.after', $transaction);

        return $transaction;
    }
}