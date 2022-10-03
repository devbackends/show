<?php

return [
    [
        'key' => 'marketplace',
        'name' => 'marketplace::app.admin.acl.marketplace',
        'route' => 'admin.marketplace.sellers.index',
        'sort' => 2
    ], [
        'key' => 'marketplace.sellers',
        'name' => 'marketplace::app.admin.acl.sellers',
        'route' => 'admin.marketplace.sellers.index',
        'sort' => 1
    ], [
        'key' => 'marketplace.products',
        'name' => 'marketplace::app.admin.acl.products',
        'route' => 'admin.marketplace.products.index',
        'sort' => 2
    ], [
        'key' => 'marketplace.reviews',
        'name' => 'marketplace::app.admin.acl.reviews',
        'route' => 'admin.marketplace.reviews.index',
        'sort' => 3
    ]
];