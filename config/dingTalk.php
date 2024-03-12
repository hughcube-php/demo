<?php

return [
    /**
     * default config
     */
    'defaults' => [],

    'robots' => [
        'default' => [
            'enabled' => env('DING_TALK_ROBOT_ENABLED', true),
            'access_token' => env('DING_TALK_ROBOT_ACCESS_TOKEN', ''),
            'secret' => env('DING_TALK_ROBOT_SECRET', ''),
        ]
    ]
];
