<?php

namespace Webkul\Marketplace\Http\Middleware;

use Closure;
use Devvly\FluidPayment\Models\FluidCustomer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Webkul\Marketplace\Models\Seller as SellerModel;

class Seller
{
    /**
     * Check if the seller profile is filled in
     * @param Request $request
     * @param Closure $next
     * @return RedirectResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {


        // Check if customer is logged in
        $customer = auth()->guard('customer')->user();
        if (!$customer) {
            return response()->redirectToRoute('customer.session.index');
        }

        // Check if seller
        $seller = SellerModel::query()->firstWhere('customer_id', $customer->id);
        if (!$seller) {
            return response()->redirectToRoute('marketplace.account.seller.create');
        }

        // Check if card set
        $fluidCustomer = FluidCustomer::query()->where('seller_id', '=', $seller->id)->first();
        $isSettingsUrls = strpos($request->route()->action['as'], 'marketplace.account.settings') !== false;
        if (!$fluidCustomer && !$isSettingsUrls) {
            session()->flash('error', 'Alert! Sellers must have a credit card on file. To add your card, go to the “Your Card” tab of the Store Settings page');
            return response()->redirectToRoute('marketplace.account.settings.index');
        }

        return $next($request);
    }
}