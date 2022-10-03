<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Marketplace\Repositories\OrderRepository;
use Webkul\Marketplace\Repositories\TransactionRepository;

/**
 * Marketplace seller order controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrderController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * OrderRepository object
     *
     * @var object
    */
    protected $orderRepository;

    /**
     * TransactionRepository object
     *
     * @var object
    */
    protected $transactionRepository;
    
    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Marketplace\Repositories\OrderRepository       $orderRepository
     * @param  Webkul\Marketplace\Repositories\TransactionRepository $transactionRepository
     * @return void
     */
    public function __construct(
        OrderRepository $orderRepository,
        TransactionRepository $transactionRepository
    )
    {
        $this->orderRepository = $orderRepository;

        $this->transactionRepository = $transactionRepository;

        $this->_config = request('_config');
    }

    /**
     * Method to populate the seller order page which will be populated.
     *
     * @return Mixed
     */
    public function index($url)
    {   
        return view($this->_config['view']);
    }

    /**
     * Pay seller
     *
     * @return Mixed
     */
    public function pay()
    {
        if ($this->transactionRepository->paySeller(request()->all())) {
            session()->flash('success', trans('marketplace::app.admin.orders.payment-success-msg'));
        }

        return redirect()->back();
    }
}