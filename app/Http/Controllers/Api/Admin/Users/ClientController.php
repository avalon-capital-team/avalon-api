<?php

namespace App\Http\Controllers\Api\Admin\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;

class ClientController extends Controller
{
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
}
