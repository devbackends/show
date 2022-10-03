<?php

return [
    [
        'key' => 'tenants',
        'name' => 'saas::app.super-user.layouts.left-menu.tenants',
        'route' => 'super.tenants.index',
        'sort' => 3,
        'icon-class' => 'company-icon',
    ], [
        'key' => 'tenants.companies',
        'name' => 'saas::app.super-user.layouts.left-menu.tenants',
        'route' => 'super.tenants.index',
        'sort' => 1,
        'icon-class' => '',
    ], [
        'key' => 'tenants.customers',
        'name' => 'saas::app.super-user.layouts.left-menu.tenant-customers',
        'route' => 'super.tenants.customers.index',
        'sort' => 2,
        'icon-class' => '',
    ], [
        'key' => 'tenants.products',
        'name' => 'saas::app.super-user.layouts.left-menu.tenant-products',
        'route' => 'super.tenants.products.index',
        'sort' => 3,
        'icon-class' => '',
    ], [
        'key' => 'tenants.orders',
        'name' => 'saas::app.super-user.layouts.left-menu.tenant-orders',
        'route' => 'super.tenants.orders.index',
        'sort' => 3,
        'icon-class' => '',
    ], [
        'key' => 'settings',
        'name' => 'saas::app.super-user.layouts.left-menu.settings',
        'route' => 'super.agents.index',
        'sort' => 4,
        'icon-class' => 'settings-icon'
    ], [
        'key' => 'settings.agents',
        'name' => 'saas::app.super-user.layouts.left-menu.agents',
        'route' => 'super.agents.index',
        'sort' => 1,
        'icon-class' => '',
    ], [
        'key' => 'settings.locales',
        'name' => 'saas::app.super-user.layouts.left-menu.locales',
        'route' => 'super.locales.index',
        'sort' => 2,
        'icon-class' => ''
    ], [
        'key' => 'settings.currencies',
        'name' => 'saas::app.super-user.layouts.left-menu.currencies',
        'route' => 'super.currencies.index',
        'sort' => 3,
        'icon-class' => ''
    ], [
        'key' => 'settings.exchange_rates',
        'name' => 'saas::app.super-user.layouts.left-menu.exchange-rates',
        'route' => 'super.exchange_rates.index',
        'sort' => 4,
        'icon-class' => ''
    ], [
        'key' => 'settings.channels',
        'name' => 'saas::app.super-user.layouts.left-menu.channels',
        'route' => 'super.channels.index',
        'sort' => 5,
        'icon-class' => ''
    ], [
        'key' => 'configuration',
        'name' => 'saas::app.super-user.layouts.left-menu.configurations',
        'route' => 'super.configuration.index',
        'sort' => 5,
        'icon-class' => 'configuration-icon'
    ], [
        'key' => 'configuration.general',
        'name' => 'saas::app.super-user.layouts.left-menu.general',
        'route' => 'super.configuration.index',
        'sort' => 1,
        'icon-class' => ''
    ],
    [
        'key' => 'configuration.sales',
        'name' => 'Sales',
        'route' => 'super.configuration.index',
        'sort' => 2,
        'icon-class' => ''
    ],
    [
    'key'  => 'configuration.sales.carriers',
    'name' => 'Shipping Methods',
    'route' => 'super.configuration.index',
    'sort' => 1,
]
    ,
    [
        'key' => 'predefined',
        'name' => 'Onboarding',
        'route' => 'super.predefined.mmc.index',
        'sort' => 6,
        'icon-class' => 'cms-icon'
    ], [
        'key' => 'predefined.mmc',
        'name' => 'MMC',
        'route' => 'super.predefined.mmc.index',
        'sort' => 1,
        'icon-class' => ''
    ], [
        'key' => 'predefined.business-type',
        'name' => 'Business Type',
        'route' => 'super.predefined.business-type.index',
        'sort' => 2,
        'icon-class' => ''
    ],
    [
        'key' => 'predefined.pricing',
        'name' => 'Pricing',
        'route' => 'super.predefined.pricing.edit',
        'sort' => 3,
        'icon-class' => ''
    ],
    [
        'key' => 'coupons',
        'name' => 'Coupon',
        'route' => 'super.coupon.index',
        'sort' => 7,
        'icon-class' => 'promotion-icon'
    ], [
        'key' => 'coupons.coupon',
        'name' => 'Coupon',
        'route' => 'super.coupon.index',
        'sort' => 1,
        'icon-class' => ''
    ], [
        'key' => 'coupons.type',
        'name' => 'Type',
        'route' => 'super.coupons-type.index',
        'sort' => 2,
        'icon-class' => ''
    ],
    [
        'key' => 'messages',
        'name' => 'Messages',
        'route' => 'super.messages.index',
        'sort' => 8,
        'icon-class' => 'cms-icon'
    ], [
        'key' => 'messages.customers',
        'name' => 'Messages',
        'route' => 'super.messages.index',
        'sort' => 1,
        'icon-class' => ''
    ], [
        'key' => 'messages.reported',
        'name' => 'Reported Messages',
        'route' => 'super.messages.reported',
        'sort' => 2,
        'icon-class' => ''
    ]
];