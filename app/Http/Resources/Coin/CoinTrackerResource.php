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

    function rand_float($st_num = 0, $end_num = 1, $mul = 1000000)
    {
        if ($st_num > $end_num) return false;
        return mt_rand($st_num * $mul, $end_num * $mul) / $mul;
    }

    /**
     * @param  array $trackers
     * @return array
     */
    public function currencyTreatment($trackers)
    {
        $buy = $this->rand_float(0.1, 0.5) / 100;
        $porcent_buy = $buy / 100;

        $sale = $this->rand_float(0.1, 0.5) / 100;
        $porcent_sale = $sale / 100;

        foreach ($trackers['data'] as $track) {
            if ($track['currency']['symbol'] == 'BTC') {
                $exchange = $track['currency'];

                $variable_buy = $track['currency']['price_usd'] * $porcent_buy;
                $variable_sale = $track['currency']['price_usd'] * $porcent_sale;

                $exchange['price_buy'] = number_format((float)$track['currency']['price_usd'] - $variable_buy, 2, '.', '');
                $exchange['price_sale'] = number_format((float)$track['currency']['price_usd'] + $variable_sale, 2, '.', '');
            }
        }

        return $exchange;
    }
}
