<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */
    'has_matching_path' => true,

    'paths' => ['*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*.x4k.net', '*.x4k.cn', 'localhost', '127.0.0.1'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 365 * 24 * 60 * 60,

    'supports_credentials' => false,

];
