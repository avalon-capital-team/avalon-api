<?php

namespace App\Http\Controllers\Api\Compliance;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserComplianceResource;
use App\Http\Requests\User\UserComplianceRequest;


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
     * @param  \App\Http\Resources\User\UserComplianceResource $resource
     * @param  \App\Http\Requests\User\UpdateUserDocumentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function startPersonCompliance(UserComplianceRequest $request)
    {
        try {
            $file = [
                'doc_front' => $request->doc_front,
                'doc_back' => $request->doc_back,
                'proof_address' => $request->proof_address,
                'terms_and_police' => $request->terms_and_police
            ];

            (new UserComplianceResource())->startCompliancePerson(auth()->user(), $file);

            return response()->json([
                'status'  => true,
                'message' => __('Documentos enviados com sucesso'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message'  => $e->getMessage()
            ], 400);
        }
    }
}
