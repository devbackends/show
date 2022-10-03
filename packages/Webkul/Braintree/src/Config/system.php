<?php

return [
    [
        'key' => 'sales.paymentmethods.braintree',
        'name' => 'Braintree',
        'sort' => 4,
        'fields' => [
            [
                'name' => 'active',
                'title' => 'Status',
                'type'          => 'boolean',
                'options' => [
                    [
                        'title' => 'Yes',
                        'value' => true
                    ], [
                        'title' => 'No',
                        'value' => false
                    ]
                ],
                'validation' => 'required'
        ], [
            'name' => 'debug',
            'title' => 'Debug',
            'type' => 'select',
            'options' => [
                [
                    'title' => 'Sandbox',
                    'value' => true
                ], [
                    'title' => 'Production',
                    'value' => false
                ]
            ],
            'validation' => 'required'
        ], [
            'name' => 'braintree_merchant_id',
            'title' => 'Braintree Merchant ID',
            'type' => 'text',
            'validation' => 'required',
            'channel_based' => true,
            'locale_based' => true
        ], [
            'name' => 'braintree_public_key',
            'title' => 'Braintree Public Key',
            'type' => 'password',
            'validation' => 'required',
            'channel_based' => true,
            'locale_based' => true
        ], [
            'name' => 'braintree_private_key',
            'title' => 'Braintree Private key',
            'type' => 'password',
            'validation' => 'required',
            'channel_based' => true,
            'locale_based' => true
            ]
        ]
    ]
];
