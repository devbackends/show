<?php

return [
    [
        'key' => 'sales.carriers.mpfedex',
        'name' => 'marketplace_fedex::app.admin.system.mp-fedex',
        'sort' => 3,
        'fields' => [
            [
                'name' => 'title',
                'title' => 'marketplace_fedex::app.admin.system.title',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'description',
                'title' => 'marketplace_fedex::app.admin.system.description',
                'type' => 'textarea',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'active',
                'title' => 'marketplace_fedex::app.admin.system.status',
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
                'name' => 'account_id',
                'title' => 'marketplace_fedex::app.admin.system.account-id',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'meter_number',
                'title' => 'marketplace_fedex::app.admin.system.meter-number',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'postcode',
                'title' => 'marketplace_fedex::app.admin.system.postcode',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'key',
                'title' => 'marketplace_fedex::app.admin.system.key',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'password',
                'title' => 'marketplace_fedex::app.admin.system.password',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'sandbox_mode',
                'title' => 'marketplace_fedex::app.admin.system.sandbox-mode',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Yes',
                        'value' => true
                    ], [
                        'title' => 'No',
                        'value' => false
                    ]
                ],
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'packaging_type',
                'title' => 'marketplace_fedex::app.admin.system.packaging-type',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Your Packaging',
                        'value' => 'YOUR_PACKAGING',
                    ], [
                        'title' => 'FedEx Envelope',
                        'value' => 'FEDEX_ENVELOPE'
                    ], [
                        'title' => 'FedEx Pak',
                        'value' => 'FEDEX_PAK'
                    ], [
                        'title' => 'FedEx Box',
                        'value' => 'FEDEX_BOX'
                    ], [
                        'title' => 'FedEx Tube',
                        'value' => 'FEDEX_TUBE'
                    ], [
                        'title' => 'FedEx 10kg Box',
                        'value' => 'FEDEX_10KG_BOX'
                    ], [
                        'title' => 'FedEx 25kg Box',
                        'value' => 'FEDEX_25KG_BOX'
                    ]
                ],
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'dropoff_type',
                'title' => 'marketplace_fedex::app.admin.system.dropoff-type',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Regular Pickup',
                        'value' => 'REGULAR_PICKUP',
                    ], [
                        'title' => 'Request Courier',
                        'value' => 'REQUEST_COURIER'
                    ], [
                        'title' => 'Drop Box',
                        'value' => 'DROP_BOX'
                    ], [
                        'title' => 'Business Service Center',
                        'value' => 'BUSINESS_SERVICE_CENTER'
                    ], [
                        'title' => 'Station',
                        'value' => 'STATION'
                    ],
                ],
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'weight_unit',
                'title' => 'marketplace_fedex::app.admin.system.weight-unit',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Pounds',
                        'value' => 'LB',
                    ], [
                        'title' => 'Kilograms',
                        'value' => 'KG'
                    ],
                ],
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'allow_seller',
                'title' => 'marketplace_fedex::app.admin.system.allow-seller',
                'type' => 'select',
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
                'name' => 'services',
                'title' => 'marketplace_fedex::app.admin.system.services',
                'type' => 'multiselect',
                'options' => [
                    [
                        'title' => 'Europe First Priority',
                        'value' => 'EUROPE_FIRST_INTERNATIONAL_PRIORITY',
                    ], [
                        'title' => '1 Day Freight',
                        'value' => 'FEDEX_1_DAY_FREIGHT'
                    ], [
                        'title' => '2 Day Freight',
                        'value' => 'FEDEX_2_DAY_FREIGHT'
                    ], [
                        'title' => '2 Day',
                        'value' => 'FEDEX_2_DAY'
                    ], [
                        'title' => '2 Day AM',
                        'value' => 'FEDEX_2_DAY_AM'
                    ], [
                        'title' => '3 Day Freight',
                        'value' => 'FEDEX_3_DAY_FREIGHT'
                    ], [
                        'title' => 'Express Saver',
                        'value' => 'FEDEX_EXPRESS_SAVER'
                    ], [
                        'title' => 'Ground',
                        'value' => 'FEDEX_GROUND'
                    ], [
                        'title' => 'First Overnight',
                        'value' => 'FIRST_OVERNIGHT'
                    ], [
                        'title' => 'Ground Home Delivery',
                        'value' => 'GROUND_HOME_DELIVERY'
                    ], [
                        'title' => 'International Economy',
                        'value' => 'INTERNATIONAL_ECONOMY'
                    ], [
                        'title' => 'International Economy Freight',
                        'value' => 'INTERNATIONAL_ECONOMY_FREIGHT'
                    ], [
                        'title' => 'International First',
                        'value' => 'INTERNATIONAL_FIRST'
                    ], [
                        'title' => 'International Ground',
                        'value' => 'INTERNATIONAL_GROUND'
                    ], [
                        'title' => 'International Priority',
                        'value' => 'INTERNATIONAL_PRIORITY'
                    ], [
                        'title' => 'International Priority Freight',
                        'value' => 'INTERNATIONAL_PRIORITY_FREIGHT'
                    ], [
                        'title' => 'Priority Overnight',
                        'value' => 'PRIORITY_OVERNIGHT'
                    ], [
                        'title' => 'Smart Post',
                        'value' => 'SMART_POST'
                    ], [
                        'title' => 'Standard Overnight',
                        'value' => 'STANDARD_OVERNIGHT'
                    ], [
                        'title' => 'Freight',
                        'value' => 'FEDEX_FREIGHT'
                    ], [
                        'title' => 'National Freight',
                        'value' => 'FEDEX_NATIONAL_FREIGHT'
                    ],
                ],
                'channel_based' => false,
                'locale_based' => true
            ],[
                'name' => 'length_class',
                'title' => 'marketplace_fedex::app.admin.system.length-class',
                'type' => 'select',
                'validation' => 'required',
                'options' => [
                    [
                        'title' => 'Inches',
                        'value' => 'IN',
                    ], [
                        'title' => 'Centimeter',
                        'value' => 'CM'
                    ]
                ],
                'channel_based' => false,
                'locale_based' => true
            ],

        ]
    ],
];