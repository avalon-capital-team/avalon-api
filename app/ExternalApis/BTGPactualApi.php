<?php

namespace App\ExternalApis;

use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BTGPactualApi
{
    /**
     * Create a new ExternalApis instance.
     *
     * @return void
     */
    public function __construct()
    {
        $config = config('external_apis.btg_pactual_api');
        $this->base_url_id = $config['base_url_id'];
        $this->base_url = $config['base_url'];
        $this->company_id = $config['company_id'];
        $this->client_id = $config['client_id'];
        $this->secret = $config['secret'];
        $this->basic = base64_encode($this->client_id  . ":" . $this->secret);
        $this->cache_access_token = 86400;
        $this->refresh_token = $config['refresh_token'];
        $this->pix_key = $config['pix_key'];
    }

    /**
     * Get access token
     *
     * @return string|bool
     */
    public function getAccessToken(): string
    {
        $config = [
            'base_url_id' => $this->base_url_id,
            'refresh_token' => $this->refresh_token,
            'client_id' => $this->client_id,
            'basic' => $this->basic,
        ];

        return Cache::remember('btg_pactual_access_token', $this->cache_access_token, function () use ($config) {
            $response = Http::asForm()
                ->withHeaders([
                    'Accept' => 'application/x-www-form-urlencoded',
                    'Authorization' => "Basic {$config['basic']}",
                ])
                ->post("{$config['base_url_id']}/oauth2/token", [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $config['refresh_token'],
                    'client_id' => $config['client_id'],
                ]);

            if ($response->failed()) {
                Log::critical('Failed in ' . self::class, [
                    'code' => 'Unexpected error in ' . self::class,
                    'exception' => $response->json(),
                    'external_api' => 'BTGPactualApi_getAccessToken',
                ]);

                return null;
            }

            return $response->json()['access_token'];
        });
    }

    /**
     * @param  float $amount
     * @param  array $payer array('name' => string, 'txId' => string)
     * @param  string $internalId
     * @return array
     * @throws Exception
     */
    public function generatePix(float $amount, array $payer, string $internalId): array
    {
        return $this->cashInPix([
            'amount' => $amount,
            'payer' => [
                'name' => $payer['name'],
                'taxId' => $payer['taxId']
            ],
            'description' => 'Cobrança PIX',
            'internal_id' => $internalId
        ]);
    }

    /**
     * @param  array $payer array('amount' => float, 'payer' => array, 'internal_id' => string)
     * @return array
     *
     * @throws Exception
     */
    private function cashInPix(array $data): array
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->getAccessToken()
        ])->post("{$this->base_url}/companies/{$this->company_id}/pix-cash-in/instant-collections", [
            'pixKey' => $this->pix_key,
            'amount' => [
                'original' => $data['amount'],
                'allowCustomerChangeValue' => false,
            ],
            'displayText' => $data['description'],
            'payer' => [
                'name' => $data['payer']['name'],
                'taxId' => $data['payer']['taxId']
            ],
            'tags' => [
                'internalId' => $data['internal_id']
            ]
        ]);

        if ($response->failed()) {
            Log::critical('Failed in ' . self::class, [
                'code' => 'Unexpected error in ' . self::class,
                'exception' => $response->json(),
                'external_api' => 'BTGPactualApi_cashInPix',
            ]);

            if (isset($response->json()['errors'])) {
                throw new Exception($this->formatErrorCode($response->json()['errors'][0]['codeError']));
            }

            throw new Exception("Não foi possível gerar o PIX para pagamento.");
        }

        return $response->json();
    }

    /**
     * @param  string $locationId
     * @return array
     *
     * @throws Exception
     */
    public function checkCashInPix(string $locationId): array
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->getAccessToken()
        ])->get("{$this->base_url}/companies/{$this->company_id}/pix-cash-in/instant-collections", [
            'locationId' => $locationId,
        ]);

        if ($response->failed()) {
            Log::critical('Failed in ' . self::class, [
                'code' => 'Cash in Pix (' . $locationId . ') not found',
                'exception' => $response->json(),
                'external_api' => 'BTGPactualApi_checkCashInPix',
            ]);

            throw new Exception('Não foi possível encontrar a cobrança PIX.');
        }

        return $response->json();
    }

    /**
     * @param  string $errorCode
     * @return string
     */
    private function formatErrorCode(string $errorCode): string
    {
        switch ($errorCode) {
            case 'invalidTaxId':
                return __("O CPF ou CNPJ informado é inválido.");
            default:
                return __("Não foi possível gerar o PIX para pagamento.");
        }
    }
}
