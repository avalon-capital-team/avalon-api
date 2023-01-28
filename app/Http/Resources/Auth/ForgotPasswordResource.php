<?php

namespace App\Http\Resources\Auth;

use App\Helpers\PasswordResetTokenHelper;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Resources\User\UserResource;
use App\Models\Password\PasswordResetToken;
use App\Notifications\Auth\ForgotPasswordNotification;

class ForgotPasswordResource
{
    /**
     * @param \App\Http\Requests\Auth\ForgotPasswordRequest $request
     * @return bool
     * @throws \Exception
     */
    public function recoverPassword(ForgotPasswordRequest $request)
    {
        $validated = $request->validated();

        $user = (new UserResource())->findByEmail($validated['email']);

        if (!$user) {
            throw new \Exception('E-mail não encontrado', 404);
        }

        $token = PasswordResetTokenHelper::generateToken();

        $passwordResetToken = new PasswordResetToken();
        $passwordResetToken->email = $user->email;
        $passwordResetToken->token = $token;
        $passwordResetToken->expires_in = now()->addHour()->format('Y-m-d H:i:s');

        if ($passwordResetToken->save()) {
            $user->notify(new ForgotPasswordNotification($user->id, $token));
            return "Instruções enviadas para $user->email";
        }

        throw new \Exception('Ocorreu um erro. Tente novamente!');
    }
}
