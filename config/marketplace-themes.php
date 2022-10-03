<?php

return [
    'default' => 'default',

    'themes' => [
        'default' => [
            'views_path' => 'resources/themes/default/views',
            'assets_path' => 'public/themes/default/assets',
            'name' => 'Default'
        ],
        'velocity' => [
            'views_path' => 'resources/themes/velocity/views',
            'assets_path' => 'public/themes/velocity/assets',
            'name' => 'Velocity',
            'parent' => 'default'
        ],
        'market' => [
            'views_path' => 'packages/Webkul/Marketplace/src/Resources/views/shop/market',
            'assets_path' => 'public/themes/market/assets',
            'name' => 'Market',
            'parent' => 'default'
        ]
    ]
];