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

    public function currencyTreatment($trackers)
    {
        $trackers = $trackers['data'];

        foreach ($trackers as $track) {
            if ($track['currency']['symbol'] == 'BTC') {
                $exchange = $track['currency'];
            }
        }
        return $exchange;
    }
}
