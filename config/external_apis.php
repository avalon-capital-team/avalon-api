<?php

return [
    'coin_market_cap_api' => [
        'key' => env('COIN_MARKET_CAP_API_KEY'),
        'endpoint' => env('COIN_MARKET_CAP_API_URL')
    ],
    'tatum_api' => [
        'key' => env('TATUM_API_KEY'),
        'endpoint' => env('TATUM_API_URL')
    ],
    'kycaid_api' => [
        'key' => env('KYCAID_API_KEY'),
        'endpoint' => env('KYCAID_API_URL'),
        'form_id_person' => env('KYCAID_FORM_ID_PERSON'),
        'form_id_company' => env('KYCAID_FORM_ID_COMPANY'),
    ],

    'gerencianet' => [
        'certificate' => env('GERENCIANET_CERTIFICATE'),
        'endpoint' => env('GERENCIANET_API_URL'),
        'client_id' => env('GERENCIANET_CLIENT_ID'),
        'secret' => env('GERENCIANET_SECRET'),
        'pix_key' => env('GERENCIANET_PIX_KEY'),
    ],

    'btg_pactual_api' => [
        'base_url_id' => env('BTG_PACTUAL_BASE_URL_ID'),
        'base_url' => env('BTG_PACTUAL_BASE_URL'),
        'client_id' => env('BTG_PACTUAL_CLIENT_ID'),
        'secret' => env('BTG_PACTUAL_SECRET'),
        'company_id' => env('BTG_PACTUAL_COMPANY_ID'),
        'refresh_token' => env('BTG_PACTUAL_REFRESH_TOKEN'),
        'webhook_key' => env('BTG_PACTUAL_WEBHOOK_KEY'),
        'pix_key' => env('BTG_PACTUAL_PIX_KEY'),
    ],
];
