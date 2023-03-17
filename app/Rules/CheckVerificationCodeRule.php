<?php

namespace App\Rules;

use App\Http\Resources\User\UserVerificationCodeResource;
use Illuminate\Contracts\Validation\Rule;
use PragmaRX\Google2FALaravel\Facade as Google2FA;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Crypt;

class CheckVerificationCodeRule implements Rule
{
    public $type;

    /**
     * Create a new notification instance.
     *
     * @param App\Models\User\UserVerificationCode $userVerificationCode
     *
     * @return void
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (in_array(config('app.env'), ['local', 'testing'])) {
            return true;
        }

        if (auth()->user()->security->google2fa) {
            $valid = Google2FA::verifyKey(Crypt::decryptString(auth()->user()->security->google2fa), $value);
            return ($valid) ? true : false;
        }

        if ((new UserVerificationCodeResource())->verify($value, $this->type, auth()->user()->id)) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O Código de segurança informado é inválido.';
    }
}
