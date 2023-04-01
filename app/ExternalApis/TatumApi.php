<?php

namespace App\ExternalApis;

use Illuminate\Support\Facades\Config;

class TatumApi
{
    /**
     * Create a new ExternalApis instance.
     *
     * @return void
     */
    public function __construct()
    {
        $tatumConfig = Config::get('external_apis.tatum_api');
        $this->api_url = $tatumConfig['endpoint'];
        $this->api_key = $tatumConfig['key'];
    }

    /**
     * @param array $body
     * @return mixed
     */
    public function deploySmartContract(array $body)
    {
        $endpoint = "/blockchain/token/deploy";
        return $this->executeCurl('POST', $endpoint, $body);
    }

    /**
     * @param array $body
     * @return mixed
     */
    public function createSubscription(array $body)
    {
        $endpoint = "/subscription";
        return $this->executeCurl('POST', $endpoint, $body);
    }

    /**
     * @return mixed
     */
    public function listAllSubscription()
    {
        $endpoint = "/subscription?pageSize=10&offset=0";
        return $this->executeCurl('GET', $endpoint);
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function cancelSubscription(string $id)
    {
        $endpoint = "/subscription/$id";
        return $this->executeCurl('DELETE', $endpoint);
    }

    /**
     * @param string $currency
     * @return mixed
     */
    public function generateWallet(string $currency)
    {
        $endpoint = "/$currency/wallet";
        return $this->executeCurl('GET', $endpoint);
    }

    /**
     * @param string $currency
     * @param string $address
     * @return mixed
     */
    public function getAccount(string $currency, string $address)
    {
        $endpoint = "/$currency/account/balance/$address";
        return $this->executeCurl('GET', $endpoint);
    }
    /**
     * @param string $currency
     * @param string $address
     * @return mixed
     */
    public function getAccountAddress(string $currency, string $address)
    {
        $endpoint = "/$currency/account/$address";
        return $this->executeCurl('GET', $endpoint);
    }

    /**
     * @param string $currency
     * @param string $address
     * @return mixed
     */
    public function getAddressBalance(string $currency, string $address)
    {
        $endpoint = "/$currency/address/balance/$address";
        return $this->executeCurl('GET', $endpoint);
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function getSubscriptionReport(string $id)
    {
        $endpoint = "/subscription/report/$id";
        return $this->executeCurl('GET', $endpoint);
    }

    /**
     * @param string $currency
     * @param string $xpub
     * @return mixed
     */
    public function generateAddress(string $currency, string $xpub)
    {
        $endpoint = "/$currency/address/$xpub/1";
        return $this->executeCurl('GET', $endpoint);
    }

    /**
     * @param string $currency
     * @param string $mnemonic
     * @return mixed
     */
    public function generatePrivateKey(string $currency, string $mnemonic)
    {
        $endpoint = "/$currency/wallet/priv";
        $body = ['index' => 1, 'mnemonic' => $mnemonic];
        return $this->executeCurl('POST', $endpoint, $body);
    }

    /**
     * @param string $currency
     * @param array|json $body
     * @return mixed
     */
    public function send(string $currency, $body, $body_is_json)
    {
        $endpoint = "/$currency/transaction";
        return $this->executeCurl('POST', $endpoint, $body, $body_is_json);
    }

    /**
     * @param array $body
     * @return mixed
     */
    public function sendToken(array $body)
    {
        $endpoint = "/blockchain/token/transaction";
        return $this->executeCurl('POST', $endpoint, $body);
    }
    /**
     * @param array $body
     * @return mixed
     */
    public function sendTokenTron(array $body)
    {
        $endpoint = "/tron/trc20/transaction";
        return $this->executeCurl('POST', $endpoint, $body);
    }

    /**
     * @param string $chain
     * @param string $contractAddress
     * @param string $address
     * @return mixed
     */
    public function getTokenAccountBalance(string $chain, string $contractAddress, string $address)
    {
        $endpoint = "/blockchain/token/balance/$chain/$contractAddress/$address";
        return $this->executeCurl('GET', $endpoint);
    }

    /**
     * @param string $address
     * @return mixed
     */
    public function getBscAccountBalance(string $address)
    {
        $endpoint = "/BSC/account/balance/$address";
        return $this->executeCurl('GET', $endpoint);
    }

    /**
     * @param string $chain
     * @param string $txid
     * @return mixed
     */
    public function getTransaction(string $chain, string $txid)
    {
        $endpoint = "/$chain/transaction/$txid";
        return $this->executeCurl('GET', $endpoint);
    }

    /**
     * Calculate Fee to Transfer
     *
     * @param  string $coin_symbol
     * @param  string $from_address
     * @param  string $from_private_key
     * @param  string $destination
     * @param  float $amount
     * @return void
     */
    public function calculateFee(string $coin_symbol, string $from_address, string $from_private_key, string $destination, float $amount)
    {
        $endpoint = '/blockchain/estimate';

        $body = [
            "chain" => $coin_symbol,
            "type" => 'TRANSFER',
            'fromAddress' => [
                [
                    'address' => $from_address,
                    'privateKey' => $from_private_key,
                ]
            ],
            'to' => [
                [
                    'address' => $destination,
                    'value' => floatval(bcdiv($amount, 1, 6)),
                ]
            ]
        ];

        return $this->executeCurl('POST', $endpoint, $body);
    }

    /**
     * Calculate Fee to Transfer of ERC20
     *
     * @param  string $chain_symbol
     * @param  string $from_address
     * @param  string $destination
     * @param  float $amount
     * @param  string $contractAddress
     * @return void
     */
    public function calculateFeeTransferErc20(string $chain_symbol, string $from_address, string $destination, float $amount, string $contractAddress)
    {
        $endpoint = '/blockchain/estimate';

        $body = [
            "chain" => $chain_symbol,
            "type" => 'TRANSFER_ERC20',
            "sender" => $from_address,
            "recipient" => $destination,
            "contractAddress" => $contractAddress,
            "amount" => (string) floatval(bcdiv($amount, 1, 6))
        ];

        return $this->executeCurl('POST', $endpoint, $body);
    }

    /**
     * Calculate Fee to deploy ERC20
     *
     * @param  string $chain_symbol
     * @return void
     */
    public function calculateFeeErc20(string $chain_symbol)
    {
        $endpoint = '/blockchain/estimate';

        $body = [
            "chain" => $chain_symbol,
            "type" => 'DEPLOY_ERC20',
        ];

        return $this->executeCurl('POST', $endpoint, $body);
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array|null|json $body
     * @return mixed
     */
    private function executeCurl(string $method, string $endpoint, $body = null, bool $body_is_json = false)
    {
        $arrCurl = [
            CURLOPT_URL => $this->api_url.$endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "x-api-key: ".$this->api_key
            ],
        ];

        if ($body) {
            if ($body_is_json) {
                $arrCurl[CURLOPT_POSTFIELDS] = $body;
            } else {
                $arrCurl[CURLOPT_POSTFIELDS] = json_encode($body);
            }
        }

        $curl = curl_init();
        curl_setopt_array(
            $curl,
            $arrCurl
        );

        $response = curl_exec($curl);

        return json_decode($response, true);
    }
}
