<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Account;

use Devvly\FluidPayment\Models\FluidCustomer;
use Illuminate\Support\Facades\DB;
use Webkul\Marketplace\Service\SellerType;

class SellerUpgradeController extends SellerAccountBaseController
{

    public function index()
    {
        return view($this->_config['view'], [
            'seller' => $this->seller
        ]);
    }

    public function submit()
    {
        if ($this->seller->type !== 'basic') return response()->redirectToRoute('marketplace.account.settings.index');

        // Start transaction
        DB::beginTransaction();

        // Update seller type
        $this->seller->type = 'plus';
        $this->seller->save();

        // Create fluid subscription
        $subscription = (new SellerType($this->seller))->init();

        if ($subscription) {
            FluidCustomer::query()->where('seller_id', $this->seller->id)->update(['is_approved' => 0]);
            DB::commit();
            session()->flash('success', 'Your seller account have been successfully upgraded');
            return response()->redirectToRoute('marketplace.account.seller.upgrade.index');
        } else {
            DB::rollBack();
            session()->flash('error', 'Something went wrong');
            return back();
        }
    }

}