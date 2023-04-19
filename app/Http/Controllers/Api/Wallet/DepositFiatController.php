<?php

namespace App\Http\Controllers\Api\Wallet;

use App\Http\Controllers\Controller;
use App\Http\Resources\Deposit\DepositFiatResource;
use Illuminate\Http\Request;

class DepositFiatController extends Controller
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
    public function deposit(Request $request)
    {
        try {
            $data = (new DepositFiatResource())->createDeposit(auth()->user(), $request->amount, $request->payment_code);
            return response()->json([
                'status' => true,
                'message' => 'O indicador realizado com sucesso',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?? 400);
        }
    }
}
