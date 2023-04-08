<?php

namespace App\ExternalApis;

use Illuminate\Support\Facades\Config;

class CoinUsdBrl
{
    /**
     * Create a new ExternalApis instance.
     *
     * @return void
     */
    public function __construct()
    {
        $coinUsdBrlConfig = Config::get('external_apis.coin_usd_brl');
        $this->api_url = $coinUsdBrlConfig['endpoint'];
    }

    /**
     * @return mixed
     */
    public function listUsdBrl()
    {
        $endpoint = "/USD-BRL";
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
