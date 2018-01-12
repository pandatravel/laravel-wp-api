<?php

return [

    'base_url' => env('WP_BASE_URL'),

    'auth' => [
        'driver' => env('WP_API_DRIVER', 'basic_auth'),
        'user'  => env('WP_USER'),
        'password'  => env('WP_PASSWORD'),
    ],

];
