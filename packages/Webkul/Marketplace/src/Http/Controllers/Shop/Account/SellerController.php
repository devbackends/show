<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Account;

use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Webkul\Marketplace\Http\Requests\SellerForm;
use Webkul\Marketplace\Repositories\SellerRepository;

class SellerController extends SellerAccountBaseController
{

    /**
     * @return View
    */
    public function edit(): View
    {
        return view($this->_config['view'], [
            'seller' => $this->seller,
            'defaultCountry' => config('app.default_country')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SellerForm $request
     * @param $id
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(SellerForm $request, $id)
    {
        if (isset($request['uscca_certified'])) {
            $this->validate($request, [
                'instructor_number'=>'required|min:6'
            ]);
        }

        app(SellerRepository::class)->update(request()->all(), $id);

        session()->flash('success', 'Your profile saved successfully.');

        return redirect()->route($this->_config['redirect']);
    }
}