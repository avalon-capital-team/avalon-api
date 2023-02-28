<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResendRequest;
use App\Http\Requests\Auth\VerifyRequest;
use App\Http\Resources\Auth\VerifyResource;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class VerifyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('resendCode');
    }

    /**
     * @param  \App\Http\Resources\Auth\VerifyResource $resource
     * @param  \App\Http\Requests\Auth\VerifyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyCode(VerifyResource $resource, VerifyRequest $request)
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

    /**
     * @param  \App\Http\Resources\Auth\ResendRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendCode(ResendRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            (new VerifyResource())->requestVerify($user);

            return response()->json([
                'status'  => true,
                'message' => __('Código de verificação enviado com sucesso.'),
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
