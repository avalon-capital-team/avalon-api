<?php

namespace App\Http\Controllers\Api\Compliance;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserComplianceResource;
use App\Http\Resources\User\UserOnboardingResource;
use App\Http\Requests\User\UserComplianceRequest;
use App\Http\Requests\Settings\SettingsComplianceRequest;


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
        $validated = $request->validated();

        try {
            $files = array(
                array(
                    'name' => 'driver_license',
                    'file' => $validated['doc_front'],
                    'file_back' => $validated['doc_back'],
                )
            );
            (new UserComplianceResource())->startCompliancePerson(auth()->user(), $files);

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

    public function upPersonCompliance(SettingsComplianceRequest $request)
    {
        $validated = $request->validated();

        try {
            $files = array(
                array(
                    'name' => 'driver_license',
                    'file' => $validated['doc_front'],
                    'file_back' => $validated['doc_back'],
                )
            );

            (new UserOnboardingResource())->updateStepFour($request);

            // (new UserComplianceResource())->storeOrUpdate(auth()->user(), $files);


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
