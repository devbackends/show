<?php

return [
    'admin' => [
        'system' => [
            'name' => 'SMS Notifications',
            'general' => 'General',
            'active' => 'Enable Extension',
            'api' => [
                'api' => 'API Provider',
                'select-api' => 'Please Select an API',
                'spring-edge' => 'Spring Edge',
                'msg91' => 'MSG91',
                'textlocal' => 'Text Local API',
                'twilio'    => 'Twilio API',
                'jawalbsms' => 'JawalbSMS'
            ],
            'sender_id' => 'Sender ID',
            'api_key' => 'API Key',
            'api_pass' => 'API Password',
            'admin' => [
                'admin' => 'Admin Notifications',
                'active' => 'Enable Notifications For Admin',
                'phone' => 'Admin Phone Number',
                'new_user' => 'Enable Notifications For Customer Registration',
                'new_user_template' =>'SMS Template for New Customer Registration',
                'new_order' => 'Send Notifications for Order Placed',
                'new_order_template' => 'New Order Template',
                'new_invoice' => 'Send Notifications on New Invoice',
                'new_invoice_template' => 'SMS Template For New Invoice',
                'new_shipment' => 'Send Notifications on New Shipment',
                'new_shipment_template' => 'SMS Template For New Shipment',
                'order_cancel' => 'Send Notifications on Cancel',
                'order_cancel_template' => 'SMS Template For Cancel',
                'customer_registration' => 'Send Notification On Customer Registration',
                'customer_registration_template' => 'Customer Registration Template'
            ],
            'customer' => [
                'customer' => 'Customer Notifications',
                'notifications_reciever' => [
                    'notifications_reciever' => 'Notifications Reciever',
                    'registered_number' => 'Registered Number',
                    'shipping_address_number' => 'Shipping Address Number',
                    'both' => 'Both Numbers'
                ] ,
                'active' => 'Enable Notifications For Customer',
                'new_user' => 'Enable Notifications For Success Registration',
                'new_user_template' =>'SMS Template for Success Registration',
                'new_order' => 'Send Notifications for Order Placed',
                'new_order_template' => 'New Order Template',
                'new_invoice' => 'Send Notifications on New Invoice',
                'new_invoice_template' => 'SMS Template For New Invoice',
                'new_shipment' => 'Send Notifications on New Shipment',
                'new_shipment_template' => 'SMS Template For New Shipment',
                'order_cancel' => 'Send Notifications on Cancel',
                'order_cancel_template' => 'SMS Template For Cancel',
                'success_login' => 'Login Successful',
                'success_login_template' => 'SMS template for login success',
                'success_registration' => 'Registration Successful',
                'success_registration_template' => 'SMS template for registration success',
                'success_password_reset' => 'Password Reset',
                'success_password_reset_template' => 'SMS template for password reset'
            ]
        ],
        'menu' => [
            'notifications' => 'Meg'
        ]
    ],
    'customer' =>[
        'signup-form' => [
            'phone' => 'Phone Number'
        ],
    ]
];
