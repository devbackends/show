<?php

namespace Webkul\Marketplace\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('bagisto.shop.customers.signup_form_controls.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('marketplace::shop.customers.signup.seller');
        });

        Event::listen('bagisto.shop.products.view.short_description.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('marketplace::shop.products.product-seller-info');
        });

        Event::listen('bagisto.shop.products.view.booking', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('bookingproduct::shop.products.view.booking');
        });


        Event::listen(['bagisto.shop.checkout.cart.item.name.after', 'bagisto.shop.checkout.cart-mini.item.name.after', 'bagisto.shop.checkout.name.after'], function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('marketplace::shop.checkout.cart.item-seller-info');
        });

/*        Event::listen('bagisto.shop.products.price.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('marketplace::shop.products.product-sellers');
        });*/

        Event::listen('bagisto.admin.layout.head', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('marketplace::admin.layouts.style');
        });

        Event::listen('checkout.cart.add.before', 'Webkul\Marketplace\Listeners\Cart@cartItemAddBefore');

        Event::listen('checkout.cart.add.after', 'Webkul\Marketplace\Listeners\Cart@cartItemAddAfter');

        Event::listen('checkout.order.save.after', 'Webkul\Marketplace\Listeners\Order@afterPlaceOrder');

        Event::listen('sales.shipment.save.after', 'Webkul\Marketplace\Listeners\Shipment@afterShipment');

        Event::listen('sales.invoice.save.after', 'Webkul\Marketplace\Listeners\Invoice@afterInvoice');

        Event::listen('sales.refund.save.after', 'Webkul\Marketplace\Listeners\Refund@afterRefund');

        Event::listen('sales.order.cancel.after', 'Webkul\Marketplace\Listeners\Order@afterOrderCancel');

        Event::listen('catalog.marketplace.attribute.create.after', 'Webkul\Marketplace\Listeners\Product@afterAttributeCreatedUpdated');

        Event::listen('catalog.marketplace.attribute.update.after', 'Webkul\Marketplace\Listeners\Product@afterAttributeCreatedUpdated');

        Event::listen('catalog.marketplace.attribute.delete.before', 'Webkul\Marketplace\Listeners\Product@afterAttributeDeleted');

        Event::listen('catalog.marketplace.product.create.after', 'Webkul\Marketplace\Listeners\Product@afterProductCreatedUpdated');

        Event::listen('catalog.marketplace.product.update.after', 'Webkul\Marketplace\Listeners\Product@afterProductCreatedUpdated');



        //Send sales mails
        Event::listen('marketplace.sales.order.save.after', 'Webkul\Marketplace\Listeners\Order@sendNewOrderMail');

        Event::listen('marketplace.sales.invoice.save.after', 'Webkul\Marketplace\Listeners\Order@sendNewInvoiceMail');

        Event::listen('marketplace.sales.shipment.save.after', 'Webkul\Marketplace\Listeners\Order@sendNewShipmentMail');

        Event::listen('marketplace.sellers.account.settings.payment.after', function ($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('authorize::shop.sellers.account.settings.payment.authorize');
        });
    }
}