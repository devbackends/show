<?php

namespace Webkul\Checkout\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerShippingAddressForm extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'shipping.first_name' => ['required'],
            'shipping.last_name'  => ['required'],
            'shipping.email'      => ['required'],
            'shipping.address1'   => ['required'],
            'shipping.city'       => ['required'],
            'shipping.state'      => ['required'],
            'shipping.postcode'   => ['required'],
            'shipping.phone'      => ['required'],
            'shipping.country'    => ['required'],
        ];
    }
}