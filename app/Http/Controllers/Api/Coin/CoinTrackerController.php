<?php

namespace App\Http\Controllers\Api\Coin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Coin\CoinTrackerResource;
use Illuminate\Http\Request;

class CoinTrackerController extends Controller
{
    /**
     * Create Order
     * @param  \Illuminate\Http\Request $request
     * @return \Illiminate\Http\Json
     */
    public function coinTracker(Request $request)
    {
        try {

            $binance = (new CoinTrackerResource())->coinTracking('270');
            $binance['id'] = 1;
            $binance['exchange'] = 'Binance';

            $kucoin = (new CoinTrackerResource())->coinTracking('311');
            $kucoin['id'] = 2;
            $kucoin['exchange'] = 'Kucoin';

            $bitfinix = (new CoinTrackerResource())->coinTracking('37');
            $bitfinix['id'] = 3;
            $bitfinix['exchange'] = 'Bitfinix';

            $bybit = (new CoinTrackerResource())->coinTracking('521');
            $bybit['id'] = 4;
            $bybit['exchange'] = 'Bybit';

            $zero_kx = (new CoinTrackerResource())->coinTracking('294');
            $zero_kx['id'] = 5;
            $zero_kx['exchange'] = 'Okx';

            $bitget = (new CoinTrackerResource())->coinTracking('513');
            $bitget['id'] = 6;
            $bitget['exchange'] = 'Bitget';

            $tracker = [
                $binance,
                $kucoin,
                $bitfinix,
                $bybit,
                $zero_kx,
                $bitget
            ];

            return response()->json([
                'status'  => true,
                'tracker' => $tracker
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?? 400);
        }
    }
}
