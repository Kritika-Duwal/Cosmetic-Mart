<?php
// Copy this file to includes/config.php and set your real values.
return [
    // eSewa settings
    'esewa' => [
        // Merchant code (scd)
        'merchant_code' => 'YOUR_MERCHANT_CODE',
        // Merchant secret used to sign v2 payloads (keep private)
        'merchant_secret' => 'YOUR_MERCHANT_SECRET',
        // Environment: 'sandbox' or 'production'
        'environment' => 'sandbox',
    ],
    // Database settings (optional example)
    // 'db' => [ 'host' => 'localhost', 'user' => 'root', 'pass' => '', 'name' => 'dbname' ]
];
