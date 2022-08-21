<?php

return [
    'mercant_id' => env('MERCH_ID'),
    'client_key' => env('CLIENT_KEY_SANDBOX'),
    'server_key' => env('SERVER_KEY_SANDBOX'),

    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => true,
    'is_3ds' => true,
];
