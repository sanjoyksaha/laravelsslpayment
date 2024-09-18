<?php

return [
    'store_id' => env('SSLCOMMERZ_STORE_ID'),
    'store_password' => env('SSLCOMMERZ_STORE_PASSWORD'),
    'sandbox' => env('SSLCOMMERZ_SANDBOX', true),
    'apiDomain' => env('SSLCOMMERZ_SANDBOX') ? "https://sandbox.sslcommerz.com" : "https://securepay.sslcommerz.com",
    'apiUrl' => [
        'make_payment' => "/gwprocess/v4/api.php",
        'transaction_status' => "/validator/api/merchantTransIDvalidationAPI.php",
        'order_validate' => "/validator/api/validationserverAPI.php",
        'refund_payment' => "/validator/api/merchantTransIDvalidationAPI.php",
        'refund_status' => "/validator/api/merchantTransIDvalidationAPI.php",
    ],
    'currency' => env('SSLCOMMERZ_CURRENCY', 'BDT'),
    'success_url' => 'payment/success',
    'fail_url' => 'payment/failure',
    'cancel_url' => 'payment/cancel',
    'ipn_url' => 'payment/ipn_listen',
    'product_name' => 'MyArrank.',
];
