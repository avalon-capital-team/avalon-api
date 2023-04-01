<?php

namespace App\ExternalApis;

use Illuminate\Support\Facades\Config;

class CoinMarketCapApi
{
    /**
     * Create a new ExternalApis instance.
     *
     * @return void
     */
    public function __construct()
    {
        $coinMarketCapConfig = Config::get('external_apis.coin_market_cap_api');
        $this->api_url = $coinMarketCapConfig['endpoint'];
        $this->api_key = $coinMarketCapConfig['key'];
    }

    /**
     * @return mixed
     */
    public function listAllCoinsTracker(string $id)
    {
        $endpoint = "/exchange/assets?id=$id";
        return $this->executeCurl('GET', $endpoint);
    }

    /**
     * @return mixed
     */
    public function listAllCoins()
    {
        $endpoint = "/cryptocurrency/quotes/latest";
        return $this->executeCurl('GET', $endpoint);
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array|null|json $body
     * @return mixed
     */
    private function executeCurl(string $method, string $endpoint)
    {
        $arrCurl = [
            CURLOPT_URL => $this->api_url . $endpoint,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "X-CMC_PRO_API_KEY: " . $this->api_key
            ],
        ];

        $curl = curl_init();
        curl_setopt_array(
            $curl,
            $arrCurl
        );

        $response = curl_exec($curl);

        return json_decode($response, true);
    }
}
