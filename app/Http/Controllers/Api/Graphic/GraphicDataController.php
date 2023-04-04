<?php

namespace App\Http\Controllers\Api\Graphic;

use App\Http\Controllers\Controller;
use App\Http\Resources\Credit\CreditBalanceResource;
use Illuminate\Http\Request;

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
        try {
            return response()->json([
                'status' => true,
                'data' => (new CreditBalanceResource())->getGraphicData(auth()->user(), 1),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?? 400);
        }
    }
}
