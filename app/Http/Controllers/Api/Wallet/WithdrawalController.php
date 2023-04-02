<?php

namespace App\Http\Controllers\Api\Wallet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\WithdrawalFiatRequest;
use App\Http\Resources\Withdrawal\WithdrawalFiatResource;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
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
    public function withdrawl(WithdrawalFiatResource $resource, WithdrawalFiatRequest $request)
    {
        try {
            $validated = $request->validated();

            $resource->createWithdrawal(auth()->user(), $validated['coin_id'], $validated['type'], $validated['amount']);

            return response()->json([
                'status' => true,
                'message' => 'A solicitação de saque foi realizada com sucesso.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?? 400);
        }
    }
}
