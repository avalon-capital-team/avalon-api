<?php

namespace App\Http\Controllers\Api\Graphic;

use App\Http\Controllers\Controller;
use App\Http\Resources\Credit\CreditBalanceResource;
use App\Http\Resources\Plan\PlanResource;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GraphicDataController extends Controller
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
     * @param  \App\Http\Resources\User\UserPlanResource $resource
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGraphic(Request $request)
    {
        $date_from = date('Y-m-d', strtotime('-6 months'));
        $date_to = date('Y-m-d');

        try {
            $filters = [
                'date_from' => $date_from,
                'date_to' => $date_to
            ];

            return response()->json([
                'status' => true,
                // 'plan' => (new PlanResource())->checkIfNeedPayToday(),
                'data' => (new CreditBalanceResource())->getGraphicData(auth()->user(), 1, $filters),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?? 400);
        }
    }
}
