<?php

namespace Webkul\Admin\Http\Controllers\Sales;

use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Sales\Repositories\OrderRepository;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    /**
     * OrderRepository object
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     * @return void
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->middleware('admin');

        $this->_config = request('_config');

        $this->orderRepository = $orderRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the view for the specified resource.
     *
     * @param $id
     * @return View
     */
    public function view($id)
    {
        $order = $this->orderRepository->findOrFail($id);

        $cart = app(CartRepository::class)->find($order->cart_id);
        $ffl = ($cart->ffl_shipping_method) ? $cart->selected_ffl_shipping_rate : [];

        return view($this->_config['view'], compact('order', 'ffl'));
    }

    /**
     * Cancel action for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        $result = $this->orderRepository->cancel($id);

        if ($result) {
            session()->flash('success', trans('admin::app.response.cancel-success', ['name' => 'Order']));
        } else {
            session()->flash('error', trans('admin::app.response.cancel-error', ['name' => 'Order']));
        }

        return redirect()->back();
    }

    public function saveOrderNotes($id){
        $data=request()->all();
        $result = $this->orderRepository->saveNotes($id,$data['order_notes']);
        if ($result) {
          return json_encode(array('status'=>'success'));
        } else {
          return json_encode(array('status'=>'fail'));
        }
    }
}