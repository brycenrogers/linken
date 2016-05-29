<?php

use App\Models\User;

return [
    'model' => User::class,
    'table' => 'oauth_identities',
    'providers' => [
        'github' => [
            'client_id' => env('GITHUB_OAUTH_CLIENT_ID'),
            'client_secret' => env('GITHUB_OAUTH_CLIENT_SECRET'),
            'redirect_uri' => env('GITHUB_OAUTH_CALLBACK_URL'),
            'scope' => [],
        ],
        'google' => [
            'client_id' => env('GOOGLE_OAUTH_CLIENT_ID'),
            'client_secret' => env('GOOGLE_OAUTH_CLIENT_SECRET'),
            'redirect_uri' => env('GOOGLE_OAUTH_CALLBACK_URL'),
            'scope' => [],
        ],
    ],
];
