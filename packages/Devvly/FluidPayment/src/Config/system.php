<?php

return [
    [
        'key' => 'sales.paymentmethods.fluid',
        'name' => 'fluid::app.admin.system.fluidPayment',
        'sort' => 6,
        'fields' => [
            [
                'name' => 'title',
                'title' => 'fluid::app.admin.system.title',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ],
            [
                'name' => 'description',
                'title' => 'fluid::app.admin.system.description',
                'type' => 'textarea',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ],
            [
                'name' => 'api_key',
                'title' => 'fluid::app.admin.system.api_key',
                'type' => 'password',
                'channel_based' => false,
                'locale_based' => false,
            ],
            [
                'name' => 'public_key',
                'title' => 'fluid::app.admin.system.public_key',
                'type' => 'password',
                'channel_based' => false,
                'locale_based' => false,
            ],
            [
                'name' => 'active',
                'title' => 'admin::app.admin.system.status',
                'type' => 'boolean',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
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