<?php

namespace Webkul\MarketplaceUpsShipping\Http\Controllers\Shop\Account;

use Webkul\MarketplaceUpsShipping\Http\Controllers\Shop\Controller;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\MarketplaceUpsShipping\Repositories\UpsRepository as SellerCredentials;

/**
 * MarketplaceUps controller
 *
 * @author    Naresh Verma <naresh.verma327@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class UpsController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * sellerRepository object
     *
     * @var mixed
     */
    protected $sellerRepository;

    /**
     * sellerCredentials object
     *
     * @var mixed
     */
    protected $sellerCredentials;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Marketplace\Repositories\SellerRepository $sellerRepository
     * @param  Webkul\MarketplaceUpsShipping\Repositories\SellerCredentialsRepository $sellerCredentials
     * @return void
     */
    public function __construct(
        SellerRepository $sellerRepository,
        SellerCredentials $sellerCredentials
    )
    {
        $this->sellerRepository = $sellerRepository;

        $this->sellerCredentials = $sellerCredentials;

        $this->_config = request('_config');
    }

    /**
     * Method to populate the seller fedexshipping page.
     *
     * @return Mixed
     */
    public function index()
    {
        $isSeller = $this->sellerRepository->isSeller(auth()->guard('customer')->user()->id);

        if (! $isSeller) {
            return redirect()->route('marketplace.account.seller.create');
        } else {

            $customerId = auth()->guard('customer')->user()->id;

            $sellerId = $this->sellerRepository->findOneWhere(['customer_id'=> $customerId]);

            $sellerId = $sellerId->id;
        }

        $credentials = $this->sellerCredentials->findOneWhere(['ups_seller_id' => $sellerId]);

        if (core()->getConfigData('sales.carriers.mpups.active') && core()->getConfigData('sales.carriers.mpups.allow_seller')) {

            return view($this->_config['view'])->with('credentials', $credentials);
        } else {

            return redirect()->route('marketplace.account.seller.create');
        }
    }

    /**
     * Method to store the seller fedex configuration.
     *
     * @return Mixed
     */
    public function store($sellerId)
    {
        $this->validate(request(), [
            'account_id' => 'required',
            'password' => 'required',
        ]);

        $data = request()->all();

        $data['ups_seller_id'] = $sellerId;

        $ifCredentialsExist = $this->sellerCredentials->findOneWhere(['ups_seller_id' => $sellerId]);

        if (isset ($ifCredentialsExist)) {
            $d = $this->sellerCredentials->update($data, $ifCredentialsExist->id);

            session()->flash('success', trans('marketplace_ups::app.shop.sellers.ups.update-success'));
        } else {
            $this->sellerCredentials->create($data);

            session()->flash('success', trans('marketplace_ups::app.shop.sellers.ups.create-success'));
        }

        return redirect()->back();
    }


}