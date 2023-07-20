<?php

namespace App\Http\Controllers\Api\Admin\Plans;

use App\Http\Controllers\Controller;
use App\Models\Plan\Plan;
use Illuminate\Http\Request;

class PlansController extends Controller
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
  public function pendingPlans()
  {
    try {
      return response()->json([
        'status'  => true,
        'pending_plans' => Plan::where('acting', 0 )->get(),
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message'  => $e->getMessage()
      ], 400);
    }
  }
}
