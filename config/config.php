<?php

return [

    'base_url' => env('WP_BASE_URL'),

    'auth' => [
        'driver' => env('WP_AUTH_DRIVER', 'basic'),
        'user'  => env('WP_USER'),
        'password'  => env('WP_PASSWORD'),
    ],

    'serialize_cookie' => false,

    'auth_form' => 'settings.wpapi.login',

    'login_redirect' => 'settings/wpapi/auth',

    'route_prefix' => 'settings',

    'guzzle_options' => [
        'verify' => true,
    ],

];
