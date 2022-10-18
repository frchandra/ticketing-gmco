<?php

return [
    'mercant_id' => env('MERCH_ID'),
    'client_key' => env('CLIENT_KEY'),
    'server_key' => env('SERVER_KEY'),

    'is_production' => env('MIDTRANS_IS_PRODUCTION', true),
    'is_sanitized' => true,
    'is_3ds' => true,
];
