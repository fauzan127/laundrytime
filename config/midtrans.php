<?php

return [
    'merchantId' => env('MIDTRANS_MERCHANT_ID', ''),
    'clientKey' => env('MIDTRANS_CLIENT_KEY', ''),
    'serverKey' => env('MIDTRANS_SERVER_KEY', ''),
    'isProduction' => env('MIDTRANS_IS_PRODUCTION', true),
    'isSanitized' => true,
    'is3ds' => true,
];
