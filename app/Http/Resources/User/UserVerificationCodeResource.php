<?php

namespace App\Http\Resources\User;

use App\Helpers\LocationHelper;
use App\Models\User;
use App\Models\User\UserVerificationCode;
use App\Notifications\User\ConfirmationCodeNotification;
use Illuminate\Support\Facades\Hash;

class UserVerificationCodeResource
{
    /**
     * Create a verification code for the type.
     *
     * @param  \App\Models\User $user
     * @param  string $type
     * @return string
     */
    public static function createFor(User $user, string $type)
    {
        $verification = UserVerificationCode::create([
            'user_id' => $user->id,
            'code' => $code = rand(100000, 999999),
            'type' => $type,
            'log' => LocationHelper::getInfo()

        ]);

        (new UserVerificationCodeResource())->sendMail($user, $verification, $code);

        return $code;
    }

    /**
     * Send mail
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\User\UserVerificationCode $verification
     * @param  string $code;
     * @return void
     */
    public function sendMail(User $user, UserVerificationCode $verification, string $code)
    {
        if ($verification->type == 'updated_password') {
            $reason = 'Alteração de senha';
        } elseif ($verification->type == 'withdrawal_fiat') {
            $reason = 'Saque Fiat';
        } elseif ($verification->type == 'payment_of_installments') {
            $reason = 'Pagamento da parcela';
        } else {
            $reason = 'Verificação';
        }

        // $user->notify(new ConfirmationCodeNotification($verification, $reason, $code));
    }

    /**
     * Verify the code.
     *
     * @param  string $code
     * @param  string $type
     * @param  int $userId
     * @return bool
     */
    public function verify(string $code, string $type, int $userId)
    {
        $codeIsValid = UserVerificationCode::query()
            ->for($type)
            ->where('user_id', $userId)
            ->notExpired()
            ->notUsed()
            ->cursor()
            ->contains(function ($verificationCode) use ($code) {
                return Hash::check($code, $verificationCode->code);
            });

        if (!$codeIsValid) {
            return false;
        }

        return true;
    }

    /**
     * Use codes by type
     *
     * @param  string $type
     * @param  int $userId
     * @return bool
     */
    public function useCodesFor(string $type, int $userId)
    {
        $getOldCodes = UserVerificationCode::query()
            ->for($type)
            ->where('user_id', $userId)
            ->notExpired()
            ->notUsed()
            ->get();

        foreach ($getOldCodes as $code) {
            $code->used = 1;
            $code->save();
        }
    }

    /**
     * Check if the code is the test code.
     *
     * @param  string $code
     * @return bool
     */
    protected function isTestCode(string $code)
    {
        if (empty(config('verification-code.test_code'))) {
            return false;
        }

        return $code === config('verification-code.test_code');
    }
}
