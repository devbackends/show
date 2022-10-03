<?php

return [
    'stripe' => [
        'code' => 'stripe',
        'title' => 'Credit Card',
        'description' => 'Stripe Payments',
        'class' => 'Webkul\Stripe\Payment\StripePayment',
        'debug' => true,
        'active' => false
    ]
];