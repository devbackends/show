<?php

namespace Webkul\Checkout\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerBillingAddressForm extends FormRequest
{
    /**
     * Determine if the product is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'billing.first_name' => ['required'],
            'billing.last_name'  => ['required'],
            'billing.email'      => ['required'],
            'billing.address1'   => ['required'],
            'billing.city'       => ['required'],
            'billing.state'      => ['required'],
            'billing.postcode'   => ['required'],
            'billing.phone'      => ['required'],
            'billing.country'    => ['required'],
        ];
    }
}