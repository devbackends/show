<?php

return [
    'basic' => [
        'listingFee' => [
            'enabled' => true,
            'amount' => 0.99,
        ],
        'orderComission' => [
            'enabled' => true,
            'percentage' => 4,
        ],
        'subscription' => [
            'enabled' => false,
            'type' => 'monthly',
            'amount' => 0.0
        ],
    ],

    'plus' => [
        'listingFee' => [
            'enabled' => false,
            'amount' => 0.0,
        ],
        'orderComission' => [
            'enabled' => true,
            'percentage' => 2,
        ],
        'subscription' => [
            'enabled' => false,
            'type' => 'monthly',
            'amount' => 10
        ],
    ],

    'pro' => [
        'listingFee' => [
            'enabled' => false,
            'amount' => 0.0,
        ],
        'orderComission' => [
            'enabled' => false,
            'percentage' => 0.0,
        ],
        'subscription' => [
            'enabled' => true,
            'type' => 'monthly',
            'amount' => 19.99
        ],
    ],

    'god' => [
        'listingFee' => [
            'enabled' => false,
            'amount' => 0.0,
        ],
        'orderComission' => [
            'enabled' => false,
            'percentage' => 0.0,
        ],
        'subscription' => [
            'enabled' => false,
            'type' => 'monthly',
            'amount' => 0
        ],
    ],
];