<?php

namespace Webkul\Checkout\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerShippingFflAddress extends FormRequest
{
    public function rules()
    {
        return [
            'shipping.address1'     => ['required'],
            'shipping.city'         => ['required'],
            'shipping.state'        => ['required'],
            'shipping.postcode'     => ['required'],
            'shipping.phone'        => ['required'],
            'shipping.country'      => ['required'],
            'shipping.ffl_id'       => ['required'],
        ];
    }
}
