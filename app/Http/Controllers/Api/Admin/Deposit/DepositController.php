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
   * @param \App\Htt\Resorces\User\User @resource
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
}
