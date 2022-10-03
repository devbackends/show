<?php
return [
    [
        'key' => 'megaPhoneLogin',
        'name' => 'megaPhoneLogin::app.admin.system.megaphonelogin.name',
        'sort' => 50
    ],
    [
        'key' => 'megaPhoneLogin.general',
        'name' => 'megaPhoneLogin::app.admin.system.megaphonelogin.general.config',
        'sort' => 60,
    ],[
        'key' => 'megaPhoneLogin.general.general',
        'name' => 'megaPhoneLogin::app.admin.system.megaphonelogin.general.index',
        'sort' => 70,
        'fields' => [
            [
                'name' => 'active',
                'title' => 'megaPhoneLogin::app.admin.system.megaphonelogin.general.active',
                'type' => 'boolean'
            ],[
                'name' => 'api',
                'title' => 'megaPhoneLogin::app.admin.system.megaphonelogin.general.api.api',
                'type' => 'select',
                'options' => [
                    ['value' => 0,'title' => 'megaPhoneLogin::app.admin.system.megaphonelogin.general.api.select-api'],
                    ['value' => 1,'title' => 'megaPhoneLogin::app.admin.system.megaphonelogin.general.api.spring-edge'],
                    ['value' => 2,'title' => 'megaPhoneLogin::app.admin.system.megaphonelogin.general.api.msg91'],
                    ['value' => 3, 'title' => 'megaPhoneLogin::app.admin.system.megaphonelogin.general.api.textlocal'],
                    ['value' => 4, 'title' => 'megaPhoneLogin::app.admin.system.megaphonelogin.general.api.twilio']
                ]
            ],[
                'name' => 'sender_id',
                'title' => 'megaPhoneLogin::app.admin.system.megaphonelogin.general.sender_id',
                'type' => 'text'
            ],[
                'name' => 'api_key',
                'title' => 'megaPhoneLogin::app.admin.system.megaphonelogin.general.api_key',
                'type' => 'text'
            ],[
                'name' => 'api_pass',
                'title' => 'megaPhoneLogin::app.admin.system.megaphonelogin.general.api_pass',
                'type' => 'text'
            ],[
                'name' => 'verification-required',
                'title' => 'megaPhoneLogin::app.admin.system.megaphonelogin.general.verification_required',
                'type' => 'boolean'
            ]
        ]
    ],[
        'key' => 'megaPhoneLogin.general.template',
        'name' => 'megaPhoneLogin::app.admin.system.megaphonelogin.template.index',
        'sort' => 70,
        'fields' => [
            [
                'name' => 'verification-code-template',
                'title' => 'megaPhoneLogin::app.admin.system.megaphonelogin.template.verification-code-template',
                'type' => 'textarea'
            ]
        ]
    ]
];
