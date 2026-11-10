<?php

return [
    'mode' => env('PAYPAL_MODE'),
    'client_id' => env('PAYPAL_CLIENT_ID'),
    'client_secret' => env('PAYPAL_CLIENT_SECRET'),
    'base_url' => env('PAYPAL_MODE') === 'sandbox' ? 'https://api-m.sandbox.paypal.com' : 'https://api-m.paypal.com',
    'currency' => env('PAYPAL_CURRENCY', 'USD'),
    'usd_to_khr_rate' => env('PAYPAL_KHR_TO_USD_RATE', 4073),
];