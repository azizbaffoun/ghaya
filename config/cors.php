<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    'allowed_origins' => [
        'http://localhost:3000',
        'http://localhost:3001',
        'http://localhost:5173', // React dev server
        'http://localhost:8080', // React dev server (alternative port)
        'http://localhost:8000', // Laravel server
        'http://127.0.0.1:3000',
        'http://127.0.0.1:3001',
        'http://127.0.0.1:5173', // React dev server
        'http://127.0.0.1:8080', // React dev server (alternative port)
        'http://127.0.0.1:8000', // Laravel server
        env('FRONTEND_URL', 'http://localhost:5173'),
        // Production frontend URLs
        'https://yakinmode.tn',
        'https://www.yakinmode.tn',
    ],

    'allowed_origins_patterns' => [
        // Add patterns for dynamic subdomains if needed
        // '/^https:\/\/.*\.yourdomain\.com$/',
    ],

    'allowed_headers' => [
        'Accept',
        'Authorization',
        'Content-Type',
        'X-Requested-With',
        'X-CSRF-TOKEN',
        'X-XSRF-TOKEN',
    ],

    'exposed_headers' => [
        'X-Total-Count',
        'X-Page-Count',
        'X-Current-Page',
        'X-Per-Page',
    ],

    'max_age' => 86400, // 24 hours

    'supports_credentials' => true,

];
