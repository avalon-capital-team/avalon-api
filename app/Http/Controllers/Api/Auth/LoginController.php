<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Plan\PlanResource;
use App\Http\Resources\User\UserOnboardingResource;
use App\Http\Resources\User\UserProfileResource;
use App\Http\Resources\Settings\SettingsSecurityResource;
use App\Http\Resources\User\UserComplianceResource;
use App\Models\User;
use App\Rules\CheckVerificationCodeRule;
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
      $apiName = ($request->header('device-type')) ? $request->header('device-type') : 'web';

      if (!Auth::attempt($validated)) {
        return response()->json([
          'status' => false,
          'message' => __('Dados de acesso incorretos'),
        ], 401);
      }

      $user = User::where('email', $request->email)->first();

      if ($user->id != 1 && $user->type == 'admin' && !$user->security->google_2fa) {
        return response()->json([
          'status' => false,
          'message' => 'Necessario habilitar 2FA para ter acesso!',
          'token' => $user->createToken($apiName)->plainTextToken,
          'twoFa' => (new SettingsSecurityResource())->get2faData(auth()->user()),
        ], 200);
      }

      if ($user->id != 1 && $user->type == 'admin') {
          $checkCode = new CheckVerificationCodeRule('loginadmin');

          if (!$checkCode->passes('code', $request->code)) {
            return response()->json([
              'status' => false,
              'message' => $checkCode->message()
            ], 401);
          }
      }

      if ($user->id != 1) {
        return response()->json([
          'status' => true,
          'message' => 'Acesso realizado com sucesso',
          'token' => $user->createToken($apiName)->plainTextToken,
          'onboarding' => [
            'step' => (new UserOnboardingResource())->getActualStep($user)
          ],
          'compliance' => (new UserComplianceResource())->findByComplianceStatus($user),
          'user' => (new UserProfileResource())->profileDetail(auth()->user()),
          'automatic_report' => (new PlanResource())->getAutomaticReport(auth()->user()),
          'twoFa' => (new SettingsSecurityResource())->get2faData(auth()->user()),
        ], 200);
      }

      return response()->json([
        'status' => true,
        'message' => 'Acesso realizado com sucesso',
        'token' => $user->createToken($apiName)->plainTextToken,
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
