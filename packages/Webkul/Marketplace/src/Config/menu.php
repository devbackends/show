<?php
return [
    [
        'key' => 'marketplace',
        'name' => 'marketplace::app.shop.layouts.marketplace',
        'route' => 'marketplace.account.seller.edit',
        'sort' => 2
    ], [
        'key' => 'marketplace.seller',
        'name' => 'marketplace::app.shop.layouts.profile',
        'route' => 'marketplace.account.seller.edit',
        'sort' => 1
    ], [
        'key' => 'marketplace.dashboard',
        'name' => 'marketplace::app.shop.layouts.dashboard',
        'route' => 'marketplace.account.dashboard.index',
        'sort' => 2
    ], [
        'key' => 'marketplace.products',
        'name' => 'marketplace::app.shop.layouts.products',
        'route' => 'marketplace.account.products.index',
        'sort' => 3
    ], [
        'key' => 'marketplace.orders',
        'name' => 'marketplace::app.shop.layouts.orders',
        'route' => 'marketplace.account.orders.index',
        'sort' => 4
    ],
    [
    'key' => 'marketplace.coupons',
    'name' => 'Coupons',
    'route' => 'marketplace.account.coupons.index',
    'sort' => 5
],/* [
        'key' => 'marketplace.transactions',
        'name' => 'marketplace::app.shop.layouts.transactions',
        'route' => 'marketplace.account.transactions.index',
        'sort' => 6
    ],*/ [
        'key' => 'marketplace.reviews',
        'name' => 'marketplace::app.shop.layouts.reviews',
        'route' => 'marketplace.account.reviews.index',
        'sort' => 7
    ], [
        'key' => 'marketplace.settings',
        'name' => 'marketplace::app.shop.layouts.store-settings',
        'route' => 'marketplace.account.settings.index',
        'sort' => 8
    ]
];