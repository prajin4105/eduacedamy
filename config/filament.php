<?php

return [
    'default_filesystem_disk' => env('FILAMENT_FILESYSTEM_DISK', 'public'),
    'default_avatar_provider' => \Filament\AvatarProviders\UiAvatarsProvider::class,
    'middleware' => [
        'auth',
        'web',
    ],

    'panels' => [
        [
            'id' => 'admin',         // unique panel ID
            'path' => 'admin',
            'provider' => 'users',
            'auth_guard' => 'web',
            'default' => true,       // ← mark this panel as default
        ],
        [
            'id' => 'instructor',
            'path' => 'instructor',
            'provider' => 'users',
            'auth_guard' => 'web',
        ],
    ],
];
