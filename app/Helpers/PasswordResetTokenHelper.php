<?php

namespace App\Helpers;

use App\Models\Password\PasswordResetToken;
use Illuminate\Support\Str;

class PasswordResetTokenHelper
{
    /**
     * @return string
     * @throws \Exception
     */
    public static function generateToken()
    {
        do {
            // $token = Str::random(6);

            $token = rand(100000, 999999);
        } while (PasswordResetToken::where('token', $token)->exists());

        return $token;
    }
}
