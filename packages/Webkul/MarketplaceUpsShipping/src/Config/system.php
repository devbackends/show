<?php

return [
    [
        'key' => 'sales.carriers.mpups',
        'name' => 'marketplace_ups::app.admin.system.mp-ups',
        'sort' => 3,
        'fields' => [
            [
                'name' => 'title',
                'title' => 'marketplace_ups::app.admin.system.title',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'description',
                'title' => 'marketplace_ups::app.admin.system.description',
                'type' => 'textarea',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'active',
                'title' => 'marketplace_ups::app.admin.system.status',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Active',
                        'value' => true
                    ], [
                        'title' => 'Inactive',
                        'value' => false
                    ]
                ],
                'validation' => 'required'
            ], [
                'name' => 'mode',
                'title' => 'marketplace_ups::app.admin.system.mode',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Development',
                        'value' => 'DEVELOPMENT'
                    ], [
                        'title' => 'Live',
                        'value' => "LIVE"
                    ]
                ],
                'validation' => 'required'
            ],

            // [
            //     'name' => 'gateway_url',
            //     'title' => 'marketplace_ups::app.admin.system.gateway-url',
            //     'type' => 'text',
            //     'validation' => 'required',
            //     'channel_based' => false,
            //     'locale_based' => true

            // ],

            [
                'name' => 'access_license_key',
                'title' => 'marketplace_ups::app.admin.system.access-license-number',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'user_id',
                'title' => 'marketplace_ups::app.admin.system.user-id',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'password',
                'title' => 'marketplace_ups::app.admin.system.password',
                'type' => 'password',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'shipper_number',
                'title' => 'marketplace_ups::app.admin.system.shipper',
                'type' => 'text',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'container',
                'title' => 'marketplace_ups::app.admin.system.container',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Package',
                        'value' => '02',
                    ], [
                        'title' => 'UPS Letter',
                        'value' => '01'
                    ], [
                        'title' => 'UPS Tube',
                        'value' => '03'
                    ], [
                        'title' => 'UPS Pak',
                        'value' => '04'
                    ], [
                        'title' => 'UPS Express Box',
                        'value' => '21'
                    ],
                ],
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true

            ], [
                'name' => 'weight_unit',
                'title' => 'marketplace_ups::app.admin.system.weight-unit',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'LBS',
                        'value' => 'LBS'
                    ], [
                        'title' => 'KGS',
                        'value' => 'KGS',
                    ]
                ],
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ],  [
                'name' => 'allow_seller',
                'title' => 'marketplace_ups::app.admin.system.allow-seller',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Yes',
                        'value' => true,
                    ], [
                        'title' => 'No',
                        'value' => false
                    ],
                ],
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'services',
                'title' => 'marketplace_ups::app.admin.system.allowed-methods',
                'type' => 'multiselect',
                'options' => [
                    [
                        'title' => 'Next Day Air Early AM',
                        'value' => '14',
                    ], [
                        'title' => 'Next Day Air',
                        'value' => '01'
                    ], [
                        'title' => 'Next Day Air Saver',
                        'value' => '13'
                    ], [
                        'title' => '2nd Day Air AM',
                        'value' => '59'
                    ], [
                        'title' => '2nd Day Air',
                        'value' => '02'
                    ], [
                        'title' => '3 Day Select',
                        'value' => '12'
                    ], [
                        'title' => 'Ups Ground',
                        'value' => '03'
                    ], [
                        'title' => 'UPS Worldwide Express',
                        'value' => '07'
                    ], [
                        'title' => 'UPS Worldwide Express Plus',
                        'value' => '54'
                    ], [
                        'title' => 'UPS Worldwide Expedited',
                        'value' => '08'
                    ], [
                        'title' => 'UPS World Wide Saver',
                        'value' => '65'
                    ],

                ],
                'channel_based' => false,
                'locale_based' => true
            ],
        ]
    ],
];