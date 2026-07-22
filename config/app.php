<?php declare(strict_types=1);
return [
    'name' => 'YouTube Broadcaster', 'env' => env('APP_ENV', 'production'),
    'debug' => filter_var(env('APP_DEBUG', 'false'), FILTER_VALIDATE_BOOL),
    'url' => env('APP_URL', 'http://localhost'), 'key' => env('APP_KEY', ''),
    'timezone' => 'UTC', 'session_name' => 'broadcaster_session',
];
