<?php

namespace App\Http\Controllers\Api\Wallet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\WithdrawalFiatRequest;
use App\Http\Resources\Credit\CreditBalanceResource;
use App\Http\Resources\Plan\PlanResource;
use App\Http\Resources\Withdrawal\WithdrawalFiatResource;
use App\Http\Resources\Withdrawal\WithdrawalCryptoResource;
use App\Models\User;
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
  public function withdrawlPlan(Request $request)
  {
    try {
      $user = User::find($request->user_id);
      if ($user) {
        (new WithdrawalFiatResource())->createWithdrawal($user, $request->coin_id, $request->type, $request->amount);
      } else {
        (new WithdrawalFiatResource())->createWithdrawal(auth()->user(), $request->coin_id, $request->type, $request->amount);
      }
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

  /**
   * @param  \Illuminate\Http\Request
   * @return \Illuminate\Http\JsonResponse
   */
  public function withdrawl(WithdrawalFiatRequest $request)
  {
    try {
      $validated = $request->validated();


      if ($validated['coin_id'] != '1') {
        (new WithdrawalCryptoResource())->createWithdrawalCrypto(auth()->user(), $validated['coin_id'], $validated['type'], $validated['amount']);
      } else {
        (new WithdrawalFiatResource())->createWithdrawal(auth()->user(), $validated['coin_id'], $validated['type'], $validated['amount']);
      }
      (new PlanResource())->withdrawalPlan(auth()->user(), $validated['amount']);

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
