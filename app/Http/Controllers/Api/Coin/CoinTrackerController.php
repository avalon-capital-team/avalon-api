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
            // $kraken = (new CoinTrackerResource())->coinTracking('24');
            // $coin_base = (new CoinTrackerResource())->coinTracking('89');
            // $bit_stamp = (new CoinTrackerResource())->coinTracking('70');

            $binance = (new CoinTrackerResource())->coinTracking('270');
            $kucoin = (new CoinTrackerResource())->coinTracking('311');
            $bitfinix = (new CoinTrackerResource())->coinTracking('37');
            $bybit = (new CoinTrackerResource())->coinTracking('521');
            $zero_kx = (new CoinTrackerResource())->coinTracking('294');
            $bitget = (new CoinTrackerResource())->coinTracking('513');

            $binance['exchange'] = 'binance';
            $kucoin['exchange'] = 'kucoin';
            $bitfinix['exchange'] = 'bitfinix';
            $bybit['exchange'] = 'bybit';
            $zero_kx['exchange'] = 'zerokx';
            $bitget['exchange'] = 'bitget';

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
