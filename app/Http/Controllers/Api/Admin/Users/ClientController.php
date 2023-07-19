<?php

namespace App\Http\Controllers\Api\Admin\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreatePlanOrderRequest;
use App\Http\Resources\User\UserPlanResource;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
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
  public function data(UserResource $resource)
  {
    try {
      return response()->json([
        'status'  => true,
        'users' => $resource->getClients(),

      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message'  => $e->getMessage()
      ], 400);
    }
  }

  /**
   * Create Order
   * @param \App\Htt\Resorces\User\UserPlanResource @resource
   * @param \App\Htt\Request\User\CreatePlanOrderRequest @request
   * @return \Illiminate\Http\Json
   */
  public function createOrUpdate(UserPlanResource $resource, CreatePlanOrderRequest $request)
  {
    try {
      $user = User::find($request->user_id);
      $plan = $resource->createOrUpdateOrder($user, $request);
      DB::commit();

      return response()->json([
        'status'  => true,
        'message' => 'A orden foi criada com sucesso',
        'plan' => $plan,
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message' => $e->getMessage()
      ], $e->getCode() ?? 400);
    }
  }
}
