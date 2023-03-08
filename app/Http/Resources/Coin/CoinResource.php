<?php

namespace App\Http\Resources\Coin;

use App\Models\Coin\Coin;
use App\Models\Coin\CoinNetworkContractType;

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
}
