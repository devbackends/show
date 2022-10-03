<?php
    return [
        'general' => [
            'active' => 1
        ],
        'admin' => [
            'active' => 1,
            'new_order' => 1,
            'new_order_template' => 'New Order Recieved from __customer_firstname__ __customer_lastname__, email __customer_email__, Amount: __order_amount__, Order ID :#__order_id__',
            'new_invoice' => 1,
            'new_invoice_template' => 'Invoice with id #__invoice_id__ generated for order #__order_id__ by Admin -__admin_name__',
            'new_shipment' => 1,
            'new_shipment_template' => "Shipment with id #__shipment_id__ generated for order id #__order_id__ by Admin -__admin_name__",
            'order_cancel' => 1,
            'order_cancel_template' => 'Order with id #__order_id__ cancelled by Admin -__admin_name__',
            'customer_registration' => 1,
            'customer_registration_template' => 'New user with email id __user_email__ registered'
        ],
        'customer' => [
            'active' => 1,
            'notifications_reciever' => 0,
            'new_order' => 1,
            'new_order_template' => 'Dear __customer_firstname__ __customer_lastname__! You order has been recieved. Thank you for placing order with us',
            'new_invoice' => 1,
            'new_invoice_template' => 'Dear __customer_firstname__ __customer_lastname__! invoice with id #__invoice_id__ has been generated for your order #__order_id__.',
            'new_shipment' => 1,
            'new_shipment_template' => 'Dear __customer_firstname__ __customer_lastname__! shipment with id #__shipment_id__ has been generated for your order #__order_id__.',
            'order_cancel' => 1,
            'order_cancel_template' => 'Dear __customer_firstname__ __customer_lastname__! your order #__order_id__ has been cancelled.',
            'success_login' => 1,
            'success_login_template' => 'Dear __customer_firstname__ __customer_lastname__! Your have been logged in to your account',
            'success_registration' => 1,
            'success_registration_template' => 'Dear __customer_firstname__ __customer_lastname__! Thank you for creating an account with us.',
            'success_password_reset' => 1,
            'success_password_reset_template' => 'Dear __customer_firstname__ __customer_lastname__! Your password has been reset successfully'
        ]
    ]
?>