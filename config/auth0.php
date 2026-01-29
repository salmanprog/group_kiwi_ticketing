<?php

use Auth0\Laravel\Configuration;
use Auth0\SDK\Configuration\SdkConfiguration;

return Configuration::VERSION_2 + [
    'registerGuards' => true,
    'registerMiddleware' => true,
    'registerAuthenticationRoutes' => true,

    'guards' => [
        'web' => [
        'strategy' => 'webapp', // <- important
        'domain' => env('AUTH0_DOMAIN'),
        'client_id' => env('AUTH0_CLIENT_ID'),
        'client_secret' => env('AUTH0_CLIENT_SECRET'),
        'redirect_uri' => env('AUTH0_REDIRECT_URI'),
        'cookie_secret' => substr(base64_decode(str_replace('base64:', '', env('APP_KEY'))), 0, 32),
        'scope' => explode(' ', env('AUTH0_SCOPE', 'openid profile email')),
    ],
    ],

    'routes' => [
        Configuration::CONFIG_ROUTE_CALLBACK => '/portal/callback',
        Configuration::CONFIG_ROUTE_LOGIN => '/portal/login',
        Configuration::CONFIG_ROUTE_LOGOUT => '/portal/logout',
        Configuration::CONFIG_ROUTE_AFTER_LOGIN => '/portal/dashboard',
        Configuration::CONFIG_ROUTE_AFTER_LOGOUT => '/portal/login',
    ],
];
