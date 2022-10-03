<?php

return [
    'tablerate' => [
        'code'          => 'tablerate',
        'title'         => 'Table Rate Shipping',
        'description'   => 'Table Rate Shipping',
        'active'        => false,
        'default_rate'  => 20,
        'type'          => 'per_unit',
        'class'         => 'Webkul\TableRate\Carriers\TableRate',
    ]
];