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
    public function coinTracker()
    {
        try {
            $tracker = (new CoinTrackerResource())->getExchanges();

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
