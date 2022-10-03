<?php

namespace Webkul\Customer\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Cookie;
use Cart;
use Webkul\API\Http\Resources\Checkout\Cart as CartResource;

class SessionController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('customer')->except(['show', 'create']);

        $this->_config = request('_config');
    }

    /**
     * Display the resource.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        if (auth()->guard('customer')->check()) {
            return redirect()->route('customer.profile.index');
        } else {
            return view($this->_config['view']);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->validate(request(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);


        if (!auth()->guard('customer')->attempt(request(['email', 'password']))) {


            if(isset(request()->all()['async'])){
               return response()->json([
                       'code' => 500,
                       'message' =>trans('shop::app.customer.login-form.invalid-creds')
               ],200);
            }

            session()->flash('error', trans('shop::app.customer.login-form.invalid-creds'));
            return redirect()->back();
        }

        if (auth()->guard('customer')->user()->status == 0) {
            auth()->guard('customer')->logout();
            if(isset(request()->all()['async'])){
                return response()->json([
                        'code' => 500,
                        'message' =>trans('shop::app.customer.login-form.not-activated')
                ],200);
            }
            session()->flash('warning', trans('shop::app.customer.login-form.not-activated'));
            return redirect()->back();
        }

        if (auth()->guard('customer')->user()->is_verified == 0) {


            Cookie::queue(Cookie::make('enable-resend', 'true', 1));

            Cookie::queue(Cookie::make('email-for-resend', request('email'), 1));

            auth()->guard('customer')->logout();
            if(isset(request()->all()['async'])){
                return response()->json([
                        'code' => 500,
                        'message' =>'user is not verified'
                ],200);
            }
            session()->flash('info', trans('shop::app.customer.login-form.verify-first'));
            return redirect()->back();
        }

        //Event passed to prepare cart after login
        Event::dispatch('customer.after.login', request('email'));

        // Redirect user to page after login if that was the requested page before login
         $redirect = session()->get('page_after_login');
         if ($redirect) {
             session()->remove('page_after_login');
             if(isset(request()->all()['async'])){
                 return response()->json([
                         'code' => 200,
                         'message' =>'Logged In Successfully',
                         'customer' => auth()->guard('customer')->user()
                 ],200);
             }
             return redirect($redirect);
         }

        if(isset(request()->all()['async'])){
            return response()->json([
                    'code' => 200,
                   'message' =>'Logged In Successfully',
                   'customer' => auth()->guard('customer')->user()
            ],200);
        }

        return redirect()->intended(route($this->_config['redirect']));/*route($this->_config['redirect'])*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        auth()->guard('customer')->logout();
        Session()->flush();
        Event::dispatch('customer.after.logout', $id);

        return redirect()->route($this->_config['redirect']);
    }
}