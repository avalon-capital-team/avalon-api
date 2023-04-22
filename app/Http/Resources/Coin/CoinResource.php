<?php

namespace App\Http\Resources\Coin;

use App\Models\Coin\Coin;
use App\ExternalApis\CoinMarketCapApi;
use App\ExternalApis\CoinUsdBrl;
use App\Http\Resources\Credit\CreditBalanceResource;
use App\Http\Resources\Credit\CreditResource;
use App\Models\Credit\Credit;
use App\Models\Credit\CreditBalance;
use App\Models\User;

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
    public function getPriceUSD()
    {
        $real = Coin::where('symbol', 'BRL')->first();

        $coinUsdBrlApi = new CoinUsdBrl();
        $price_usd_brl = $coinUsdBrlApi->listUsdBrl();

        $real['price_usd'] = $price_usd_brl['USDBRL']['high'];
        $real->save();

        return $real;
    }

    /**
     * @param  array $exchange
     * @return array
     */
    public function saveCoin(array $coins)
    {
        foreach ($coins as $coin) {
            $updata =  $this->findBySymbol($coin['symbol']);
            $real = $this->getPriceUSD();

            if (!$updata) {
                $updata = Coin::create([
                    'name' => $coin['name'],
                    'symbol' => $coin['symbol'],
                    'type' => 'coin',
                    'chain_api' => $coin['slug'],
                    'price_usd' => floatval($coin['quote']['USD']['price']),
                    'price_brl' => $this->calculatePriceFiat($coin['quote']['USD']['price'], $real['price_usd']),
                    'volume_24h' => floatval($coin['quote']['USD']['volume_24h']),
                    'volume_change_24h' => floatval($coin['quote']['USD']['volume_change_24h']),
                    'percent_change_24h' => floatval($coin['quote']['USD']['percent_change_24h']),
                    'show_wallet' => false,
                ]);

                if ($coin['symbol'] == 'BTC') {
                    $updata->show_wallet = true;
                }
                if ($coin['symbol'] == 'USDT') {
                    $updata->show_wallet = true;
                }
                if ($coin['symbol'] == 'FEI') {
                    $updata->show_wallet = false;
                }
                $updata->save();
            } else {
                $updata->price_usd = floatval($coin['quote']['USD']['price']);
                $updata->price_brl = $this->calculatePriceFiat($coin['quote']['USD']['price'], $real['price_usd']);
                $updata->volume_24h = floatval($coin['quote']['USD']['volume_24h']);
                $updata->volume_change_24h = floatval($coin['quote']['USD']['volume_change_24h']);
                $updata->percent_change_24h = floatval($coin['quote']['USD']['percent_change_24h']);
                $updata->show_wallet = false;

                if ($coin['symbol'] == 'BTC') {
                    $updata->show_wallet = true;
                }
                if ($coin['symbol'] == 'USDT') {
                    $updata->show_wallet = true;
                }

                $updata->save();
            }
        }
        return $updata;
    }

    /**
     * @return float
     */
    public function convertCoin(User $user, Coin $from, Coin $to, $value)
    {
        if ($from->id == 1) {
            $description = 'Valor convertido para ' . $to->name . ': ' . $value;
            (new CreditResource())->create(
                $user->id,
                $from->id,
                0,
                6,
                1,
                floatval('-' . $value),
                0.000000,
                $description
            );
            $creditBalanceFrom = CreditBalance::where('user_id', $user->id)->where('coin_id', $from->id)->first();
            (new CreditBalanceResource())->moveBalanceToEnable($creditBalanceFrom, '-' . $value);


            $convert = $this->calculatePriceCoin($value, $to->price_brl);
            $description = 'Converção no valor: R$' . $value . ' para ' . $to->name . ': ' . number_format($convert, 6);
            (new CreditResource())->create(
                $user->id,
                $to->id,
                0,
                6,
                1,
                floatval($convert),
                0.000000,
                $description
            );
            $creditBalanceTo = CreditBalance::where('user_id', $user->id)->where('coin_id', $to->id)->first();
            (new CreditBalanceResource())->moveBalanceToEnable($creditBalanceTo, $convert);
        } else {
            $description = 'Valor convertido para ' . $to->name . ': ' . $value;
            (new CreditResource())->create(
                $user->id,
                $to->id,
                0,
                6,
                1,
                floatval('-' . $value),
                0.000000,
                $description
            );
            $creditBalanceFrom = CreditBalance::where('user_id', $user->id)->where('coin_id', $from->id)->first();
            (new CreditBalanceResource())->moveBalanceToEnable($creditBalanceFrom, '-' . $value);

            $convert = $this->calculatePriceFiat($value, $from->price_brl);
            $description = 'Converção no valor: R$' . $value . ' para ' . $to->name . ': ' . number_format($convert, 2);
            (new CreditResource())->create(
                $user->id,
                $to->id,
                0,
                6,
                1,
                floatval($convert),
                0.000000,
                $description
            );
            $creditBalanceTo = CreditBalance::where('user_id', $user->id)->where('coin_id', $to->id)->first();
            (new CreditBalanceResource())->moveBalanceToEnable($creditBalanceTo, $convert);
        }


        return $convert;
    }

    /**
     * @return float
     */
    public function calculatePriceFiat($price_coin, $price_fiat)
    {
        return $price_fiat * $price_coin;
    }

    /**
     * @return float
     */
    public function calculatePriceCoin($price_fiat, $price_coin)
    {
        return $price_fiat / $price_coin;
    }
}
