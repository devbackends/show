<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Account;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Marketplace\Repositories\MpProductRepository;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Marketplace\Repositories\OrderRepository;
use Webkul\Marketplace\Repositories\OrderItemRepository;
use Webkul\Product\Repositories\ProductRepository;

class DashboardController extends SellerAccountBaseController
{

    /**
     * SellerRepository object
     *
     * @var array
     */
    protected $sellerRepository;

    /**
     * OrderRepository object
     *
     * @var array
     */
    protected $orderRepository;

    /**
     * OrderItemRepository object
     *
     * @var array
     */
    protected $orderItemRepository;


    /**
     * string object
     *
     * @var array
     */
    protected $startDate;

    /**
     * string object
     *
     * @var array
     */
    protected $lastStartDate;

    /**
     * string object
     *
     * @var array
     */
    protected $endDate;

    /**
     * string object
     *
     * @var array
     */
    protected $lastEndDate;

    /**
     * DashboardController constructor.
     * @param MpProductRepository $mpProductRepository
     * @param CategoryRepository $categoryRepository
     * @param ProductRepository $productRepository
     * @param SellerRepository $sellerRepository
     * @param OrderRepository $orderRepository
     * @param OrderItemRepository $orderItemRepository
     */
    public function __construct(
        MpProductRepository $mpProductRepository,
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository,
        SellerRepository $sellerRepository,
        OrderRepository $orderRepository,
        OrderItemRepository $orderItemRepository
    )
    {
        parent::__construct($mpProductRepository, $categoryRepository, $productRepository);

        $this->sellerRepository = $sellerRepository;

        $this->orderRepository = $orderRepository;

        $this->orderItemRepository = $orderItemRepository;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->setStartEndDate();

        $statistics = [
            'total_orders' =>  [
                'previous' => $previous = $this->orderRepository->scopeQuery(function($query) {
                        return $query->where('marketplace_orders.marketplace_seller_id', $this->seller->id)
                            ->where('marketplace_orders.created_at', '>=', $this->lastStartDate)
                            ->where('marketplace_orders.created_at', '<=', $this->lastEndDate);
                    })->count(),
                'current' => $current = $this->orderRepository->scopeQuery(function($query) {
                        return $query->where('marketplace_orders.marketplace_seller_id', $this->seller->id)
                            ->where('marketplace_orders.created_at', '>=', $this->startDate)
                            ->where('marketplace_orders.created_at', '<=', $this->endDate);
                    })->count(),
                'progress' => $this->getPercentageChange($previous, $current)
            ],
            'total_sales' =>  [
                'previous' => $previous = $this->orderRepository->scopeQuery(function($query) {
                        return $query->where('marketplace_orders.marketplace_seller_id', $this->seller->id)
                            ->where('marketplace_orders.created_at', '>=', $this->lastStartDate)
                            ->where('marketplace_orders.created_at', '<=', $this->lastEndDate);
                    })->sum('base_seller_total'),
                'current' => $current = $this->orderRepository->scopeQuery(function($query) {
                        return $query->where('marketplace_orders.marketplace_seller_id', $this->seller->id)
                            ->where('marketplace_orders.created_at', '>=', $this->startDate)
                            ->where('marketplace_orders.created_at', '<=', $this->endDate);
                    })->sum('base_seller_total') - $this->orderRepository->scopeQuery(function($query) {
                        return $query->where('marketplace_orders.marketplace_seller_id', $this->seller->id)
                            ->where('marketplace_orders.created_at', '>=', $this->startDate)
                            ->where('marketplace_orders.created_at', '<=', $this->endDate);
                    })->sum('base_grand_total_refunded') + $this->orderRepository->scopeQuery(function($query) {
                        return $query->where('marketplace_orders.marketplace_seller_id', $this->seller->id)
                            ->where('marketplace_orders.created_at', '>=', $this->startDate)
                            ->where('marketplace_orders.created_at', '<=', $this->endDate);
                    })->sum('base_commission_invoiced'),
                'progress' => $this->getPercentageChange($previous, $current)
            ],
            'avg_sales' =>  [
                'previous' => $previous = $this->orderRepository->scopeQuery(function($query) {
                        return $query->where('marketplace_orders.marketplace_seller_id', $this->seller->id)
                            ->where('marketplace_orders.created_at', '>=', $this->lastStartDate)
                            ->where('marketplace_orders.created_at', '<=', $this->lastEndDate);
                    })->avg('base_seller_total'),
                'current' => $current = $this->orderRepository->scopeQuery(function($query) {
                        return $query->where('marketplace_orders.marketplace_seller_id', $this->seller->id)
                            ->where('marketplace_orders.created_at', '>=', $this->startDate)
                            ->where('marketplace_orders.created_at', '<=', $this->endDate);
                    })->avg('base_seller_total') - $this->orderRepository->scopeQuery(function($query) {
                        return $query->where('marketplace_orders.marketplace_seller_id', $this->seller->id)
                            ->where('marketplace_orders.created_at', '>=', $this->startDate)
                            ->where('marketplace_orders.created_at', '<=', $this->endDate);
                    })->avg('base_grand_total_refunded') + $this->orderRepository->scopeQuery(function($query) {
                        return $query->where('marketplace_orders.marketplace_seller_id', $this->seller->id)
                            ->where('marketplace_orders.created_at', '>=', $this->startDate)
                            ->where('marketplace_orders.created_at', '<=', $this->endDate);
                    })->avg('base_commission_invoiced'),
                'progress' => $this->getPercentageChange($previous, $current)
            ],
            'top_selling_products' => $this->getTopSellingProducts(),
            'customer_with_most_sales' => $this->getCustomerWithMostSales(),
            'stock_threshold' => $this->getStockThreshold(),
        ];

        foreach (core()->getTimeInterval($this->startDate, $this->endDate) as $interval) {
            $statistics['sale_graph']['label'][] = $interval['start']->format('d M');

            $total = $this->orderRepository->scopeQuery(function($query) use($interval) {
                        return $query->where('marketplace_orders.marketplace_seller_id', $this->seller->id)
                            ->where('marketplace_orders.created_at', '>=', $interval['start'])
                            ->where('marketplace_orders.created_at', '<=', $interval['end']);
                    })->sum('base_seller_total');

            $statistics['sale_graph']['total'][] = $total;
            $statistics['sale_graph']['formated_total'][] = core()->formatBasePrice($total);
        }
        // function to check profile
        $is_seller_profile_complete=true;
        $check_shipping_methods_is_configured=true;

        //check if seller has products
        $seller_products_count= app('Webkul\Product\Repositories\ProductFlatRepository')->getSellerProductsCount($this->seller);

        return view($this->_config['view'], compact('statistics'))->with([
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'is_seller_profile_complete'=>$is_seller_profile_complete,
            'check_shipping_methods_is_configured'=>$check_shipping_methods_is_configured,
            'seller_products_count'=>$seller_products_count,
            'seller'=>$this->seller
        ]);
    }

