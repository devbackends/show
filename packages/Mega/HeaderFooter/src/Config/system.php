<?php

return [
    [
        'key' => 'megaheaderfooter',
        'name' => 'megaheaderfooter::app.admin.system.megaheaderfooter.name',
        'sort' => 50
    ],
    [
        'key' => 'megaheaderfooter.general',
        'name' => 'megaheaderfooter::app.admin.system.megaheaderfooter.general.index',
        'sort' => 60,
    ],[
        'key' => 'megaheaderfooter.general.headerfooter',
        'name' => 'megaheaderfooter::app.admin.system.megaheaderfooter.general.index',
        'sort' => 70,
        'fields' => [
            [
                'name' => 'active',
                'title' => 'megaheaderfooter::app.admin.system.megaheaderfooter.general.active',
                'type' => 'boolean',
                'channel_based' => true
            ]
        ]
    ],[
        'key' => 'megaheaderfooter.general.header',
        'name' => 'megaheaderfooter::app.admin.system.megaheaderfooter.header.index',
        'sort' => 70,
        'fields' => [
              [
                    'name' => 'header_html',
                    'title' => 'megaheaderfooter::app.admin.system.megaheaderfooter.header.header_html',
                    'type' => 'textarea',
                    'channel_based' => true
              ],[
                'name' => 'header_js',
                'title' => 'megaheaderfooter::app.admin.system.megaheaderfooter.header.header_js',
                'type' => 'textarea',
                'channel_based' => true,
                'placeholder' => '',
                'note'=>"Scripts must be wrapped in the <script> tag."
            ]
        ]
    ],[
        'key' => 'megaheaderfooter.general.footer',
        'name' => 'megaheaderfooter::app.admin.system.megaheaderfooter.footer.index',
        'sort' => 70,
        'fields' => [
            [
                'name' => 'footer_html',
                'title' => 'megaheaderfooter::app.admin.system.megaheaderfooter.footer.footer_html',
                'type' => 'textarea',
                'channel_based' => true
            ],[
                'name' => 'misc_js',
                'title' => 'megaheaderfooter::app.admin.system.megaheaderfooter.footer.misc_js',
                'type' => 'textarea',
                'channel_based' => true,
                'placeholder'=>"",
                'note'=>"Scripts must be wrapped in the <script> tag."
            ],[
                'name' => 'misc_css',
                'title' => 'megaheaderfooter::app.admin.system.megaheaderfooter.footer.misc_css',
                'type' => 'textarea',
                'channel_based' => true
            ]
        ]
    ]
];