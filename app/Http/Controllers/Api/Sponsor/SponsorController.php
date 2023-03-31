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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIndicateList()
    {
        try {
            $list = (new UserResource())->findBySponsorshipId(auth()->user()->id);
            $manangers_count = $list->where('type', 'mananger')->count();
            $users_count = $list->where('type', 'user')->count();

            $manangers = $list->where('type', 'mananger');
            $users = $list->where('type', 'user');

            // dd($list[0]);
            // if ($list[0]) {
            //     dd('aq');
            //     foreach ($list as $user) {
            //         $plan = (new UserPlanResource())->findByUserId($user->id);

            //         $sun = $plan->amount;
            //     }
            //     $total = $sun;
            //     dd($total);
            // }

            return response()->json([
                'status' => true,
                'manangers_count' => $manangers_count,
                'users_count' => $users_count,
                'manangers' => $manangers,
                'users' => $users
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
    public function getIndicatePlan(Request $request)
    {
        try {
            $plan = (new UserPlanResource())->findByUserId($request->user_id);
            if ($plan) {
                $plan->list = (new PlanResource())->listPlans($plan->user_id);
            }

            return response()->json([
                'status' => true,
                'plan' => $plan,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?? 400);
        }
    }
}
