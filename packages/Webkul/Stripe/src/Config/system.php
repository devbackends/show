<?php

return [
   [
        'key' => 'sales.paymentmethods.stripe',
        'name' => 'stripe::app.admin.system.stripePayment',
        'sort' => 6,
        'fields' => [
            [
                'name'          => 'active',
                'title'         => 'admin::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true
            ], [
                'name' => 'title',
                'title' => 'stripe::app.admin.system.title',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'description',
                'title' => 'stripe::app.admin.system.description',
                'type' => 'textarea',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'debug',
                'title' => 'stripe::app.admin.system.debug',
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
                'name' => 'api_key',
                'title' => 'stripe::app.admin.system.api_key',
                'type' => 'password',
                'channel_based' => false,
                'locale_based' => false,
                'info' => 'Applicable if production is selected',
            ], [
                'name' => 'api_publishable_key',
                'title' => 'stripe::app.admin.system.api_publishable_key',
                'type' => 'password',
                'channel_based' => false,
                'locale_based' => false,
                'info' => 'Applicable if production is selected',
            ], [
                'name' => 'client_id',
                'title' => 'stripe::app.admin.system.client_id',
                'type' => 'text',
                'channel_based' => false,
                'locale_based' => false,
            ], [
                'name' => 'api_test_key',
                'title' => 'stripe::app.admin.system.api_test_key',
                'type' => 'password',
                'channel_based' => false,
                'locale_based' => false,
                'info' => 'Applicable if sandbox is selected',
            ], [
                'name' => 'api_test_publishable_key',
                'title' => 'stripe::app.admin.system.api_test_publishable_key',
                'type' => 'password',
                'channel_based' => false,
                'locale_based' => false,
                'info' => 'Applicable if sandbox is selected',
            ],[
                'name' => 'sort',
                'title' => 'admin::app.admin.system.sort_order',
                'type' => 'select',
                'options' => [
                    [
                        'title' => '1',
                        'value' => 1
                    ], [
                        'title' => '2',
                        'value' => 2
                    ], [
                        'title' => '3',
                        'value' => 3
                    ], [
                        'title' => '4',
                        'value' => 4
                    ]
                ],
            ]
        ]
    ],
];