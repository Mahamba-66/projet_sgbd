<?php

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

    // Code pour l'inscription des administrateurs
    'admin_registration_code' => '123456',
    
    // Codes spécifiques pour chaque rôle
    'role_codes' => [
        'super_admin' => 'SUPER2024',
        'admin' => 'ADMIN2024',
        'supervisor' => 'SUPER2024'
    ],
];
