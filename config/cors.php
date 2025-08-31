<?php

return [
    'paths' => ['api/*', 'login', 'logout', 'register', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    // VERY IMPORTANT: This MUST match your frontend's exact URL
    'allowed_origins' => [
        'http://localhost:5173',    // Keep if your frontend runs on localhost
        'http://127.0.0.1:8000',    // Add this for 127.0.0.1:8000
        'http://localhost:8000',    // Add this for localhost:8000
    ],
    'allowed_origins_patterns' => [], // Keep empty unless you need regex patterns
    'allowed_headers' => ['*'],       // Allow all headers
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true, // THIS MUST BE 'true' for cookies to be sent and received cross-origin
];