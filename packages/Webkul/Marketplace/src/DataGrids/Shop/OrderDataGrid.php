<?php

namespace Webkul\Marketplace\DataGrids\Shop;

use DB;
use Webkul\Ui\DataGrid\DataGrid;
use Webkul\Marketplace\Repositories\SellerRepository;

/**
 * Order Data Grid class
 *
 * @author Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrderDataGrid extends DataGrid
{
    /**
     * @var integer
     */
    protected $index = 'order_id';

    protected $sortOrder = 'desc'; //asc or desc

    /**
     * SellerRepository object
     *
     * @var Object
     */
    protected $sellerRepository;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\Marketplace\Repositories\SellerRepository $sellerRepository
     * @return void
     */
    public function __construct(SellerRepository $sellerRepository)
    {
        parent::__construct();

        $this->sellerRepository = $sellerRepository;
    }

    public function prepareQueryBuilder()
    {
        $seller = $this->sellerRepository->findOneByField('customer_id', auth()->guard('customer')->user()->id);

        $queryBuilder = DB::table('marketplace_orders')
                ->leftJoin('orders', 'marketplace_orders.order_id', '=', 'orders.id')
                ->select('orders.id', 'marketplace_orders.order_id', 'marketplace_orders.base_grand_total', 'marketplace_orders.grand_total', 'marketplace_orders.created_at', 'channel_name', 'marketplace_orders.status', 'orders.order_currency_code', 'orders.customer_id','marketplace_orders.seller_payout_status')
                ->addSelect(DB::raw('CONCAT(orders.customer_first_name, " ", orders.customer_last_name) as customer_name'), 'orders.increment_id')
                ->where('marketplace_orders.marketplace_seller_id', $seller->id);

        $this->addFilter('customer_name', DB::raw('CONCAT(orders.customer_first_name, " ", orders.customer_last_name)'));
        $this->addFilter('id', 'orders.id');
        $this->addFilter('base_grand_total', 'marketplace_orders.base_grand_total');
        $this->addFilter('grand_total', 'marketplace_orders.grand_total');
        $this->addFilter('created_at', 'marketplace_orders.created_at');
        $this->addFilter('status', 'marketplace_orders.status');
        $this->addFilter('seller_payout_status', 'marketplace_orders.seller_payout_status');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'increment_id',
            'label' => trans('marketplace::app.shop.sellers.account.sales.orders.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'base_grand_total',
            'label' => trans('marketplace::app.shop.sellers.account.sales.orders.base-total'),
            'type' => 'price',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'grand_total',
            'label' => trans('marketplace::app.shop.sellers.account.sales.orders.grand-total'),
            'type' => 'price',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'wrapper' => function($row) {
                if (! is_null($row->grand_total))
                    return core()->formatPrice($row->grand_total, $row->order_currency_code);
            }
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => trans('marketplace::app.shop.sellers.account.sales.orders.order-date'),
            'type' => 'datetime',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('marketplace::app.shop.sellers.account.sales.orders.status'),
            'type' => 'string',
            'sortable' => false,
            'searchable' => false,
            'closure' => true,
            'filterable' => true,
            'wrapper' => function ($row) {
                if ($row->status == 'processing')
                    return '<span class="badge badge-md badge-primary">' . trans("marketplace::app.shop.sellers.account.sales.orders.processing") . '</span>';
                else if ($row->status == 'completed')
                    return '<span class="badge badge-md badge-success">' . trans("marketplace::app.shop.sellers.account.sales.orders.completed") . '</span>';
                else if ($row->status == "canceled")
                    return '<span class="badge badge-md badge-danger">' . trans("marketplace::app.shop.sellers.account.sales.orders.canceled") . '</span>';
                else if ($row->status == "closed")
                    return '<span class="badge badge-md badge-info">' . trans("marketplace::app.shop.sellers.account.sales.orders.closed") . '</span>';
                else if ($row->status == "pending")
                    return '<span class="badge badge-md badge-warning">' . trans("marketplace::app.shop.sellers.account.sales.orders.pending") . '</span>';
                else if ($row->status == "pending_payment")
                    return '<span class="badge badge-md badge-warning">' . trans("marketplace::app.shop.sellers.account.sales.orders.pending-payment") . '</span>';
                else if ($row->status == "fraud")
                    return '<span class="badge badge-md badge-danger">' . trans("marketplace::app.shop.sellers.account.sales.orders.fraud") . '</span>';
            }
        ]);

        $this->addColumn([
            'index' => 'seller_payout_status',
            'label' => 'Payout Status',
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'closure' => true,
            'filterable' => true,
            'wrapper' => function ($row) {
                if ($row->seller_payout_status == 'refunded')
                    return '<span class="">Refunded</span>';
            }
        ]);

        $this->addColumn([
            'index' => 'customer_name',
            'label' => trans("marketplace::app.shop.sellers.account.sales.orders.billed-to"),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.view'),
            'method' => 'GET',
            'route'  => 'marketplace.account.refunds.view',
            'icon'   => 'icon icon-coin',
        ]);

        $this->addAction([
            'type' => 'View',
            'route' => 'marketplace.account.orders.view',
            'icon' => 'icon eye-icon',
            'method' => 'GET'
        ]);
        $this->addAction([
            'type' => 'Print',
            'custom' => true,
            'custom_type' => 'print-order',
            'icon' => 'fa fa-print',
            'method' => 'CUSTOM'
        ]);
        $this->addAction([
            'type' => 'Contact',
            'custom' => true,
            'custom_type' => 'contact-seller',
            'icon' => 'far fa-comments-alt',
            'method' => 'MESSAGE'
        ]);
    }
}