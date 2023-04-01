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
        $buy = rand(1, 3) / 100;
        $porcent_buy = $buy / 100;

        $sale = rand(1, 3) / 100;
        $porcent_sale = $sale / 100;

        foreach ($trackers['data'] as $track) {
            if ($track['currency']['symbol'] == 'BTC') {
                $exchange = $track['currency'];

                $variable_buy = $track['currency']['price_usd'] * $porcent_buy;
                $variable_sale = $track['currency']['price_usd'] * $porcent_sale;

                $exchange['prace_buy'] = number_format((float)$track['currency']['price_usd'] - $variable_buy, 2, '.', '');
                $exchange['prace_sale'] = number_format((float)$track['currency']['price_usd'] + $variable_sale, 2, '.', '');
            }
        }

        return $exchange;
    }
}
