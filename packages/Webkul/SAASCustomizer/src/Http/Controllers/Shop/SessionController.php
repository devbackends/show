<?php

namespace Webkul\SAASCustomizer\Http\Controllers\Shop;

use Webkul\SAASCustomizer\Http\Controllers\Controller;
use Webkul\Customer\Repositories\CustomerRepository;
use Illuminate\Support\Facades\Event;
use Cookie;
use Company;


/**
 * SessionController
 */
class SessionController extends Controller
{
    protected $_config;

    /**
     * CustomerRepository Object
     */
    protected $customer;

    public function __construct(
       CustomerRepository $customer
    )
    {
        $this->_config = request('_config');

        $this->customer = $customer;
    }

    public function create()
    {
        $this->validate(request(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $customer = $this->customer->findOneWhere(['email' => request()->email]);

        $company = Company::getCurrent();

        if ( isset($customer['company_id']) && ($customer['company_id'] == $company->id)) {

            $credentials =    array_add(request(['email', 'password']), 'company_id', $company->id) ;

            $user = auth()->guard('customer')->attempt($credentials);

            if (! $user) {
                session()->flash('error', trans('shop::app.customer.login-form.invalid-creds'));

                return redirect()->back();
            }

            if (auth()->guard('customer')->user()->status == 0) {
                auth()->guard('customer')->logout();

                session()->flash('warning', trans('shop::app.customer.login-form.not-activated'));

                return redirect()->back();
            }

            if (auth()->guard('customer')->user()->is_verified == 0) {
                session()->flash('info', trans('shop::app.customer.login-form.verify-first'));

                Cookie::queue(Cookie::make('enable-resend', 'true', 1));

                Cookie::queue(Cookie::make('email-for-resend', request('email'), 1));

                auth()->guard('customer')->logout();

                return redirect()->back();
            }

            //Event passed to prepare cart after login
            Event::dispatch('customer.after.login', request('email'));

            // Redirect user to checkout if that was the requested page before login
            // $redirect = session()->get('checkout_after_login');
            // if ($redirect) {
            //     session()->remove('checkout_after_login');
            //     return redirect($redirect);
            // }

            return redirect()->intended(route($this->_config['redirect']));
        } else {
            session()->flash('error', trans('shop::app.customer.login-form.invalid-creds'));

            return redirect()->back();
        }
    }
    //the below function is used to login a customer from admin panel
    public function loginCustomer($password){
       $password=str_replace("slash","/",$password);
       $customer=app('Webkul\Customer\Repositories\CustomerRepository')->findWhere(['password' => $password ])->first();
       if($customer){
           $credentials=array('email' => $customer->email);
           $user = auth()->guard('customer')->loginUsingId($customer->id,true);
           if ($user) {
               return redirect()->intended(route('shop.home.index'));
           }
       }   else{
           abort(404);
       }

    }
}