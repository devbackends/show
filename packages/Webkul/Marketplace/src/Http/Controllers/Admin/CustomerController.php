<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Marketplace\Repositories\SellerRepository;

/**
 * Customer controlller
 *
 * @author    Rahul Shukla <rahulshukla.symfony517@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CustomerController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * CustomerRepository object
     *
     * @var array
     */
    protected $customerRepository;

     /**
     * SellerRepository object
     *
     * @var array
     */
    protected $sellerRepository;

    /**
     * Create a new controller instance.
     *
     * @param \Webkul\Customer\Repositories\CustomerRepository  $customerRepository
     * @param \Webkul\Marketplace\Repositories\SellerRepository $sellerRepository
     */
    public function __construct(
        CustomerRepository $customerRepository,
        SellerRepository $sellerRepository
    )
    {
        $this->_config = request('_config');

        $this->middleware('admin');

        $this->customerRepository = $customerRepository;

        $this->sellerRepository = $sellerRepository;
    }


     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = request()->all();

        $this->validate(request(), [
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'gender' => 'required',
            'email' => 'required|unique:customers,email,'. $id,
            'date_of_birth' => 'date|before:today'
        ]);

        $this->customerRepository->update(request()->all(), $id);

        $seller = app('Webkul\Marketplace\Repositories\SellerRepository')->findOneWhere([
            'customer_id' => $id
        ]);

        if ($seller) {
            if (isset($data['commission_enable'])) {
                $sellerData['commission_enable']     = $data['commission_enable'];
                $sellerData['commission_percentage'] = $data['commission_percentage'];
            } else {
                $sellerData['commission_enable']     = 0;
                $sellerData['commission_percentage'] = 0;
            }

            $this->sellerRepository->update($sellerData, $seller->id);
        }

        Event::dispatch('admin.marketplace.customer.update.after', ['seller' => $seller]);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Customer']));

        return redirect()->route($this->_config['redirect']);
    }
}