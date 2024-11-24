<?php

return [
    'scheduled_command' => [
        'enabled' => env('ELOQUENT_LIFETIME_SCHEDULED_COMMAND_ENABLED', false),
        'schedule' => env('ELOQUENT_LIFETIME_SCHEDULE', 'daily'),
    ],

    'models' => [
        'folder' => env('ELOQUENT_LIFETIME_MODELS_FOLDER', app_path('Models')),
    ],
];
