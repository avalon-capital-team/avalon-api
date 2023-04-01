<?php

namespace App\ExternalApis;

use Illuminate\Support\Facades\Cache;

class GerencianetApi
{
    /**
     * Create a new ExternalApis instance.
     *
     * @param bool $needBearer
     * @return void
     */
    public function __construct($needBearer=true)
    {
        $config = config('external_apis.gerencianet');
        $this->api_url = $config['endpoint'];
        $this->pix_key = $config['pix_key'];
        $this->client_id = $config['client_id'];
        $this->secret = $config['secret'];
        $this->certificate = $config['certificate'];
        $this->bearer = ($needBearer) ? Cache::remember('gerencianet_token_oauth', 3600, function () {
            return (new GerencianetApi(false))->authorizeOAuth()['access_token'];
        }) : '';
        $this->basic = base64_encode($this->client_id  . ":" . $this->secret);
    }

    /**
     * Create new pix key
     *
     * @return void
     */
    public function createNewPixKey()
    {
        $endpoint = '/v2/gn/evp';
        return $this->executeCurl('POST', $endpoint, []);
    }

    /**
     * Create Form URL
     *
     * @param  string $document_type
     * @param  string $document_number
     * @param  string $name
     * @param  float $amount
     * @param  string $internalId
     * @return \Illuminate\Http\Client\Response
     */
    public function createCharge(string $document_type, string $document_number, string $name, float $amount, string $internalId)
    {
        $endpoint = "/v2/cob";
        $body =  [
            'calendario' => [
                'expiracao' => 3600
            ],
            'devedor' => [
                strtolower($document_type) => $document_number,
                'nome' => $name,
            ],
            'valor' => [
                'original' => ''.bcdiv($amount, 1, 2),
            ],
            'solicitacaoPagador' => $internalId,
            'chave' => $this->pix_key,
        ];
        return $this->executeCurl('POST', $endpoint, $body);
    }

    /**
     * Get location QR Code
     *
     * @param  string $idLoc
     * @return void
     */
    public function getQRCodeByIdLoc(string $idLoc)
    {
        $endpoint = '/v2/loc/'.$idLoc.'/qrcode';
        return $this->executeCurl('GET', $endpoint, [], false);
    }

    /**
     * Get charge information
     *
     * @param  string $idLoc
     * @return void
     */
    public function getCharge(string $txid)
    {
        $endpoint = '/v2/cob/'.$txid;
        return $this->executeCurl('GET', $endpoint, [], false);
    }

    /**
     * Set webhook to Pix Key
     *
     * @param  string $pix_key
     * @param  string $webhook_endpoint
     * @return void
     */
    public function setWebhook(string $pix_key, string $webhook_endpoint)
    {
        $endpoint = '/v2/webhook/'.$pix_key;
        return $this->executeCurl('PUT', $endpoint, [
            'webhookUrl' => $webhook_endpoint
        ], false);
    }

    /**
     * Send pix
     *
     * @param  mixed $amount
     * @param  mixed $pix_key
     * @param  mixed $internalId
     * @return void
     */
    public function sendPix(float $amount, string $pix_key, string $internalId)
    {
        $endpoint = '/v2/gn/pix/'.$internalId;

        return $this->executeCurl('PUT', $endpoint, [
            'valor' => $amount.'',
            'pagador' => [
                'chave' => $this->pix_key
            ],
            'favorecido' => [
                'chave' => $pix_key
            ]
        ], false);
    }

    /**
     * Authorize auth
     *
     * @return \Illuminate\Http\Client\Response
     */
    public function authorizeOAuth()
    {
        $endpoint = '/oauth/token';
        $body = ['grant_type' => 'client_credentials'];
        return $this->executeCurl('POST', $endpoint, $body, false, 'basic');
    }

    /**
     * Execute Curl
     *
     * @param string $method
     * @param string $endpoint
     * @param array|null|json $body
     * @param boolean $body_is_json
     * @param boolean $body_is_json
     * @return mixed
     */
    private function executeCurl(string $method, string $endpoint, $body = null, bool $body_is_json = false, string $typeAuth = 'bearer')
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url."".$endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_SSLCERT => public_path().'/certificates/gerencianet/'.$this->certificate,
            CURLOPT_SSLCERTPASSWD => "",
            CURLOPT_HTTPHEADER => array(
                "Authorization: ".ucfirst($typeAuth)." ".$this->$typeAuth,
                "Content-Type: application/json",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);

        return json_decode($response, true);
    }
}
