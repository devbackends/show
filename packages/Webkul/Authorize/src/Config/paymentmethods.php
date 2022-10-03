<?php

return [
    'authorize' => [
        'code' => 'authorize',
        'title' => 'Authorize',
        'description' => 'Marketplace Authorize Net',
        'client_key' => 'api_key',
        'class' => 'Webkul\Authorize\Payment\AuthorizePayment',
        'debug' => true,
        'active' => false
    ]
];
