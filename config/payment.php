<?php

return [
    'default' => 'offline',

    'gateways' => [
        'offline' => [
            'driver' => 'Offline',
            'handler' => Domain\Payments\Handlers\OfflinePaymentHandler::class,
            'instructions' => null,
        ],
        'easypay' => [
            'driver' => 'EasyPay',
            'handler' => Domain\Payments\Handlers\EasyPayPaymentHandler::class,
            'account_id' => env('EASYPAY_ACCOUNT_ID'),
            'api_key' => env('EASYPAY_API_KEY'),
            'webhook_secret' => env('EASYPAY_WEBHOOK_SECRET'),
            'sandbox' => env('EASYPAY_SANDBOX', true),
        ],
    ],

];
