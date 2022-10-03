<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => 'wassabi_public',

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root'   => storage_path('app'),
        ],

        'public' => [
            'driver'     => 'local',
            'root'       => storage_path('app/public'),
            'url'        => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],

        'public_image' => [
            'driver'     => 'local',
            'root'       => public_path(''),
            'url'        => env('APP_URL') . '/public',
            'visibility' => 'public',
        ],

        's3'                 => [
            'driver' => 's3',
            'key'    => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url'    => env('AWS_URL'),
        ],
        'wassabi_public'     => [
            'driver'   => 's3',
            'key'      => env('WAS_ACCESS_KEY_ID_PUBLIC'),
            'secret'   => env('WAS_SECRET_ACCESS_KEY_PUBLIC'),
            'region'   => env('WAS_DEFAULT_REGION'),
            'bucket'   => env('WAS_BUCKET_PUBLIC'),
            'endpoint' => env('WAS_URL'),
        ],
        'wassabi_private'    => [
            'driver'   => 's3',
            'key'      => env('WAS_ACCESS_KEY_ID_PRIVATE'),
            'secret'   => env('WAS_SECRET_ACCESS_KEY_PRIVATE'),
            'region'   => env('WAS_DEFAULT_REGION'),
            'bucket'   => env('WAS_BUCKET_PRIVATE'),
            'endpoint' => env('WAS_URL'),
        ],
        'distributor_import' => [
            'driver'   => 'ftp',
            'host'     => env('DISTRIBUTOR_IMPORT_URL'),
            'username' => env('DISTRIBUTOR_IMPORT_USER'),
            'password' => env('DISTRIBUTOR_IMPORT_PASSWORD'),
        ],
    ],

];
