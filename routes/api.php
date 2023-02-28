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

        # Step Four
        Route::post('update-step-four', [App\Http\Controllers\Api\Onboarding\OnboardingStepFourController::class, 'updateStep']);
    });

    # User
    Route::group(['prefix' => 'user'], function () {
        # User
        Route::get('data', [App\Http\Controllers\Api\Account\AccountController::class, 'userData']);
    });

    # Profile
    Route::group(['prefix' => 'profile'], function () {
        # Data
        Route::get('{username}', [App\Http\Controllers\Api\Profile\ProfileController::class, 'data']);

        # Following
        Route::get('{username}/following', [App\Http\Controllers\Api\Profile\ProfileController::class, 'following'])->name('api.profile.following');

        # Followers
        Route::get('{username}/followers', [App\Http\Controllers\Api\Profile\ProfileController::class, 'followers'])->name('api.profile.followers');

        # Actions
        Route::get('{username}/follow', [App\Http\Controllers\Api\Profile\ProfileController::class, 'follow']);
        Route::get('{username}/unfollow', [App\Http\Controllers\Api\Profile\ProfileController::class, 'unfollow']);
    });

    # Settings
    Route::group(['prefix' => 'settings'], function () {
        # General
        Route::get('general', [App\Http\Controllers\Api\Settings\SettingsGeneralController::class, 'data']);
        Route::post('general/update', [App\Http\Controllers\Api\Settings\SettingsGeneralController::class, 'update']);

        # Notifications
        Route::get('notifications', [App\Http\Controllers\Api\Settings\SettingsNotificationsController::class, 'data']);
        Route::post('notifications/update', [App\Http\Controllers\Api\Settings\SettingsNotificationsController::class, 'update']);

        # Social
        Route::get('social', [App\Http\Controllers\Api\Settings\SettingsSocialController::class, 'data']);
        Route::post('social/update', [App\Http\Controllers\Api\Settings\SettingsSocialController::class, 'update']);

        # Profile
        Route::get('profile', [App\Http\Controllers\Api\Settings\SettingsProfileController::class, 'data']);
        Route::post('profile/update', [App\Http\Controllers\Api\Settings\SettingsProfileController::class, 'update']);

        # Compliance
        Route::get('compliance', [App\Http\Controllers\Api\Settings\SettingsComplianceController::class, 'data']);
        Route::post('compliance/update', [App\Http\Controllers\Api\Settings\SettingsComplianceController::class, 'update']);

        # Privacy
        Route::get('privacy', [App\Http\Controllers\Api\Settings\SettingsPrivacyController::class, 'data']);
        Route::post('privacy/update', [App\Http\Controllers\Api\Settings\SettingsPrivacyController::class, 'update']);

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

    # Notifications
    Route::get('/notifications', [App\Http\Controllers\Api\Notifications\NotificationsController::class, 'list'])->name('api.notifications');
});

# Helpers
Route::group(['prefix' => 'helpers'], function () {

    # Countries
    Route::get('countries', [App\Http\Controllers\Api\Helpers\HelpersController::class, 'getAllCountries']);

    # Genre
    Route::get('genres', [App\Http\Controllers\Api\Helpers\HelpersController::class, 'getAllGenres']);

    # Privacy
    Route::get('privacy/types-with-options', [App\Http\Controllers\Api\Helpers\HelpersController::class, 'getAllPrivacyTypeWithOption']);
    Route::get('privacy/types', [App\Http\Controllers\Api\Helpers\HelpersController::class, 'getAllPrivacyType']);
    Route::get('privacy/options', [App\Http\Controllers\Api\Helpers\HelpersController::class, 'getAllPrivacyOption']);
});
