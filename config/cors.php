<?php

// FRONTEND_URL supports a comma-separated list, e.g.
// FRONTEND_URL=https://your-app.vercel.app,http://localhost:5500
$origins = array_filter(array_map('trim', explode(',', (string) env('FRONTEND_URL', ''))));

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // If FRONTEND_URL is not set, fall back to allowing any origin (useful
    // while you're still wiring things up) — set FRONTEND_URL in production.
    'allowed_origins' => $origins ?: ['*'],

    'allowed_origins_patterns' => [
        // Allow any Vercel preview-deployment subdomain automatically.
        '#^https://.*\.vercel\.app$#',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
