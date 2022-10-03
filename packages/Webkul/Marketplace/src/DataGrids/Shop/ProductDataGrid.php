<?php

namespace Webkul\Marketplace\DataGrids\Shop;

use DB;
use Webkul\Ui\DataGrid\DataGrid;
use Webkul\Marketplace\Repositories\SellerRepository;

/**
 * Product Data Grid class
 *
 * @author Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductDataGrid extends DataGrid
{
    /**
     * @var integer
     */
    protected $index = 'product_id';

    /**
     * @var string
     */
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
        DB::enableQueryLog(); // Enable query log
        $queryBuilder =  DB::table('product_flat')
        ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
        ->leftJoin('product_images', 'product_images.product_id', '=', 'products.id')
        ->leftJoin('marketplace_sellers', 'product_flat.marketplace_seller_id', '=', 'marketplace_sellers.id')
        ->leftJoin('customers', 'marketplace_sellers.customer_id', '=', 'customers.id')
        ->leftJoin('attribute_families', 'attribute_families.id', '=', 'products.attribute_family_id')
        ->select( 'product_flat.url_key','products.type')
            ->selectRaw("
                product_flat.product_id as product_id,
                products.sku as product_sku,
                product_flat.name as product_name,

                product_flat.status,
                product_flat.price")
            ->SelectRaw(' IF(product_flat.quantity - product_flat.ordered_quantity >= 0, product_flat.quantity - product_flat.ordered_quantity ,0) as quantity')
        ->addSelect('products.type as product_type','products.parent_id as parent_id')
        ->addSelect('product_flat.marketplace_seller_id',  'product_flat.name', 'product_flat.price as product_flat_price', 'product_flat.is_seller_approved',  DB::raw('CONCAT(customers.first_name, " ", customers.last_name) as seller_name'))

        ->addSelect('product_images.path as product_image')
        ->addSelect('attribute_families.name as attribute_family')
        ->where('product_flat.marketplace_seller_id', $seller->id)
        ->where('channel', core()->getCurrentChannelCode())
        ->where('locale', app()->getLocale())
        ->distinct();

        $queryBuilder->groupBy('product_flat.product_id');



        $this->addFilter('product_id', 'product_flat.product_id');
        $this->addFilter('product_name', 'product_flat.name');
        $this->addFilter('product_sku', 'products.sku');
        $this->addFilter('price', 'product_flat.price');
        $this->setQueryBuilder($queryBuilder);
        // Show results of log
    }

    public function addColumns()
    {

         $this->addColumn([
            'index' => 'product_image',
            'label' => '',
            'type' => 'image',
            'searchable' => false,
            'sortable' => false,
            'filterable' => false
        ]);


        $this->addColumn([
            'index' => 'product_id',
            'label' => trans('marketplace::app.shop.sellers.account.catalog.products.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'product_sku',
            'label' => trans('marketplace::app.shop.sellers.account.catalog.products.sku'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'product_name',
            'label' => trans('marketplace::app.shop.sellers.account.catalog.products.name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'type',
            'label' => 'Type',
            'type' => 'family',
            'searchable' => false,
            'sortable' => false,
            'filterable' => false
        ]);

        $this->addColumn([
            'index' => 'price',
            'label' => trans('marketplace::app.shop.sellers.account.catalog.products.price'),
            'type' => 'price',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true,
            'wrapper' => function($row) {
                    return number_format($row->product_flat_price, 2);
            }
        ]);

        $this->addColumn([
            'index' => 'quantity',
            'label' => trans('marketplace::app.shop.sellers.account.catalog.products.qty'),
            'type' => 'number',
            'sortable' => true,
            'searchable' => false,
            'filterable' => false
        ]);

/*        $this->addColumn([
            'index' => 'is_approved',
            'label' => trans('marketplace::app.shop.sellers.account.catalog.products.is-approved'),
            'type' => 'boolean',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true,
            'wrapper' => function($row) {
                if ($row->is_approved == 1)
                    return trans('marketplace::app.shop.sellers.account.catalog.products.yes');
                else
                    return trans('marketplace::app.shop.sellers.account.catalog.products.no');
            }
        ]);*/
    }

    public function prepareActions() {
        $this->addAction([
            'custom' => true,
            'custom_type' => 'seller-product',
            'type' => 'View',
            'method' => 'GET',
            'icon' => 'far fa-eye'
        ]);
        $this->addAction([
            'type' => 'Edit',
            'method' => 'GET',
            'route' => 'marketplace.account.products.edit',
            'icon' => 'far fa-pencil'
        ]);

        $this->addAction([
            'type' => 'Delete',
            'method' => 'GET',
            'route' => 'marketplace.account.products.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'product']),
            'icon' => 'far fa-trash-alt'
        ]);
    }

    public function prepareMassActions() {
        $this->addMassAction([
            'type' => 'delete',
            'label' => trans('marketplace::app.shop.sellers.account.catalog.products.delete'),
            'action' => route('marketplace.account.products.massdelete'),
            'method' => 'DELETE',
            'onSubmit' => 'onMassDeleteFormSubmit(); return false;',
        ]);
    }
}