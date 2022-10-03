<?php


namespace Mega\Phonelogin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{

    public function boot(){
        Event::listen('bagisto.shop.customers.signup_form_controls.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('megaPhoneLogin::shop.customer.signup.phone');
        });
        Event::listen('customer.registration.before',
            'Mega\Phonelogin\Listeners\Customers@beforeRegistration');
        Event::listen('customer.registration.after',
            'Mega\Phonelogin\Listeners\Customers@afterRegistration');
        Event::listen('bagisto.shop.customers.login.after',function ($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('megaPhoneLogin::shop.customer.login.index');
        });
        Event::listen('bagisto.shop.customers.forget_password.after',function ($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('megaPhoneLogin::shop.customer.forgetpassword.index');
        });
        Event::listen('bagisto.shop.customers.account.profile.view.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('megaPhoneLogin::shop.customer.account.profile.edit.phone');
        });
        Event::listen('bagisto.shop.customers.account.profile.edit_form_controls.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('megaPhoneLogin::shop.customer.account.profile.edit.phone');
        });

    }
}