<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Account\Sales;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Marketplace\Http\Controllers\Shop\Controller;
use Webkul\Marketplace\Repositories\OrderRepository;
use Webkul\Marketplace\Repositories\TransactionRepository;
use Webkul\Marketplace\Repositories\SellerRepository;
use function foo\func;

/**
 * Transaction controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TransactionController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * SellerRepository object
     *
     * @var mixed
     */
    protected $sellerRepository;

    /**
     * OrderRepository object
     *
     * @var mixed
     */
    protected $orderRepository;

    /**
     * TransactionRepository object
     *
     * @var mixed
     */
    protected $transactionRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Marketplace\Repositories\SellerRepository      $sellerRepository
     * @param  Webkul\Marketplace\Repositories\OrderRepository       $orderRepository
     * @param  Webkul\Marketplace\Repositories\TransactionRepository $transactionRepository
     * @return void
     */
    public function __construct(
        SellerRepository $sellerRepository,
        OrderRepository $orderRepository,
        TransactionRepository $transactionRepository
    )
    {
        $this->sellerRepository = $sellerRepository;

        $this->orderRepository = $orderRepository;

        $this->transactionRepository = $transactionRepository;

        $this->_config = request('_config');

        $this->middleware('marketplace-seller');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect()->route('shop.home.index');

        $isSeller = $this->sellerRepository->isSeller(auth()->guard('customer')->user()->id);

        if (! $isSeller) {
            return redirect()->route('marketplace.account.seller.create');
        }

        $seller = $this->sellerRepository->findOneByField('customer_id', auth()->guard('customer')->user()->id);

        $statistics = [
            'total_sale' =>
                $totalSale = $this->orderRepository->scopeQuery(function($query) use($seller) {
                        return $query->where('marketplace_orders.marketplace_seller_id', $seller->id);
                })->sum('base_grand_total') - $this->orderRepository->scopeQuery(function($query) use($seller) {
                        return $query->where('marketplace_orders.marketplace_seller_id', $seller->id);
                })->sum('base_grand_total_refunded'),


            'total_payout' => $totalPaid = $this->transactionRepository->scopeQuery(function($query) use ($seller) {
                        return $query->where('marketplace_transactions.marketplace_seller_id', $seller->id);
                    })->sum('base_total'),

            'remaining_payout' => $totalSale - $totalPaid - $this->orderRepository->scopeQuery(function($query) use($seller) {
                    return $query->where('marketplace_orders.marketplace_seller_id', $seller->id);
                })->sum('base_commission') - $this->orderRepository->scopeQuery(function($query) use($seller) {
                    return $query->where('marketplace_orders.marketplace_seller_id', $seller->id);
                })->sum('base_shipment_processing'),
        ];

        $statistics = array_map(function ($a) {
            if (strpos($a, '-') !== false) {
                $a = 0.0;
            }
            return round($a, 2);
        }, $statistics);

        return view($this->_config['view'], compact('statistics'));
    }

    /**
     * Show the view for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $transaction = $this->transactionRepository->findOrFail($id);

        return view($this->_config['view'], compact('transaction'));
    }
}