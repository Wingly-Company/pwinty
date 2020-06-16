<?php

return [
    'model' => env('PWINTY_MODEL', App\User::class),

    'api' => env('PWINTY_API', 'sandbox'),

    'merchantId' => env('PWINTY_MERCHANT', 'MerchantID'),

    'apiKey' => env('PWINTY_APIKEY', 'ApiKey'),
];
