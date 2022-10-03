<?php

return [
    'braintree' => [
        'code'                  => 'braintree',
        'title'                 => 'Braintree',
        'description'           => 'Braintree Payment',
        'class'                 => 'Webkul\Braintree\Payment\BraintreePayment',
        'active'                => false,
        'sort'                  => 4,
        'sandbox'               => true,
        'braintree_merchant_id' => 'merchant_id',
        'braintree_public_key'  => 'public_key',
        'braintree_private_key' => 'braintree_private_key',
    ],
];
