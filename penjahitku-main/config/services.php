<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | File ini menyimpan kredensial untuk layanan pihak ketiga seperti
    | Mailgun, Postmark, AWS, Google, Xendit, dan lainnya.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // === KONFIGURASI GOOGLE LOGIN ===
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'), // Ambil dari .env
        'client_secret' => env('GOOGLE_CLIENT_SECRET'), // Ambil dari .env
        'redirect' => env('GOOGLE_REDIRECT_URI'), // Ambil dari .env
    ],

    // === KONFIGURASI XENDIT ===
    'xendit' => [
        'secret_key' => env('XENDIT_SECRET_KEY'),
    ],

    // === KONFIGURASI LOYALTY APP ===
    'loyalty' => [
        'base_url' => env('LOYALTY_APP_URL'),
    ],

];
