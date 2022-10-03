<?php
return [
    'cashsale'  => [
        'code'        => 'cashsale',
        'title'       => 'Cash Sale',
        'description' => 'Cash Sale',
        'class'       => 'Webkul\Payment\Payment\CashSale',
        'active'      => false,
        'sort'        => 1,
    ],

    'check'  => [
        'code'        => 'check',
        'title'       => 'Check',
        'description' => 'Check',
        'class'       => 'Webkul\Payment\Payment\Check',
        'active'      => false,
        'sort'        => 1,
    ],

    'banktransfer'   => [
        'code'        => 'banktransfer',
        'title'       => 'Bank Transfer',
        'description' => 'Bank Transfer',
        'class'       => 'Webkul\Payment\Payment\BankTransfer',
        'active'      => false,
        'sort'        => 2,
    ],
];