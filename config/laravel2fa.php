<?php

return [
    'enabled' => env('LARAVEL2FA_ENABLED', true),

    'yubikey' => [
        'enabled'    => env('LARAVEL2FA_YUBIKEY_ENABLED', true),
        'api_server' => 'api.yubico.com',
        'client_id'  => env('LARAVEL2FA_YUBIKEY_ID'),
        'secret_key' => env('LARAVEL2FA_YUBIKEY_SECRET')
    ],

    'google' => [
        'enabled' => env('LARAVEL2FA_GOOGLE_ENABLED', true)
    ],

    'remember_cookie' => [
        'enabled'  => true,
        'name'     => '2fa-remember',
        'lifetime' => 43200
    ],

    'default_redirect' => '/dashboard',

    'register_routes'     => true,
    'register_middleware' => true,
    'routes_prefix'       => '/2fa'
];