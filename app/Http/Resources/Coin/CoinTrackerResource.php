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
        $i = rand(1, 2);
        $u = rand(1, 3) / 100;
        $p = rand(1, 3) / 100;
        $porcent_sale = $p / 100;
        $porcent_buy = $u / 100;

        foreach ($trackers['data'] as $track) {
            if ($track['currency']['symbol'] == 'BTC') {
                $exchange = $track['currency'];

                $variable_buy = $track['currency']['price_usd'] * $porcent_buy;
                $variable_sale = $track['currency']['price_usd'] * $porcent_sale;

                if ($i == 1) {
                    $exchange['price_buy'] = $track['currency']['price_usd'] + $variable_buy;
                    $exchange['price_sale'] = $track['currency']['price_usd'] - $variable_sale;
                } else {
                    $exchange['prace_buy'] = $track['currency']['price_usd'] - $variable_buy;
                    $exchange['prace_sale'] = $track['currency']['price_usd'] + $variable_sale;
                }
            }
        }

        return $exchange;
    }
}
