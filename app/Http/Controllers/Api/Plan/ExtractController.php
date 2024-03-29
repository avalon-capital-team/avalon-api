<?php

namespace App\Http\Controllers\Api\Plan;

use App\Http\Controllers\Controller;
use App\Http\Resources\Credit\CreditResource;
use App\Http\Resources\Credit\CreditBalanceResource;
use Illuminate\Http\Request;

class ExtractController extends Controller
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
    public function getExtract(Request $request)
    {
        if ($request) {
            $filters = [
                'uuid' => $request['uuid'],
                'type_id' => $request['type_id'],
                'coin_id' => $request['coin_id'],
                'date_from' => $request['date_from'],
                'date_to' => $request['date_to'],
                'description' => $request['description']
            ];
        } else {
            $filters = [
                'uuid' => '',
                'type_id' => '',
                'coin_id' => '',
                'date_from' => '',
                'date_to' => '',
                'description' => ''
            ];
        }

        try {
            return response()->json([
                'status' => true,
                'extract' => (new CreditResource())->listExtractPaginate(auth()->user()->id, $filters),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?? 400);
        }
    }

    /**
     * @param  \App\Http\Resources\User\UserPlanResource $resource
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReports()
    {
        try {
            return response()->json([
                'status' => true,
                'data' => (new CreditBalanceResource())->reportData(auth()->user()),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?? 400);
        }
    }
}
