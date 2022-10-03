<?php

namespace Mega\SmsNotifications\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\PasswordReset;

class EventServiceProvider extends ServiceProvider
{
    public function boot(){
       /* Event::listen('bagisto.shop.customers.signup_form_controls.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('megaSmsNotifications::shop.customer.signup.phone');
        });
        Event::listen('bagisto.shop.customers.account.profile.view.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('megaSmsNotifications::shop.customer.account.profile.view.phone');
        });
        Event::listen('bagisto.shop.customers.account.profile.edit_form_controls.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('megaSmsNotifications::shop.customer.signup.phone');
        });
*/
        Event::listen('customer.after.login', 'Mega\SmsNotifications\Listeners\Frontend\Customers@AfterLogin');
        Event::listen('customer.registration.after', 'Mega\SmsNotifications\Listeners\Frontend\Customers@AfterRegistration');
        Event::listen(PasswordReset::class,'Mega\SmsNotifications\Listeners\Frontend\Customers@AfterPasswordReset');

        Event::listen('checkout.order.save.after','Mega\SmsNotifications\Listeners\Frontend\Customers@AfterPlaceOrder');
        Event::listen('sales.invoice.save.after','Mega\SmsNotifications\Listeners\Backend\Orders@AfterInvoice');

        Event::listen('sales.order.cancel.after','Mega\SmsNotifications\Listeners\Backend\Orders@AfterCancel');
        Event::listen('sales.shipment.save.after','Mega\SmsNotifications\Listeners\Backend\Orders@AfterShipment');

    }

}
