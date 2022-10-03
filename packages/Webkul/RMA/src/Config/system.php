<?php

return [
    [
        'key' => 'sales.rma',
        'name' => 'rma::app.admin.admin-name.rma',
        'sort' => 2
    ],
    [
        'key' => 'sales.rma.setting',
        'name' => 'rma::app.admin.setting.general',
        'sort' => 2,
        'fields' => [
            [
                'name' => 'default_allow_days',
                'title' => 'rma::app.admin.setting.fields.default-allow-days',
                'type' =>  'text',
                'validation' => 'required|numeric',
                'channel_based' => true,
                'locale_based' => true
            ],
        ],
    ],
];
