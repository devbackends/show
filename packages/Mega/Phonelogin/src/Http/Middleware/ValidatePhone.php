<?php

namespace Mega\Phonelogin\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ValidatePhone
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'customer')
    {

        $helper = app('Mega\Phonelogin\Helper\PhoneloginHelper');
        if(!$helper->isEnabled())
            return $next($request);
        if (! Auth::guard($guard)->check()) {
            return $next($request);
        } else {
            $routeName = $request->route()->getName();
            if(!core()->getConfigData('megaPhoneLogin.general.general.verification-required')){
                return $next($request);
            }
            if (Auth::guard($guard)->user()->mega_phone_verified == 0 && $routeName != 'mega.phonelogin.verifyphone') {
                session()->flash('warning', trans('megaPhoneLogin::app.customer.verify-phone-message'));
                return redirect()->route('mega.phonelogin.verifyphone');
            }
        }

        return $next($request);
    }

}