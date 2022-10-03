<?php

namespace Webkul\Marketplace\DataGrids\Shop;

use DB;
use Webkul\Ui\DataGrid\DataGrid;
use Webkul\Marketplace\Repositories\SellerRepository;

/**
 * Transaction Data Grid class
 *
 * @author Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TransactionDataGrid extends DataGrid
{
    /**
     * @var integer
     */
    protected $index = 'id';

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

        $queryBuilder = DB::table('marketplace_transactions')
                ->select('id', 'transaction_id', 'comment', 'base_total')
                ->where('marketplace_transactions.marketplace_seller_id', $seller->id);

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('marketplace::app.shop.sellers.account.sales.transactions.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'transaction_id',
            'label' => trans('marketplace::app.shop.sellers.account.sales.transactions.transaction-id'),
            'type' => 'string',
            'sortable' => false,
            'searchable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'comment',
            'label' => trans('marketplace::app.shop.sellers.account.sales.transactions.comment'),
            'type' => 'string',
            'sortable' => false,
            'searchable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'base_total',
            'label' => trans('marketplace::app.shop.sellers.account.sales.transactions.total'),
            'type' => 'price',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'type' => 'View',
            'route' => 'marketplace.account.transactions.view',
            'icon' => 'icon eye-icon',
            'method' => 'GET'
        ]);
    }
}