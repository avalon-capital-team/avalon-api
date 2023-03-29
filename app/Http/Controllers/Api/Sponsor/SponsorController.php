<?php

namespace App\Http\Controllers\Api\Sponsor;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserPlanResource;
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
            $manangers_count = $list->where('type', 'manange')->count();
            $users_count = $list->where('type', 'user')->count();

            return response()->json([
                'status' => true,
                'manangers' => $manangers_count,
                'users' => $users_count,
                'list' => $list,
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
