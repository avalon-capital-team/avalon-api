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
            $kucoin = (new CoinTrackerResource())->coinTracking('311');
            $bitfinix = (new CoinTrackerResource())->coinTracking('37');
            $bybit = (new CoinTrackerResource())->coinTracking('521');
            $zero_kx = (new CoinTrackerResource())->coinTracking('294');
            $bitget = (new CoinTrackerResource())->coinTracking('513');

            $binance['exchange'] = 'binance';
            $binance['id'] = 1;
            $kucoin['exchange'] = 'kucoin';
            $kucoin['id'] = 2;
            $bitfinix['exchange'] = 'bitfinix';
            $bitfinix['id'] = 3;
            $bybit['exchange'] = 'bybit';
            $bybit['id'] = 4;
            $zero_kx['exchange'] = 'zerokx';
            $zero_kx['id'] = 5;
            $bitget['exchange'] = 'bitget';
            $bitget['id'] = 6;

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
