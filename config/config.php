<?php

return [

    'base_url' => env('WP_BASE_URL'),

    'auth' => [
        'driver' => env('WP_AUTH_DRIVER', 'basic_auth'),
        'user'  => env('WP_USER'),
        'password'  => env('WP_PASSWORD'),
    ],

    'guzzle_options' => [
        'verify' => true,
    ],

];
