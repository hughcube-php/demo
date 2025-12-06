<?php

use HughCube\Laravel\Octane\Actions\WaitTaskCompleteAction;

return [
    'default' => 'default',

    'clients' => [
        'default' => [
            'AccessKeyID' => env('FC_ACCESS_KEY_ID'),
            'AccessKeySecret' => env('FC_ACCESS_KEY_SECRET'),
            'RegionId' => env('FC_REGION_ID'),
            'AccountId' => env('FC_ACCOUNT_ID'),
            'Internal' => env('FC_INTERNAL'),
            'ServiceIp' => env('FC_SERVICE_IP'),
            'Http' => [
                'timeout' => 25.0,
                'connect_timeout' => 25.0,
                'read_timeout' => 25.0,
                'proxy' => [
                    'http' => env('FC_HTTP_PROXY'),
                    'https' => env('FC_HTTP_PROXY'),
                ],
                'verify' => true == env('FC_HTTP_VERIFY', true),
            ]
        ]
    ],

    'handlers' => [
        'pre_freeze' => WaitTaskCompleteAction::class,
        'pre_stop' => WaitTaskCompleteAction::class,
    ],

    'storagePath' => env('FC_STORAGE_PATH'),
];
