<?php
return [
    'admin' => [
        'system' => [
             'megaphonelogin' => [
                 'name' => 'Two Factor Authentication',
                 'general' => [
                     'index' => 'General',
                     'config' => 'Configuration',
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
                    'verification_required' => "Enable Verification For Already Registered User"
                ],
                'template' => [
                     'index' => 'SMS Templates',
                     'verification-code-template' => 'Template for verification Code SMS'
                 ]

            ]
        ],
        'menu' => [
            'notifications' => 'Mega'
        ]
    ],
    'customer' =>[
        'additional-details' => "Additional Details",
        'verify-phone-message' => "Please verify your mobile number",
        'account-exist' => "There is already an account associated with this Mobile Number",
        'code-sent' => 'A verification code has been sent on entered mobile number. Please enter the code to verify your mobile number',
        'invalid-code' => 'Verification code is invalid',
        'phone-verified' => "Mobile Number has been verified",
        'error-sending-code' => "There was an error sending verification code",
        'signup-form' => [
            'phone' => 'Phone Number',
            'invalid-phone' => 'Mobile Number is invalid',
            'popup' => [
                'title' => 'Verify Your Mobile Number',
                'sub-title' => 'A verification code has been sent on entered mobile number. Please enter the code to verify your mobile number',
                'otp-label' => 'Verification Code',
                'verify-otp' => 'Verify',
                'resend-otp' => 'Resend',
                'otp-error' => 'The Verification field is required',
                'close' => 'Close Popup'
            ]
        ],
        'login-form' => [
            'email-phone' => 'Email or mobile number',
            'email-phone-err' => 'Email or mobile number'
        ],
        'forgot-password-form' => [
            'email-phone' => 'Email or mobile number',
            'email-phone-err' => 'Email or mobile number'
        ],
        'profile' => [
            'edit-phone' => "Edit Phone"
        ],
        'verify-phone' => [
            'page-title' => 'Verify Your Mobile Number',
            'send-otp' => 'Send Verification Code'
        ]
    ]
];
