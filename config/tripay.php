<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tripay Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Tripay Payment Gateway integration
    |
    */

    // Environment: sandbox atau production
    'environment' => env('TRIPAY_ENVIRONMENT', 'sandbox'),

    // Sandbox Configuration
    'sandbox' => [
        'api_key' => env('TRIPAY_SANDBOX_API_KEY', ''),
        'private_key' => env('TRIPAY_SANDBOX_PRIVATE_KEY', ''),
        'merchant_code' => env('TRIPAY_SANDBOX_MERCHANT_CODE', ''),
        'base_url' => 'https://tripay.co.id/api-sandbox',
    ],

    // Production Configuration
    'production' => [
        'api_key' => env('TRIPAY_PRODUCTION_API_KEY', ''),
        'private_key' => env('TRIPAY_PRODUCTION_PRIVATE_KEY', ''),
        'merchant_code' => env('TRIPAY_PRODUCTION_MERCHANT_CODE', ''),
        'base_url' => 'https://tripay.co.id/api',
    ],

    // Callback settings
    'callback_url' => env('TRIPAY_CALLBACK_URL', env('APP_URL') . '/api/tripay/callback'),
    'return_url' => env('TRIPAY_RETURN_URL', env('APP_URL') . '/billing/payments/success'),

    // Default settings
    'currency' => 'IDR',
    'fee_customer' => true, // Customer menanggung fee atau tidak
    'expiry_time' => 24, // Jam (24 jam = 1 hari)
];