<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\VerifyResource;
use App\Http\Resources\User\UserOnboardingResource;
use App\Http\Resources\User\UserProfileResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Login The User
     *
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        try {
            $validated = $request->validated();

            if (!Auth::attempt($validated)) {
                return response()->json([
                    'status' => false,
                    'message' => __('Dados de acesso incorretos'),
                ], 401);
            }

            $user = User::where('email', $request->email)->first();
            $apiName = ($request->header('device-type')) ? $request->header('device-type') : 'web';

            if (!$user->email_verified_at) {
                (new VerifyResource())->requestVerify($user);
            }

            return response()->json([
                'status' => true,
                'message' => 'Acesso realizado com sucesso',
                'token' => $user->createToken($apiName)->plainTextToken,
                'onboarding' => [
                    'step' => (new UserOnboardingResource())->getActualStep($user)
                ],
                'user' => (new UserProfileResource())->profileDetail(auth()->user()),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();

        return response()->json([
            'status'  => true,
            'message' => 'Logout realizado com sucesso'
        ], 200);
    }
}