    /**
     * Return stock threshold.
     *
     * @return mixed
     */
    public function getStockThreshold()
    {
        return app('Webkul\Product\Repositories\ProductFlatRepository')->getModel()
            ->join('products', 'product_flat.product_id', 'products.id')
            ->select(DB::raw('SUM(product_flat.quantity) as total_qty'))
            ->addSelect('product_flat.product_id')
            ->where('products.type', '!=', 'configurable')
            ->where('product_flat.marketplace_seller_id', $this->seller->id)
            ->groupBy('product_flat.product_id')
            ->orderBy('total_qty', 'ASC')
            ->limit(5)
            ->get();
        return '';
    }

    /**
     * Returns top selling products
     * @return mixed
     */
    public function getTopSellingProducts()
    {
        return $this->orderItemRepository->getModel()
            ->leftJoin('order_items', 'marketplace_order_items.order_item_id', 'order_items.id')
            ->leftJoin('marketplace_orders', 'marketplace_order_items.marketplace_order_id', 'marketplace_orders.id')
            ->select(DB::raw('SUM(qty_ordered) as total_qty_ordered'))
            ->addSelect('order_items.id', 'order_items.product_id', 'product_type', 'name')
            ->where('order_items.created_at', '>=', $this->startDate)
            ->where('order_items.created_at', '<=', $this->endDate)
            ->where('marketplace_orders.marketplace_seller_id', $this->seller->id)
            ->whereNull('order_items.parent_id')
            ->groupBy('order_items.product_id')
            ->orderBy('total_qty_ordered', 'DESC')
            ->limit(5)
            ->get();

    }

    /**
     * Returns top selling products
     *
     * @return mixed
     */
    public function getCustomerWithMostSales()
    {
        return $this->orderRepository->getModel()
            ->leftJoin('orders', 'marketplace_orders.order_id', 'orders.id')
            ->select(DB::raw('SUM(marketplace_orders.base_grand_total) as total_base_grand_total'))
            ->addSelect(DB::raw('COUNT(marketplace_orders.id) as total_orders'))
            ->addSelect('orders.id', 'orders.customer_id', 'orders.customer_email', DB::raw('CONCAT(orders.customer_first_name, " ", orders.customer_last_name) as customer_full_name'))
            ->where('marketplace_orders.created_at', '>=', $this->startDate)
            ->where('marketplace_orders.created_at', '<=', $this->endDate)
            ->where('marketplace_orders.marketplace_seller_id', $this->seller->id)
            ->groupBy('orders.customer_email')
            ->orderBy('total_base_grand_total', 'DESC')
            ->limit(5)
            ->get();
    }

    public function getPercentageChange($previous, $current)
    {
        if (! $previous)
            return $current ? 100 : 0;

        return ($current - $previous) / $previous * 100;
    }

    /**
     * Sets start and end date
     *
     * @return void
     */
    public function setStartEndDate()
    {
        $this->startDate = request()->get('start')
            ? Carbon::createFromTimeString(request()->get('start') . " 00:00:01")
            : Carbon::createFromTimeString(Carbon::now()->subDays(30)->format('Y-m-d') . " 00:00:01");

        $this->endDate = request()->get('end')
            ? Carbon::createFromTimeString(request()->get('end') . " 23:59:59")
            : Carbon::now();

        if ($this->endDate > Carbon::now())
            $this->endDate = Carbon::now();

        $this->lastStartDate = clone $this->startDate;
        $this->lastEndDate = clone $this->startDate;

        $this->lastStartDate->subDays($this->startDate->diffInDays($this->endDate));
    }
}