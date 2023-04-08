<?php

namespace App\Http\Resources\Coin;

use App\Models\Coin\Coin;
use App\ExternalApis\CoinMarketCapApi;
use App\ExternalApis\CoinUsdBrl;

class CoinResource
{
    /**
     * Find By Id
     *
     * @param string $id
     * @return \App\Models\Coin\Coin
     */
    public function findById(string $id)
    {
        return Coin::where('id', $id)->first();
    }

    /**
     * Get All Coins
     *
     * @return \App\Models\Coin\Coin
     */
    public function getCoins()
    {
        return Coin::where('show_wallet', 1)->get();
    }

    /**
     * Find By Symbol
     *
     * @param string $symbol
     * @return \App\Models\Coin\Coin
     */
    public function findBySymbol(string $symbol)
    {
        return Coin::where('symbol', $symbol)->first();
    }

    /**
     * @return array
     */
    public function coinData()
    {
        $coinMarketCapApi = new CoinMarketCapApi();
        $coins = $coinMarketCapApi->listAllCoins();

        return $this->saveCoin($coins['data']);
    }

    /**
     * @param  array $exchange
     * @return array
     */
    public function saveCoin(array $coins)
    {
        $real = Coin::where('symbol', 'BRL')->first();
        $coinUsdBrlApi = new CoinUsdBrl();
        $price_usd_brl = $coinUsdBrlApi->listUsdBrl();
        $real['price_usd'] = $price_usd_brl['USDBRL']['high'];
        $real->save();

        foreach ($coins as $coin) {

            $updata = Coin::where('symbol', $coin['symbol'])->first();
            if (!$updata) {
                $updata = Coin::create([
                    'name' => $coin['name'],
                    'symbol' => $coin['symbol'],
                    'type' => 'coin',
                    'chain_api' => $coin['slug'],
                    'price_usd' => floatval($coin['quote']['USD']['price']),
                    'price_brl' => $this->calculatePriceBrl($coin['quote']['USD']['price'], $real->price_usd),
                    'volume_24h' => floatval($coin['quote']['USD']['volume_24h']),
                    'volume_change_24h' => floatval($coin['quote']['USD']['volume_change_24h']),
                    'percent_change_24h' => floatval($coin['quote']['USD']['percent_change_24h']),
                ]);
            } else {
                $updata->price_usd = floatval($coin['quote']['USD']['price']);
                $updata->price_brl = $this->calculatePriceBrl($coin['quote']['USD']['price'], $real->price_usd);
                $updata->volume_24h = floatval($coin['quote']['USD']['volume_24h']);
                $updata->volume_change_24h = floatval($coin['quote']['USD']['volume_change_24h']);
                $updata->percent_change_24h = floatval($coin['quote']['USD']['percent_change_24h']);
                $updata->save();
            }
        }
        return true;
    }

    /**
     * @return array
     */
    public function calculatePriceBrl($price_coin, $price_fiat)
    {
        return $price_fiat * $price_coin;
    }
}
