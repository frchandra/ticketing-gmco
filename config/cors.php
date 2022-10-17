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

    'paths' => ['*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['https://gmco-event.com'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['X-Custom-Header', 'Upgrade-Insecure-Requests', 'X-HTTP-Method-Override', 'Content-Type', 'Accept', '*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,

];
