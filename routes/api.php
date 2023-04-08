<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Broadcast::routes(['middleware' => ['auth:sanctum']]);

# API Auth
Route::group(['prefix' => 'auth'], function () {
    # Login
    Route::post('signin', [App\Http\Controllers\Api\Auth\LoginController::class, 'login']);

    # Forgot Password
    Route::post('forgot-password', [App\Http\Controllers\Api\Auth\ForgotPasswordController::class, 'recoverPassword']);

    # Reset Password
    Route::post('reset-password', [App\Http\Controllers\Api\Auth\ResetPasswordController::class, 'updatePasswordRecovery']);

    # Register
    Route::post('signup', [App\Http\Controllers\Api\Auth\RegisterController::class, 'register']);

    # Resend Code
    Route::get('resend-code', [App\Http\Controllers\Api\Auth\VerifyController::class, 'resendCode']);
});

# API Logged
Route::group(['middleware' => 'auth:sanctum'], function () {

    # Verify Code
    Route::group(['prefix' => 'onboarding'], function () {
        Route::post('verify-code', [App\Http\Controllers\Api\Auth\VerifyController::class, 'verifyCode']);
    });

    # Onboarding
    Route::group(['prefix' => 'onboarding'], function () {
        Route::get('actual-step', [App\Http\Controllers\Api\Onboarding\OnboardingController::class, 'getActualStep']);
        Route::get('all-steps', [App\Http\Controllers\Api\Onboarding\OnboardingController::class, 'getAllSteps']);

        # Step One
        Route::post('update-step-one', [App\Http\Controllers\Api\Onboarding\OnboardingStepOneController::class, 'updateStep']);

        # Step Two
        Route::post('update-step-two', [App\Http\Controllers\Api\Onboarding\OnboardingStepTwoController::class, 'updateStep']);

        # Step Three
        Route::post('update-step-three', [App\Http\Controllers\Api\Onboarding\OnboardingStepThreeController::class, 'updateStep']);
    });

    # Compliance
    Route::group(['prefix' => 'compliance'], function () {
        Route::get('user-compliance-status', [App\Http\Controllers\Api\Compliance\UserComplianceController::class, 'userComplianceStatus']);

        # Send Documents
        Route::post('start-compleance-person', [App\Http\Controllers\Api\Compliance\UserComplianceController::class, 'startPersonCompliance']);
        Route::post('store-or-update', [App\Http\Controllers\Api\Compliance\UserComplianceController::class, 'upPersonCompliance']);
    });

    # User
    Route::group(['prefix' => 'user'], function () {
        # User
        Route::get('data', [App\Http\Controllers\Api\Account\AccountController::class, 'userData']);
        Route::get('plan', [App\Http\Controllers\Api\Plan\UserPlanController::class, 'getUserPlan']);

        # Plan
        Route::post('order/generate', [App\Http\Controllers\Api\Plan\UserPlanController::class, 'createOrUpdate']);
        #UploadFile
        Route::post('plan/upload-voucher', [App\Http\Controllers\Api\Plan\UserPlanController::class, 'uploadeVoucher']);
        #Extract
        Route::get('plan/extract', [App\Http\Controllers\Api\Plan\ExtractController::class, 'getExtract']);

        Route::get('plan/get-plans', [App\Http\Controllers\Api\Plan\UserPlanController::class, 'getUserPlans']);
    });

    # Manange/Accessor
    Route::group(['prefix' => 'mananger'], function () {
        Route::get('list', [App\Http\Controllers\Api\Sponsor\SponsorController::class, 'getIndicateList']);

        # List of Managers and Adivisors
        Route::get('users', [App\Http\Controllers\Api\Sponsor\SponsorController::class, 'getUsersTypes']);

        # Set Indicate
        Route::post('set-indicator', [App\Http\Controllers\Api\Sponsor\SponsorController::class, 'setIndicate']);

        # Set User Mananger
        Route::post('set-mananger', [App\Http\Controllers\Api\Sponsor\SponsorController::class, 'setMananger']);
    });

    # Withdrawl
    Route::group(['prefix' => 'withdrawal'], function () {
        # Request
        Route::post('request', [App\Http\Controllers\Api\Wallet\WithdrawalController::class, 'withdrawl']);
    });

    # Settings
    Route::group(['prefix' => 'settings'], function () {
        # General
        Route::get('general', [App\Http\Controllers\Api\Settings\SettingsGeneralController::class, 'data']);
        Route::post('general/update', [App\Http\Controllers\Api\Settings\SettingsGeneralController::class, 'update']);

        # Profile
        Route::post('profile/update', [App\Http\Controllers\Api\Settings\SettingsProfileController::class, 'update']);

        # Compliance
        Route::get('compliance', [App\Http\Controllers\Api\Settings\SettingsComplianceController::class, 'data']);
        Route::post('compliance/update', [App\Http\Controllers\Api\Settings\SettingsComplianceController::class, 'update']);

        # Financial
        Route::get('financial', [App\Http\Controllers\Api\Settings\SettingsFinancialController::class, 'data']);
        Route::post('finacial/update-pix', [App\Http\Controllers\Api\Settings\SettingsFinancialController::class, 'updatePix']);
        Route::post('finacial/update-bank', [App\Http\Controllers\Api\Settings\SettingsFinancialController::class, 'updateBank']);
        Route::post('finacial/update-crypto', [App\Http\Controllers\Api\Settings\SettingsFinancialController::class, 'updateCrypto']);

        # Security
        Route::post('security/update-password', [App\Http\Controllers\Api\Settings\SettingsSecurityController::class, 'updatePassword']);
        Route::get('security/2fa', [App\Http\Controllers\Api\Settings\SettingsSecurityController::class, 'data2fa']);
        Route::post('security/2fa/enable', [App\Http\Controllers\Api\Settings\SettingsSecurityController::class, 'enable2fa']);
        Route::post('security/2fa/disable', [App\Http\Controllers\Api\Settings\SettingsSecurityController::class, 'disable2fa']);

        # Access Logs
        Route::get('access-logs', [App\Http\Controllers\Api\Settings\SettingsAccessLogsController::class, 'data']);

        # Update Device token
        Route::post('device-token/update', [App\Http\Controllers\Api\Settings\SettingsDeviceTokenController::class, 'updateDeviceToken']);
    });

    # TESTE RENTABIL
    Route::get('rentabil', [App\Http\Controllers\Api\Plan\UserPlanController::class, 'rentabil']);

    # Notifications
    Route::get('/notifications', [App\Http\Controllers\Api\Notifications\NotificationsController::class, 'list'])->name('api.notifications');
});

