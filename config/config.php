<?php

return [

    'base_url' => env('WP_BASE_URL', 'https://www.pandaonline.com'),

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
        'verify' => env('WP_API_VERIFY', true),
    ],

    'wp_parent_category' => env('WP_PARENT_CATEGORY'),

];
