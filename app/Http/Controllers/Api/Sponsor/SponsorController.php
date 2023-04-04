<?php

namespace App\Http\Controllers\Api\Sponsor;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserPlanResource;
use App\Http\Resources\Plan\PlanResource;
use Illuminate\Http\Request;

class SponsorController extends Controller
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIndicateList()
    {
        try {
            $list = (new UserResource())->findBySponsorshipId(auth()->user()->id);



            $manangers_count = $list->where('type', 'mananger')->count();
            $users_count = $list->where('type', 'user')->count();
            foreach ($list as $user) {
                if ($user['type'] == 'user') {
                    $user->plan = (new UserPlanResource())->findByUserId($user->id);
                    if ($user->plan) {
                        $user->plan->list = (new PlanResource())->listPlans($user->id);
                    }
                    $user_amount = $user->plan->amount;
                }
                if ($user['type'] == 'mananger') {
                    $user->plan = (new UserPlanResource())->findByUserId($user->id);
                    if ($user->plan) {
                        $user->plan->list = (new PlanResource())->listPlans($user->id);
                    }
                    $manange_amount = $user->plan->amount;
                }
            }

            $total = $manange_amount += $user_amount;

            return response()->json([
                'status' => true,
                'total' => $total,
                'name' => auth()->user()->name,
                'manangers_count' => $manangers_count,
                'users_count' => $users_count,
                'list' => $list
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?? 400);
        }
    }

    /**
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setMananger(Request $request)
    {
        try {
            $user = (new UserResource())->findById($request->user_id);

            if ($user) {
                $user->type = 'mananger';
                $user->save();
            }

            if (!$user) {
                throw new \Exception('Não foi possível adicionar o cliente como gestor. Tente novamente mais tarde!');
            }

            return response()->json([
                'status' => true,
                'message' => 'Este cliente agora é um gestor'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?? 400);
        }
    }
}
