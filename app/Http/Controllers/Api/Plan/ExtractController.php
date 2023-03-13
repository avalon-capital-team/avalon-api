<?php

namespace App\Http\Controllers\Api\Plan;

use App\Http\Controllers\Controller;
use App\Http\Resources\Credit\CreditResource;
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
    public function getExtract()
    {
        $filters = [
            'uuid' => '',
            'coin_id' => '1',
            'date_from' => '',
            'date_to' => '',
            'description' => ''
        ];
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
}
