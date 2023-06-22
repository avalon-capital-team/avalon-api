<?php

namespace App\Http\Controllers\Api\Admin\Withdral;

use App\Http\Controllers\Controller;
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
   * @param \App\Htt\Resorces\User\User @resource
   * @return \Illuminate\Http\JsonResponse
   */
  public function data()
  {
    try {
      return response()->json([
        'status'  => true,
        'withdrals' => WithdrawalFiat::get(),
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message'  => $e->getMessage()
      ], 400);
    }
  }
}
