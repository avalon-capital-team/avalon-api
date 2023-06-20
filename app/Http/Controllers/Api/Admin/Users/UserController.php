<?php

namespace App\Http\Controllers\Api\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
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
public function users()
{
  try {
    return response()->json([
      'status'  => true,
      'users' => (new User())->allData(),

    ]);
  } catch (\Exception $e) {
    return response()->json([
      'status' => false,
      'message'  => $e->getMessage()
    ], 400);
  }
}
}

