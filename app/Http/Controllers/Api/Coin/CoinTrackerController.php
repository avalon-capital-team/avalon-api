<?php

namespace App\Http\Controllers\Api\Coin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Coin\CoinTrackerResource;

class CoinTrackerController extends Controller
{
    /**
     * Create Order
     * @param  \Illuminate\Http\Request $request
     * @return \Illiminate\Http\Json
     */
    public function coinTracker()
    {
        try {
            // $kraken = (new CoinTrackerResource())->coinTracking('24');
            // $coin_base = (new CoinTrackerResource())->coinTracking('89');
            // $bit_stamp = (new CoinTrackerResource())->coinTracking('70');

            $binance = (new CoinTrackerResource())->coinTracking('270');
            $kucoin = (new CoinTrackerResource())->coinTracking('311');
            $bitfinix = (new CoinTrackerResource())->coinTracking('37');
            $bybit = (new CoinTrackerResource())->coinTracking('521');
            $zero_kx = (new CoinTrackerResource())->coinTracking('294');
            $bitget = (new CoinTrackerResource())->coinTracking('513');

            return response()->json([
                'status'  => true,
                'binance'  => $binance,
                'kucoin'  => $kucoin,
                'bitfinix'  => $bitfinix,
                'bybit'  => $bybit,
                'zerokx'  => $zero_kx,
                'bitget'  => $bitget,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?? 400);
        }
    }
}
