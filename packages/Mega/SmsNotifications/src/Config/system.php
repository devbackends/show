<?php
return [
    [
        'key' => 'megasmsnotifications',
        'name' => 'megaSmsNotifications::app.admin.system.name',
        'sort' => 50
    ],
    [
        'key' => 'megasmsnotifications.general',
        'name' => 'megaSmsNotifications::app.admin.system.general',
        'sort' => 60,
        'active' => false

    ],[
        'key' => 'megasmsnotifications.general.general',
        'name' => 'megaSmsNotifications::app.admin.system.general',
        'sort' => 70,
        'active' => false,
        'fields' => [
            [
                'name' => 'active',
                'title' => 'megaSmsNotifications::app.admin.system.active',
                'type' => 'boolean'
            ],[
                'name' => 'api',
                'title' => 'megaSmsNotifications::app.admin.system.api.api',
                'type' => 'select',
                'options' => [
                    ['value' => 0,'title' => 'megaSmsNotifications::app.admin.system.api.select-api'],
                    ['value' => 1,'title' => 'megaSmsNotifications::app.admin.system.api.spring-edge'],
                    ['value' => 2,'title' => 'megaSmsNotifications::app.admin.system.api.msg91'],
                    ['value' => 3, 'title' => 'megaSmsNotifications::app.admin.system.api.textlocal'],
                    ['value' => 4, 'title' => 'megaSmsNotifications::app.admin.system.api.twilio']
                ]
            ],[
                'name' => 'sender_id',
                'title' => 'megaSmsNotifications::app.admin.system.sender_id',
                'type' => 'text'
            ],[
                'name' => 'api_key',
                'title' => 'megaSmsNotifications::app.admin.system.api_key',
                'type' => 'text'
            ],[
                'name' => 'api_pass',
                'title' => 'megaSmsNotifications::app.admin.system.api_pass',
                'type' => 'text'
            ],
        ]
    ],[
        'key' => 'megasmsnotifications.general.admin',
        'name' => 'megaSmsNotifications::app.admin.system.admin.admin',
        'sort' => 80,
        'fields' => [
            [
                'name' => 'active',
                'title' => 'megaSmsNotifications::app.admin.system.admin.active',
                'type' => 'boolean'
            ],[
                'name' => 'phone',
                'title' => 'megaSmsNotifications::app.admin.system.admin.phone',
                'type' => 'text'
            ],
            [
                'name' => 'new_order',
                'title' => 'megaSmsNotifications::app.admin.system.admin.new_order',
                'type' => 'boolean'
            ],[
                'name' => 'new_order_template',
                'title' => 'megaSmsNotifications::app.admin.system.admin.new_order_template',
                'type' => 'textarea'
            ],
            [
                'name' => 'new_invoice',
                'title' => 'megaSmsNotifications::app.admin.system.admin.new_invoice',
                'type' => 'boolean'
            ],[
                'name' => 'new_invoice_template',
                'title' => 'megaSmsNotifications::app.admin.system.admin.new_invoice_template',
                'type' => 'textarea'
            ],
            [
                'name' => 'new_shipment',
                'title' => 'megaSmsNotifications::app.admin.system.admin.new_shipment',
                'type' => 'boolean'
            ],[
                'name' => 'new_shipment_template',
                'title' => 'megaSmsNotifications::app.admin.system.admin.new_shipment_template',
                'type' => 'textarea'
            ],
            [
                'name' => 'order_cancel',
                'title' => 'megaSmsNotifications::app.admin.system.admin.order_cancel',
                'type' => 'boolean'
            ],[
                'name' => 'order_cancel_template',
                'title' => 'megaSmsNotifications::app.admin.system.admin.order_cancel_template',
                'type' => 'textarea'
            ],
            [
                'name' => 'customer_registration',
                'title' => 'megaSmsNotifications::app.admin.system.admin.customer_registration',
                'type' => 'boolean'
            ],[
                'name' => 'customer_registration_template',
                'title' => 'megaSmsNotifications::app.admin.system.admin.customer_registration_template',
                'type' => 'textarea'
            ]
        ]
    ],[
        'key' => 'megasmsnotifications.general.customer',
        'name' => 'megaSmsNotifications::app.admin.system.customer.customer',
        'sort' => 90,
        'fields'=>[
            [
                'name' => 'active',
                'title' => 'megaSmsNotifications::app.admin.system.customer.active',
                'type' => 'boolean'
            ],
            [
                'name' => 'notifications_reciever',
                'title' => 'megaSmsNotifications::app.admin.system.customer.notifications_reciever.notifications_reciever',
                'type' => 'select',
                'options' => [
                    ['value' => 0,'title' => 'megaSmsNotifications::app.admin.system.customer.notifications_reciever.registered_number'],
                    ['value' => 1,'title' => 'megaSmsNotifications::app.admin.system.customer.notifications_reciever.shipping_address_number'],
                    ['value' => 2, 'title' => 'megaSmsNotifications::app.admin.system.customer.notifications_reciever.both']
                ]
            ],
            [
                'name' => 'new_order',
                'title' => 'megaSmsNotifications::app.admin.system.customer.new_order',
                'type' => 'boolean'
            ],[
                'name' => 'new_order_template',
                'title' => 'megaSmsNotifications::app.admin.system.customer.new_order_template',
                'type' => 'textarea'
            ],
            [
                'name' => 'new_invoice',
                'title' => 'megaSmsNotifications::app.admin.system.customer.new_invoice',
                'type' => 'boolean'
            ],[
                'name' => 'new_invoice_template',
                'title' => 'megaSmsNotifications::app.admin.system.customer.new_invoice_template',
                'type' => 'textarea'
            ],
            [
                'name' => 'new_shipment',
                'title' => 'megaSmsNotifications::app.admin.system.customer.new_shipment',
                'type' => 'boolean'
            ],[
                'name' => 'new_shipment_template',
                'title' => 'megaSmsNotifications::app.admin.system.customer.new_shipment_template',
                'type' => 'textarea'
            ],
            [
                'name' => 'order_cancel',
                'title' => 'megaSmsNotifications::app.admin.system.customer.order_cancel',
                'type' => 'boolean'
            ],[
                'name' => 'order_cancel_template',
                'title' => 'megaSmsNotifications::app.admin.system.customer.order_cancel_template',
                'type' => 'textarea'
            ],[
                'name' => 'success_login',
                'title' => 'megaSmsNotifications::app.admin.system.customer.success_login',
                'type' => 'boolean'
            ],[
                'name' => 'success_login_template',
                'title' => 'megaSmsNotifications::app.admin.system.customer.success_login_template',
                'type' => 'textarea'
            ],[
                'name' => 'success_registration',
                'title' => 'megaSmsNotifications::app.admin.system.customer.success_registration',
                'type' => 'boolean'
            ],[
                'name' => 'success_registration_template',
                'title' => 'megaSmsNotifications::app.admin.system.customer.success_registration_template',
                'type' => 'textarea'
            ],[
                'name' => 'success_password_reset',
                'title' => 'megaSmsNotifications::app.admin.system.customer.success_password_reset',
                'type' => 'boolean'
            ],[
                'name' => 'success_password_reset_template',
                'title' => 'megaSmsNotifications::app.admin.system.customer.success_password_reset_template',
                'type' => 'textarea'
            ]

        ]
    ]
];
