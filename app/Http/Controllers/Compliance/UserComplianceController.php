<?php

namespace App\Http\Controllers\Api\Compliance;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserComplianceResource;
use App\Http\Request\User\UpdateUserDocumentRequest;

use Illuminate\Support\Facades\DB;

class UserComplianceController extends Controller
{
    /**
     * @param  \App\Http\Resources\User\UserComplianceResource $resource
     * @return \Illuminate\Http\JsonResponse
     */
    public function userComplianceStatus()
    {
        try {
            return response()->json([
                'status' => true,
                'user' => (new UserComplianceResource())->findByUserId(auth()->user()->id),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?? 400);
        }
    }

    /**
     * @param  \App\Http\Resources\Auth\VerifyResource $resource
     * @param  \App\Http\Requests\Auth\VerifyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function startCompliancePerson(UserComplianceResource $resource, UpdateUserDocumentRequest $request)
    {
        try {
            if ($resource->verifyResource($request)) {

                return response()->json([
                    'status'  => true,
                    'message' => __('E-mail verificado com sucesso'),
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message'  => $e->getMessage()
            ], 400);
        }
    }
}
