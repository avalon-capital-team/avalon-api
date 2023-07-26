<?php

namespace App\Http\Controllers\Api\Admin\Models;

use App\Http\Controllers\Controller;
use App\Http\Resources\Data\DataPlanResource;
use App\Models\Coin\Coin;
use App\Models\Data\DataPercent;
use App\Models\Data\DataPlan;
use Illuminate\Http\Request;

class ModelsController extends Controller
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
  public function dataPlan()
  {
    try {
      return response()->json([
        'status'  => true,
        'plans' => DataPlan::get(),
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
  public function updatePlan(Request $request)
  {
    try {
      return response()->json([
        'status'  => true,
        'plans' => (new DataPlanResource())->updatePlan($request->plan_id, $request->data),
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
  public function dataPorcents()
  {
    try {
      return response()->json([
        'status'  => true,
        'percent' => DataPercent::get(),
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
  public function updataPorcent(Request $request)
  {
    $request->validate([
      'name' => 'sometimes',
      'rescue' => 'sometimes',
      'porcent' => 'sometimes',
      'type' => 'sometimes',
    ]);

    try {
      $plan = DataPlan::find($request->id);
      if ($plan) {
        $plan->update($request->only(['name', 'rescue', 'porcent', 'type']));
        return response()->json(['message' => 'DataPlan updated successfully!'], 200);
      } else {
        return response()->json(['message' => 'DataPlan not found.'], 404);
      }
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
  public function dataCoins()
  {
    try {
      return response()->json([
        'status'  => true,
        'coins' => Coin::get(),
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message'  => $e->getMessage()
      ], 400);
    }
  }
}
