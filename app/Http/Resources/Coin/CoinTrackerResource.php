<?php

namespace App\Http\Resources\Coin;

use App\ExternalApis\CoinMarketCapApi;

class CoinTrackerResource
{
    /**
     * @param  string $exchange
     * @return array
     */
    public function coinTracking(string $exchange)
    {
        $coinMarketCapApi = new CoinMarketCapApi();
        $trackers = $coinMarketCapApi->listAllCoinsTracker($exchange);

        return $this->currencyTreatment($trackers);
    }

    /**
     * @param  array $trackers
     * @return array
     */
    public function currencyTreatment($trackers)
    {
        foreach ($trackers['data'] as $track) {
            if ($track['currency']['symbol'] == 'BTC') {
                $exchange = $track['currency'];
            }
        }

        return $exchange;
    }
}
