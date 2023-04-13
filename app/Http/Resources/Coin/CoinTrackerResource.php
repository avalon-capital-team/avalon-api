<?php

namespace App\Http\Resources\Coin;

use App\ExternalApis\CoinMarketCapApi;
use App\Models\Coin\CoinTracker;

class CoinTrackerResource
{

    /**
     * @param  string $exchange
     * @return array
     */
    public function coinTrackingList()
    {
        $binance = $this->coinTracking('270');
        $this->saveCoinTracking($binance['data'], 'Binance');

        $kucoin = $this->coinTracking('311');
        $this->saveCoinTracking($kucoin['data'], 'KuCoin');

        $bitfinix = $this->coinTracking('37');
        $this->saveCoinTracking($bitfinix['data'], 'Bitfinix');

        $bybit = $this->coinTracking('521');
        $this->saveCoinTracking($bybit['data'], 'Bybit');

        $zero_kx = $this->coinTracking('294');
        $this->saveCoinTracking($zero_kx['data'], 'OKX');

        $bitget = $this->coinTracking('513');
        $this->saveCoinTracking($bitget['data'], 'Bitget');

        $bitget = $this->coinTracking('302');
        $this->saveCoinTracking($bitget['data'], 'Gate.io');

        $bitget = $this->coinTracking('102');
        $this->saveCoinTracking($bitget['data'], 'Huobi');

        $bitget = $this->coinTracking('1149');
        $this->saveCoinTracking($bitget['data'], 'Crypto.com');

        $bitget = $this->coinTracking('544');
        $this->saveCoinTracking($bitget['data'], 'Mexc');


        return true;
    }

    /**
     * @param  array $exchange
     * @return array
     */
    public function saveCoinTracking(array $coins, $name)
    {
        $exchange = CoinTracker::where('name', $name)->first();

        $coin_id = 1;
        foreach ($coins as $coin) {
            if ($coin['currency']['symbol'] == 'BTC') {
                if (!$exchange) {
                    $exchange = CoinTracker::create([
                        'coin_id' => $coin_id,
                        'name' => $name,
                        'price_usd' => floatval($coin['currency']['price_usd']),
                    ]);
                } else {
                    $exchange->price_usd = floatval($coin['currency']['price_usd']);
                    $exchange->save();
                }

                return true;
            }
        }
    }

    /**
     * @param  string $exchange
     * @return array
     */
    public function coinTracking(string $exchange)
    {
        $coinMarketCapApi = new CoinMarketCapApi();
        $exchange = $coinMarketCapApi->listAllCoinsTracker($exchange);

        return $exchange;
    }

    /**
     * @param  string $exchange
     * @return array
     */
    public function getExchanges()
    {
        $exchanges = CoinTracker::get();

        return $this->currencyTreatment($exchanges);
    }

    /**
     * @param  array $trackers
     * @return array
     */
    public function currencyTreatment($exchanges)
    {
        foreach ($exchanges as $exchange) {
            $buy = $this->rand_float(0.1, 0.7) / 100;
            $porcent_buy = $buy / 100;

            $sale = $this->rand_float(0.1, 0.7) / 100;
            $porcent_sale = $sale / 100;

            $variable_buy = $exchange['price_usd'] * $porcent_buy;
            $variable_sale = $exchange['price_usd'] * $porcent_sale;

            $exchange['price_buy'] = number_format((float)$exchange['price_usd'] - $variable_buy, 2, '.', '');
            $exchange['price_sale'] = number_format((float)$exchange['price_usd'] + $variable_sale, 2, '.', '');
        }

        return $exchanges;
    }

    /**
     * @return
     */
    function rand_float($st_num = 0, $end_num = 1, $mul = 1000000)
    {
        if ($st_num > $end_num) return false;
        return mt_rand($st_num * $mul, $end_num * $mul) / $mul;
    }
}
