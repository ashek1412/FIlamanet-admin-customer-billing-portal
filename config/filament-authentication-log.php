<?php

return [

    'resources' => [
        'AutenticationLogResource' => \Tapp\FilamentAuthenticationLog\Resources\AuthenticationLogResource::class,
    ],

    'authenticable-resources' => [
        \App\Models\User::class,
    ],

    'navigation' => [
        'authentication-log' => [
            'register' => true,

            'sort' => 1,
            'icon' => 'heroicon-o-shield-check',
        ],
    ],

    'sort' => [
        'column' => 'login_at',
        'direction' => 'desc',
    ],

    'authenticatable' => [
        'field-to-display' => 'username', // Change 'name' to your custom field if needed
    ],

];
