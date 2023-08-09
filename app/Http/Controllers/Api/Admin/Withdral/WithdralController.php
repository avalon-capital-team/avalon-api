<?php

namespace App\Http\Controllers\Api\Admin\Withdral;

use App\Http\Controllers\Controller;
use App\Http\Resources\Withdrawal\WithdrawalFiatResource;
use App\Models\Withdrawal\WithdrawalFiat;
use Illuminate\Http\Request;

class WithdralController extends Controller
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
   * @return \Illuminate\Http\JsonResponse
   */
  public function data()
  {
    try {
      return response()->json([
        'status'  => true,
        'withdrals' => (new WithdrawalFiatResource)->getWithdral(),
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message'  => $e->getMessage()
      ], 400);
    }
  }

  /**
   * @return \Illuminate\Http\JsonResponse
   */
  public function withdralPendings()
  {
    try {
      return response()->json([
        'status'  => true,
        'withdrals_pendings' => (new WithdrawalFiatResource)->getWithdralPendings(),
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message'  => $e->getMessage()
      ], 400);
    }
  }

  /**
   * @return \Illuminate\Http\JsonResponse
   */
  public function withdralPayment(Request $request)
  {
    try {
      $model = WithdrawalFiat::find($request->id);
      if (!$model) return response()->json(['status'  => false, 'message' => 'Solicitação de saque não encontrado']);

      if ($request->type == 1) {
        (new WithdrawalFiatResource())->approveWithdrawal($model, $request->message);
        $message = 'Saque aprovado com sucesso.';
      } else {
        (new WithdrawalFiatResource())->cancelWithdrawal($model, $request->message);
        $message = 'Saque cancelado com sucesso.';
      }
      return response()->json([
        'status'  => true,
        'message' => $message
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message'  => $e->getMessage()
      ], 400);
    }
  }

  /**
   * @return \Illuminate\Http\JsonResponse
   */
  public function deleteWithdral(Request $request)
  {
    try {
      $withdral = WithdrawalFiat::find($request->id);
      if ($withdral) {
        $withdral->delete();
        return response()->json([
          'status'  => true,
          'message' => 'Saque deletado com sucesso'
        ]);
      }
      return response()->json([
        'status'  => false,
        'message' => 'Saque não encontrado'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message'  => $e->getMessage()
      ], 400);
    }
  }
}
