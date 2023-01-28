<?php

namespace App\Http\Resources\Password;

use App\Models\Password\PasswordResetToken;

class PasswordResetTokenResource
{
    /**
     * @param string $token
     * @return \App\Models\Password\PasswordResetToken
     */
    public function findByToken(string $token)
    {
        return PasswordResetToken::where('token', $token)->first();
    }
}
