<?php

$defaultVersion = 'v1';

return [
    /*
    |--------------------------------------------------------------------------
    | PayPal API Credentials
    |--------------------------------------------------------------------------
    |
    | Here are the core credentials needed to work with PayPal's API.
    | You can get these credentials from your PayPal Developer account.
    |
    */

    // PayPal API mode - can be 'sandbox' or 'live'
    'base_url' => env('LARA_PAYPAL_MODE') === 'sandbox' ? 'https://api-m.sandbox.paypal.com/' . $defaultVersion : 'https://api-m.paypal.com/' . $defaultVersion,
    // Your PayPal client ID from the developer dashboard
    'client_id' => env('LARA_PAYPAL_CLIENT_ID'),

    // Your PayPal secret key from the developer dashboard
    'secret' => env('LARA_PAYPAL_SECRET'),

    'subscription_cancel_callback' => env('LARA_PAYPAL_SUBSCRIPTION_CANCEL_CALLBACK', config('app.url')),
    'subscription_return_callback' => env('LARA_PAYPAL_SUBSCRIPTION_RETURN_CALLBACK', config('app.url')),
];
