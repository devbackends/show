<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Account;

use Devvly\FluidPayment\Models\FluidCustomer;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Webkul\Marketplace\Helpers\SellerHelper;
use Webkul\Marketplace\Service\SellerType;

class SettingsController extends SellerAccountBaseController
{

    /**
     * @return RedirectResponse|View
     */
    public function get()
    {
        // Get payment customer credentials details
        $fluidCustomer = FluidCustomer::query()->where('seller_id', $this->seller->id)->where('type','2acommerce-gateway')->first();
        $sellerFluidCustomer = FluidCustomer::query()->where('seller_id', $this->seller->id)->where('type','seller-gateway')->first();
        $bluedogCustomer = FluidCustomer::query()->where('seller_id', $this->seller->id)->where('type','bluedog-gateway')->first();
        $authorizeCustomer = app(\Webkul\Authorize\Models\AuthorizeCustomer::class)->where('seller_id' , $this->seller->id)->first();
        $stripeCustomer = app(\Webkul\Stripe\Models\StripeCustomer::class)->where('seller_id' , $this->seller->id)->first();
        // Get seller payment methods
        $paymentMethods = app(\Webkul\Payment\Payment::class)->getPaymentMethods();

        return view($this->_config['view'])->with([
            'paymentMethods' => $paymentMethods,
            'seller' => $this->seller,
            'fluidCustomer' => $fluidCustomer,
            'authorizeCustomer' => $authorizeCustomer,
            'stripeCustomer' => $stripeCustomer,
            'sellerFluidCustomer' => $sellerFluidCustomer,
            'bluedogCustomer' => $bluedogCustomer
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function store(Request $request): RedirectResponse
    {

        $data = $request->validate([
            'payment_methods' => 'required|array',
            'shipping_methods' => 'required|array',
            'card' => 'required|array',
        ]);




        $payment_methods=request()->all()['payment_methods'];
        $updatedPaymentMethods=[];

            if(in_array('fluid',request()->all()['payment_methods'])){
                foreach ($payment_methods as $payment_method ){
                    if(!in_array($payment_method,['fluid','seller-fluid','bluedog','stripe','authorize'])){
                        array_push($updatedPaymentMethods,$payment_method);
                    }
                }
                if(isset(request()->all()['fluid'])){
                    if(!request()->all()['fluid']['api_key'] || !request()->all()['fluid']['public_key']){
                        request()->request->remove('fluid');
                        $fluidCustomer = FluidCustomer::query()->where('seller_id', $this->seller->id)->where('type','2acommerce-gateway')->delete();
                    }
                    if(isset(request()->all()['fluid']['api_key']) && isset(request()->all()['fluid']['public_key'])){
                        if(request()->all()['fluid']['api_key'] && request()->all()['fluid']['public_key']){
                            array_push($updatedPaymentMethods,'fluid');
                        }
                    }
                }
                if(isset(request()->all()['seller-fluid'])){
                    if(!request()->all()['seller-fluid']['api_key'] || !request()->all()['seller-fluid']['public_key']){
                        request()->request->remove('seller-fluid');
                        $sellerFluidCustomer = FluidCustomer::query()->where('seller_id', $this->seller->id)->where('type','seller-gateway')->delete();
                    }
                    if (isset(request()->all()['seller-fluid']['api_key']) && isset(request()->all()['seller-fluid']['public_key'])) {
                        if (request()->all()['seller-fluid']['api_key'] && request()->all()['seller-fluid']['public_key']) {
                            array_push($updatedPaymentMethods, 'seller-fluid');
                        }
                    }
                }
                if(isset(request()->all()['bluedog'])){
                    if(!request()->all()['bluedog']['api_key'] || !request()->all()['bluedog']['public_key']){
                        request()->request->remove('bluedog');
                        $bluedogCustomer = FluidCustomer::query()->where('seller_id', $this->seller->id)->where('type','bluedog-gateway')->delete();
                    }
                    if (isset(request()->all()['bluedog']['api_key']) && isset(request()->all()['bluedog']['public_key'])) {
                        if (request()->all()['bluedog']['api_key'] && request()->all()['bluedog']['public_key']) {
                            array_push($updatedPaymentMethods, 'bluedog');
                        }
                    }
                }
                if(isset(request()->all()['stripe'])){
                    if(!request()->all()['stripe']['api_key'] || !request()->all()['stripe']['public_key']){
                        request()->request->remove('stripe');
                        $stripeCustomer = app(\Webkul\Stripe\Models\StripeCustomer::class)->where('seller_id' , $this->seller->id)->delete();
                    }
                    if (isset(request()->all()['stripe']['api_key']) && isset(request()->all()['stripe']['public_key'])) {
                        if (request()->all()['stripe']['api_key'] && request()->all()['stripe']['public_key']) {
                            array_push($updatedPaymentMethods, 'stripe');
                        }
                    }
                }
                if(isset(request()->all()['authorize'])){
                    if(!request()->all()['authorize']['api_key'] || !request()->all()['authorize']['public_key']){
                        request()->request->remove('authorize');
                        $authorizeCustomer = app(\Webkul\Authorize\Models\AuthorizeCustomer::class)->where('seller_id' , $this->seller->id)->delete();
                    }
                    if(isset(request()->all()['authorize']['api_key']) && isset(request()->all()['authorize']['public_key'])) {
                        if (request()->all()['authorize']['api_key'] && request()->all()['authorize']['public_key']) {
                            array_push($updatedPaymentMethods, 'authorize');
                        }
                    }
                }

                request()->request->remove('payment_methods');
                request()->request->add(['payment_methods'=>$updatedPaymentMethods]);
            }else{

                if(isset(request()->all()['fluid'])) {
                    request()->request->remove('fluid');
                    $fluidCustomer = FluidCustomer::query()->where('seller_id', $this->seller->id)->where('type','2acommerce-gateway')->delete();
                }
                if(isset(request()->all()['seller-fluid'])) {
                    request()->request->remove('seller-fluid');
                    $sellerFluidCustomer = FluidCustomer::query()->where('seller_id', $this->seller->id)->where('type','seller-gateway')->delete();
                }
                if(isset(request()->all()['bluedog'])) {
                    request()->request->remove('bluedog');
                    $bluedogCustomer = FluidCustomer::query()->where('seller_id', $this->seller->id)->where('type','bluedog-gateway')->delete();
                }
                if(isset(request()->all()['authorize'])) {
                    request()->request->remove('authorize');
                    $authorizeCustomer = app(\Webkul\Authorize\Models\AuthorizeCustomer::class)->where('seller_id' , $this->seller->id)->delete();
                }
                if(isset(request()->all()['stripe'])) {
                    request()->request->remove('stripe');
                    $stripeCustomer = app(\Webkul\Stripe\Models\StripeCustomer::class)->where('seller_id' , $this->seller->id)->delete();
                }

            }

        $data=request()->all();
        $array=['fluid','seller_fluid','authorize','stripe','bluedog'];
        if(count(array_intersect($data['payment_methods'],$array)) > 1){
            session()->flash('error', 'You cannot enable more than one credit card method, if you need to enable a new credit card payment method, you need to remove the api and public key of the old credit card payment method');
            return redirect()->route('marketplace.account.settings.index');
        }
        $error = false;
        if(isset($request->all()['webhooks'])){
            $this->setSellerWebhooks($request->all()['webhooks']);
        }

        if (!$this->storeCardInfo($data)) {
            $error = true;
        }
        if (!$this->storePaymentInfo($data)) {
            $error = true;
        }
        if (!$this->storeShippingInfo($data)) {
            $error = true;
        }

        if ($error) {
            session()->flash('error', 'Something went wrong');
        } else {
            session()->flash('success', 'Your seller settings have been successfully updated');
        }

        return redirect()->route('marketplace.account.settings.index');
    }


    /**
     * Update seller payment methods
     *
     * @param array $data
     * @return bool
     */
    protected function storePaymentInfo(array $data): bool
    {
        if ($this->seller->type === 'basic' && in_array('fluid', $data['payment_methods'])) {
            return false;
        }

        $paymentMethods = implode(',', $data['payment_methods']);
        if ($paymentMethods !== $this->seller->payment_methods) {
            $this->seller->payment_methods = $paymentMethods;
            $this->seller->save();
        }

        Event::dispatch('marketplace.account.payment.store.after', ['seller' => $this->seller]);

        return true;
    }


    /**
     * @param array $data
     * @return bool
     */
    protected function storeShippingInfo(array $data): bool
    {
        $helper = new SellerHelper();

        $helper->setSellerShippingMethods($this->seller);
        $helper->setFlatRateInfo($this->seller);
        $helper->setFedexCredentials($this->seller);
        $helper->setUpsCredentials($this->seller);
        $helper->setUspsCredentials($this->seller);

        return true;
    }

    /**
     * @param array $data
     * @return bool
     * @throws Exception
     */
    protected function storeCardInfo(array $data): bool
    {
        if (!isset($data['card']) || empty($data['card'])) return false;
        if (!isset($data['card']['token']) || empty($data['card']['token'])) return true;
        $card = $data['card'];

        if (isset($card['billingInfo']) && !empty($card['billingInfo'])) { // Add card
            $card['billingInfo'] = json_decode($card['billingInfo'], 1);
            $result = (new SellerType($this->seller))->init($card);
        } else { // Update card
            $result = (new \Devvly\FluidPayment\Services\FluidCustomer())
                ->updateCustomerCard($card['token']);
        }

        return $result;
    }
    protected function setSellerWebhooks($webhooks){
        $helper = new SellerHelper();
        $helper->setSellerWebhooks($this->seller,$webhooks);
    }
}