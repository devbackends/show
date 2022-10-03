<?php

return [
    [
        'key' => 'sales.carriers.tablerate',
        'name' => 'tablerate::app.admin.system.tablerate-shipping',
        'sort' => 3,
        'fields' => [
            [
                'name' => 'title',
                'title' => 'tablerate::app.admin.system.title',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name' => 'description',
                'title' => 'tablerate::app.admin.system.description',
                'type' => 'textarea',
                'channel_based' => false,
                'locale_based' => true
            ], [
                'name'       => 'type',
                'title'      => 'admin::app.admin.system.type',
                'type'       => 'select',
                'options'    => [
                    [
                        'title' => 'tablerate::app.admin.system.per_unit',
                        'value' => 'per_unit',
                    ], [
                        'title' => 'tablerate::app.admin.system.per_order',
                        'value' => 'per_order',
                    ]
                ],
                'validation' => 'required'
            ], [
                'name' => 'active',
                'title' => 'tablerate::app.admin.system.status',
                'type'          => 'boolean',
                'validation' => 'required'
            ]
        ]
    ],
];