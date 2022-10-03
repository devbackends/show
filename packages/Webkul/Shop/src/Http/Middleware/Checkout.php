<?php

namespace Webkul\Shop\Http\Middleware;

use Webkul\Core\Repositories\CurrencyRepository;
use Closure;

class Checkout
{
    /**
     * @var CurrencyRepository
     */
    protected $currency;

    /**
     * @param \Webkul\Core\Repositories\CurrencyRepository $locale
     */
    public function __construct(CurrencyRepository $currency)
    {
        $this->currency = $currency;
    }

    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
    public function handle($request, Closure $next)
    {
        $sellerId = (int) $request->route('sellerId');
        $redirect = null;

        $cart = app(\Webkul\Checkout\Cart::class)->getCart($sellerId);
        if(!$cart){
            $redirect = 'customer.session.index';
        }
        if (is_null($sellerId)){
            return redirect()->route('customer.session.index');
        }
      /*  if (! auth()->guard('customer')->check()
            && ! core()->getConfigData('catalog.products.guest-checkout.allow-guest-checkout')) {
            $redirect = 'customer.session.index';
        }


        if (! auth()->guard('customer')->check() && $cart->hasDownloadableItems()) {
            $redirect = 'customer.session.index';
        }

        if (! auth()->guard('customer')->check() && !$cart->hasGuestCheckoutItems()) {
            $redirect = 'customer.session.index';
        }
      */
        if ($redirect) {
            // temporarily save the requested url to access it after login:
            $uri = $request->path();
            session()->put('checkout_after_login', $uri);
            return redirect()->route($redirect);
        }
        return $next($request);
    }
}