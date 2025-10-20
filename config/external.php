<?php

return [
    'logs' => [
        'base_url' => env('LOGS_BASE_URL', 'http://localhost:8001'),
        'cache_ttl' => env('LOGS_CACHE_TTL', 3600),
    ],
];
