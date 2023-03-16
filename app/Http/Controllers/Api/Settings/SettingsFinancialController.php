<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Settings\SettingsFinancialResource;
use App\Http\Requests\Settings\SettingsFinancialBankRequest;
use App\Http\Requests\Settings\SettingsFinancialPixRequest;
use App\Http\Requests\Settings\SettingsFinancialCoinRequest;

class SettingsFinancialController extends Controller
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
     * Get Profile Data
     *
     * @param  string $username
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function data()
    {
        try {
            return response()->json([
                'status'  => true,
                'data' => (new SettingsFinancialResource())->data(auth()->user())
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?? 400);
        }
    }

    /**
     * @param  \App\Http\Resources\Settings\SettingsFinancialResource $resource
     * @param  \App\Http\Requests\Settings\SettingsFinancialPixRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePix(SettingsFinancialResource $resource, SettingsFinancialPixRequest $request)
    {
        try {
            DB::beginTransaction();

            if ($resource->updatePix(auth()->user(), $request)) {
                DB::commit();

                return response()->json([
                    'status'  => true,
                    'message' => 'Configuração geral atualizada'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?? 400);
        }
    }
    /**
     * @param  \App\Http\Resources\Settings\SettingsFinancialResource $resource
     * @param  \App\Http\Requests\Settings\SettingsFinancialBankRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateBank(SettingsFinancialResource $resource, SettingsFinancialBankRequest $request)
    {
        try {
            DB::beginTransaction();

            if ($resource->updateBank(auth()->user(), $request)) {
                DB::commit();

                return response()->json([
                    'status'  => true,
                    'message' => 'Configuração geral atualizada'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?? 400);
        }
    }

    /**
     * @param  \App\Http\Resources\Settings\SettingsFinancialResource $resource
     * @param  \App\Http\Requests\Settings\SettingsFinancialCoinRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCrypto(SettingsFinancialResource $resource, SettingsFinancialCoinRequest $request)
    {
        try {
            DB::beginTransaction();

            if ($resource->updateCrypto(auth()->user(), $request)) {
                DB::commit();

                return response()->json([
                    'status'  => true,
                    'message' => 'Configuração geral atualizada'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?? 400);
        }
    }
}
