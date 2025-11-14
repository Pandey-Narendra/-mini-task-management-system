<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS Configuration
    |--------------------------------------------------------------------------
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // Allow all origins OR replace with specific frontend URL later
    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    // Accept Authorization: Bearer <token>
    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];
