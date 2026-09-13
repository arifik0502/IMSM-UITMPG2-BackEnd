<?php

return [

    'name' => env('APP_NAME', 'Attendance System'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool) env('APP_DEBUG', false),

    // Render automatically injects RENDER_EXTERNAL_URL with the service's
    // public https:// URL, so APP_URL resolves correctly there without you
    // having to hardcode it — override with APP_URL if you need to.
    'url' => env('APP_URL', env('RENDER_EXTERNAL_URL', 'http://localhost')),

    'timezone' => env('APP_TIMEZONE', 'UTC'),

    'locale' => env('APP_LOCALE', 'en'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];
