<?php

return [

    'base_url' => env('WP_BASE_URL'),

    'auth' => [
        'driver' => env('WP_AUTH_DRIVER', 'basic'),
        'user'  => env('WP_USER'),
        'password'  => env('WP_PASSWORD'),
    ],

    'auth_form' => 'settings.wp.login',

    'login_redirect' => 'settings',

    'guzzle_options' => [
        'verify' => true,
    ],

];
