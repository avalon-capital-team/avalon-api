<?php

namespace App\Http\Controllers\Api\Admin\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserDataRequest;
use App\Http\Resources\Plan\PlanResource;
use App\Http\Resources\User\UserComplianceResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Withdrawal\WithdrawalCryptoResource;
use App\Http\Resources\Withdrawal\WithdrawalFiatResource;
use App\Models\User;
use App\Nova\Models\User\User as UserUser;
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
  public function usersPending(UserResource $resource)
  {
    try {
      return response()->json([
        'status'  => true,
        'users' => $resource->getNewUsers(),

      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message'  => $e->getMessage()
      ], 400);
    }
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
        'users' => (new User())->allData(),

      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message'  => $e->getMessage()
      ], 400);
    }
  }

  /**
   * @param \App\Htt\Resorces\User\User @resource
   * @return \Illuminate\Http\JsonResponse
   */
  public function updateUserDelete(Request $request)
  {
    try {
      $user = User::find($request->user_id);

      if ($user) {
        if ($user->plan) {
          foreach ($user->plan as $plan) {
            $plan->delete();
          }
        }
        if ($user->userPlan) {
          $user->userPlan->delete();
        }
        if ($user->creditBalance) {
          foreach ($user->creditBalance as $balance) {
            $balance->delete();
          }
        }
        if ($user->financial) {
          foreach ($user->financial as $financial) {
            $financial->delete();
          }
        }
        if ($user->address) {
          $user->address->delete();
        }
        if ($user->onboarding) {
          $user->onboarding->delete();
        }
        if ($user->compliance) {
          $user->compliance->delete();
        }
        $user->delete();
      } else {
        return response()->json([
          'status'  => false,
          'message' => 'Usuário não encontrado.'
        ]);
      }

      return response()->json([
        'status'  => true,
        'message' => 'Usuário deletado com sucesso.'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message'  => $e->getMessage()
      ], 400);
    }
  }

  /**
   * @param \App\Htt\Resorces\User\User @resource
   * @return \Illuminate\Http\JsonResponse
   */
  public function updateUserType(Request $request)
  {
    try {
      $user = User::find($request->user_id);

      return response()->json([
        'status'  => true,
        'users' => (new UserResource())->updateUserType($user, $request->type),
        'message' => 'Tipo de usuário alterado com sucesso.'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message'  => $e->getMessage()
      ], 400);
    }
  }

  /**
   * @param \App\Htt\Resorces\User\User @resource
   * @return \Illuminate\Http\JsonResponse
   */
  public function updateUserSponsor(Request $request)
  {
    try {
      $user = User::find($request->user_id);
      $sponsor = User::find($request->sponsor_id);

      if ($sponsor->type == 'mananger') {
        $sponsor_name = 'Gestor';
      }
      if ($sponsor->type == 'advisor') {
        $sponsor_name = 'Assessor';
      }

      return response()->json([
        'status'  => true,
        'users' => (new UserResource())->updateUserType($user, $sponsor),
        'message' => $sponsor_name . ' setado com sucesso.'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message'  => $e->getMessage()
      ], 400);
    }
  }

  /**
   * @param \App\Htt\Resorces\User\UserCompliance @resource
   * @return \Illuminate\Http\JsonResponse
   */
  public function updateCompliance(Request $request)
  {
    try {
      $user = User::find($request->user_id);

      if ($request->type == 2) {
        $type = 'aprovado';
      } else {
        $type = 'rejeitado';
      }

      return response()->json([
        'status'  => true,
        'users' => (new UserComplianceResource())->updateUserCompliance($user->compliance, $request->type, $request->message),
        'message' => 'Usuário ' . $type . '.'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message'  => $e->getMessage()
      ], 400);
    }
  }

  /**
   * @param \App\Htt\Resorces\User\User @resource
   * @return \Illuminate\Http\JsonResponse
   */
  public function updateReport(Request $request)
  {
    try {
      return response()->json([
        'status'  => true,
        'users' => (new PlanResource())->withdralReport($request->user_id, $request->value),
        'message' => 'Aportes atualizado com sucesso!'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message'  => $e->getMessage()
      ], 400);
    }
  }

  /**
   * @param \App\Htt\Resorces\User\User @resource
   * @return \Illuminate\Http\JsonResponse
   */
  public function updataPlan(Request $request)
  {
    try {
      $user = User::find($request->user_id);
      return response()->json([
        'status'  => true,
        'users' => (new PlanResource())->updataPlan($user, $request->plan_id),
        'message' => 'Plano atualizado com sucesso!'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message'  => $e->getMessage()
      ], 400);
    }
  }

  /**
   * @param \App\Htt\Resorces\User\User @resource
   * @return \Illuminate\Http\JsonResponse
   */
  public function updateUser(UserResource $resource, UserDataRequest $request)
  {
    try {
      $validated = $request->validated();
      $user = User::find($validated['id']);

      return response()->json([
        'status'  => true,
        'user' => $resource->updateUser($user, $validated),
        'message' => 'Usuário atualizado com sucesso!'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message'  => $e->getMessage()
      ], 400);
    }
  }

  /**
   * @param  \Illuminate\Http\Request
   * @return \Illuminate\Http\JsonResponse
   */
  public function withdrawl(Request $request)
  {
    try {
      $user = User::find($request->user_id);
      if(!$user)return response()->json(['status' => false,'message' => 'Usuário não encontrado'], 200);

      (new WithdrawalFiatResource())->createWithdrawal($user, $request->coin_id, $request->type, $request->amount);

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
