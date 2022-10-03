<?php

return [
   [
        'key' => 'sales.paymentmethods.clearent',
        'name' => 'clearent::app.admin.system.clearentPayment',
        'sort' => 6,
        'fields' => [
            [
                'name' => 'title',
                'title' => 'clearent::app.admin.system.title',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ],
            [
                'name' => 'description',
                'title' => 'clearent::app.admin.system.description',
                'type' => 'textarea',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ],
            [
                'name' => 'private_key',
                'title' => 'clearent::app.admin.system.private_key',
                'type' => 'password',
                'channel_based' => false,
                'locale_based' => false,
            ],
            [
                'name' => 'public_key',
                'title' => 'clearent::app.admin.system.public_key',
                'type' => 'password',
                'channel_based' => false,
                'locale_based' => false,
            ],
            [
                'name'          => 'active',
                'title'         => 'admin::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true
            ],
            [
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