<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Account\Sales;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Webkul\CartRule\Repositories\CartRuleRepository;
use Webkul\CartRule\Repositories\CartRuleCouponRepository;
use Webkul\Marketplace\Http\Controllers\Shop\Controller;
use Webkul\Marketplace\Service\SellerType;
use Webkul\Marketplace\Repositories\SellerRepository;

/**
 * Coupon controller

 */
class CartRuleController extends Controller
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
     * To hold Cart repository instance
     *
     * @var \Webkul\CartRule\Repositories\CartRuleRepository
     */
    protected $cartRuleRepository;

    /**
     * To hold CartRuleCouponRepository repository instance
     *
     * @var \Webkul\CartRule\Repositories\CartRuleCouponRepository
     */
    protected $cartRuleCouponRepository;
    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Marketplace\Repositories\SellerRepository $sellerRepository
     * @param  \Webkul\CartRule\Repositories\CartRuleRepository $cartRuleRepository
     * @param  \Webkul\CartRule\Repositories\CartRuleCouponRepository  $cartRuleCouponRepository
     * @return void
     */
    public function __construct(SellerRepository $sellerRepository, CartRuleRepository $cartRuleRepository, CartRuleCouponRepository $cartRuleCouponRepository)
    {
        $this->sellerRepository = $sellerRepository;
        $this->cartRuleRepository = $cartRuleRepository;
        $this->cartRuleCouponRepository = $cartRuleCouponRepository;

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
        $isSeller = $this->sellerRepository->isSeller(auth()->guard('customer')->user()->id);

        if (! $isSeller) {
            return redirect()->route('marketplace.account.seller.create');
        }

        return view($this->_config['view']);
    }

    public function create(){
        return view($this->_config['view']);
    }
    public function store(){
        $this->validate(request(), [
            'name'                => 'required',
            'channels'            => 'required|array|min:1',
            'customer_groups'     => 'required|array|min:1',
            'coupon_type'         => 'required',
            'use_auto_generation' => 'required_if:coupon_type,==,1',
            'coupon_code'         => 'required_if:use_auto_generation,==,0',
            'starts_from'         => 'nullable|date',
            'ends_till'           => 'nullable|date|after_or_equal:starts_from',
            'action_type'         => 'required',
            'discount_amount'     => 'required|numeric',

        ]);

        $data = request()->all();
        $data['seller_id']=app('Webkul\Marketplace\Repositories\SellerRepository')->getVendorBySellerId(auth()->guard('customer')->user()->id);

        Event::dispatch('promotions.cart_rule.create.before');

        $cartRule = $this->cartRuleRepository->create($data);

        Event::dispatch('promotions.cart_rule.create.after', $cartRule);

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Cart Rule']));

        return redirect()->route($this->_config['redirect']);
    }

    public function edit($id)
    {
        $cartRule = $this->cartRuleRepository->findOrFail($id);

        return view($this->_config['view'], compact('cartRule'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(request(), [
            'name'                => 'required',
            'channels'            => 'required|array|min:1',
            'customer_groups'     => 'required|array|min:1',
            'coupon_type'         => 'required',
            'use_auto_generation' => 'required_if:coupon_type,==,1',
            'coupon_code'         => 'required_if:use_auto_generation,==,0',
            'starts_from'         => 'nullable|date',
            'ends_till'           => 'nullable|date|after_or_equal:starts_from',
            'action_type'         => 'required',
            'discount_amount'     => 'required|numeric',
        ]);

        $cartRule = $this->cartRuleRepository->findOrFail($id);

        Event::dispatch('promotions.cart_rule.update.before', $cartRule);

        $cartRule = $this->cartRuleRepository->update(request()->all(), $id);

        Event::dispatch('promotions.cart_rule.update.after', $cartRule);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Cart Rule']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cartRule = $this->cartRuleRepository->findOrFail($id);

        try {
            Event::dispatch('promotions.cart_rule.delete.before', $id);

            $this->cartRuleRepository->delete($id);

            Event::dispatch('promotions.cart_rule.delete.after', $id);

            session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Cart Rule']));

            return response()->json(['message' => true], 200);
        } catch (\Exception $e) {
            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Cart Rule']));
        }

        return response()->json(['message' => false], 400);
    }

}