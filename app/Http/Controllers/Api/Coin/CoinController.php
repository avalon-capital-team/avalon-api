<?php

namespace App\Http\Controllers\Api\Coin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Coin\CoinResource;


class CoinController extends Controller
{
    /**
     * Get coins in coinmarketcap
     * @param  \Illuminate\Http\Request $request
     * @return \Illiminate\Http\Json
     */
    public function coinListingsLatest()
    {
        try {
            $coins = (new CoinResource())->coinData();

            return response()->json([
                'status'  => true,
                'coins' => $coins
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?? 400);
        }
    }
}
