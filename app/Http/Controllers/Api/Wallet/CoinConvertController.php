<?php

namespace App\Http\Controllers\Api\Wallet;

use App\Http\Controllers\Controller;
use App\Http\Resources\Coin\CoinResource;
use App\Http\Resources\Credit\CreditBalanceResource;
use App\Models\Coin\Coin;
use App\Nova\Models\Coin\Coin as CoinCoin;
use Illuminate\Http\Request;

class CoinConvertController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function balance(Request $request)
    {
        try {
            $data = (new CreditBalanceResource())->getBalances(auth()->user());

            if (count($data) != 0) {
                foreach ($data as $balance) {
                    $balance['coin_id'] = (new CoinResource())->findById($balance['coin_id']);
                    $balance['balance_enable_convert'] = number_format((new CoinResource())->calculatePriceFiat($balance['balance_enable'], $balance['coin_id']['price_brl']), 2);
                }
            }

            return response()->json([
                'status' => true,
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?? 400);
        }
    }

    /**
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function convertCoin(Request $request)
    {
        try {
            $coin_from = Coin::where('id', $request->coin_id_from)->first();
            $coin_to = Coin::where('id', $request->coin_id_to)->first();

            return response()->json([
                'status' => true,
                'data' => (new CoinResource())->convertCoin(auth()->user(), $coin_from, $coin_to, $request->value)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?? 400);
        }
    }
}
