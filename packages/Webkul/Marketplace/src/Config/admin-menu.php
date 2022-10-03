<?php

return [
    [
        'key' => 'marketplace',
        'name' => 'marketplace::app.admin.layouts.marketplace',
        'route' => 'admin.marketplace.sellers.index',
        'sort' => 2,
        'icon-class' => 'marketplace-icon fal fa-store',
    ], [
        'key' => 'marketplace.sellers',
        'name' => 'marketplace::app.admin.layouts.sellers',
        'route' => 'admin.marketplace.sellers.index',
        'sort' => 1
    ], [
        'key' => 'marketplace.products',
        'name' => 'marketplace::app.admin.layouts.products',
        'route' => 'admin.marketplace.products.index',
        'sort' => 2
    ], [
        'key' => 'marketplace.reviews',
        'name' => 'marketplace::app.admin.layouts.seller-reviews',
        'route' => 'admin.marketplace.reviews.index',
        'sort' => 3
    ], [
        'key' => 'marketplace.orders',
        'name' => 'marketplace::app.admin.layouts.orders',
        'route' => 'admin.marketplace.orders.index',
        'sort' => 3
    ], [
        'key' => 'marketplace.transactions',
        'name' => 'marketplace::app.admin.layouts.transactions',
        'route' => 'admin.marketplace.transactions.index',
        'sort' => 3
    ], [
        'key' => 'marketplace.user-help-requests',
        'name' => 'marketplace::app.shop.layouts.user-help-requests',
        'route' => 'admin.user-help-requests.index',
        'sort' => 8,
    ],
];