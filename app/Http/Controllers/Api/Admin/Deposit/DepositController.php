<?php

namespace App\Http\Controllers\Api\Admin\Deposit;

use App\Http\Controllers\Controller;
use App\Http\Resources\Deposit\DepositFiatResource;
use App\Models\Deposit\DepositFiat;
use Illuminate\Http\Request;

class DepositController extends Controller
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
        'Deposits' => (new DepositFiatResource)->getDeposits(),
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
    public function approve(Request $request)
    {
        $deposit = DepositFiat::find($request->id);

        (new DepositFiatResource())->approveDeposit($deposit);
        return response()->json([
            'status'  => true,
            'message' => 'Aprovado'
        ]);
    }

  /**
   * @return \Illuminate\Http\JsonResponse
   */
  public function actionDeposit()
  {
    try {
      return response()->json([
        'status'  => true,
        'Deposits' => (new DepositFiatResource)->getDeposits(),
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
  public function deleteDeposit(Request $request)
  {
    try {
      $deposit = DepositFiat::find($request->id);
      if($deposit){
        $deposit->delete();
        return response()->json([
          'status'  => true,
          'message' => 'Deposito deletado com sucesso'
        ]);
      }
      return response()->json([
        'status'  => false,
        'message' => 'Deposito não encontrado'
      ]);

    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message'  => $e->getMessage()
      ], 400);
    }
  }
}