# Coin Tracker
Route::group(['prefix' => 'coin'], function () {
    Route::get('tracker', [App\Http\Controllers\Api\Coin\CoinTrackerController::class, 'coinTracker']);
});

# Graphic
Route::group(['prefix' => 'graphic'], function () {
    Route::get('get-data', [App\Http\Controllers\Api\Graphic\GraphicDataController::class, 'getGraphic']);
});

# Helpers
Route::group(['prefix' => 'helpers'], function () {

    # Banks
    Route::get('banks', [App\Http\Controllers\Api\Helpers\HelpersController::class, 'getAllBanks']);

    # Plans
    Route::get('plans', [App\Http\Controllers\Api\Helpers\HelpersController::class, 'getAllPlans']);

    # Countries
    Route::get('countries', [App\Http\Controllers\Api\Helpers\HelpersController::class, 'getAllCountries']);

    # Genre
    Route::get('genres', [App\Http\Controllers\Api\Helpers\HelpersController::class, 'getAllGenres']);

    # Privacy
    Route::get('privacy/types-with-options', [App\Http\Controllers\Api\Helpers\HelpersController::class, 'getAllPrivacyTypeWithOption']);
    Route::get('privacy/types', [App\Http\Controllers\Api\Helpers\HelpersController::class, 'getAllPrivacyType']);
    Route::get('privacy/options', [App\Http\Controllers\Api\Helpers\HelpersController::class, 'getAllPrivacyOption']);
});
