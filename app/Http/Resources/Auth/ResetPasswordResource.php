<?php

namespace App\Http\Resources\Auth;

use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\Password\PasswordResetTokenResource;
use App\Http\Resources\User\UserResource;

class ResetPasswordResource
{
    /**
     * @param RecoverPasswordRequest $request
     * @return bool
     * @throws \Exception
     */
    public function updatePasswordRecovery(ResetPasswordRequest $request)
    {
        $validated = $request->validated();

        $passwordResetToken = (new PasswordResetTokenResource())->findByToken($validated['token']);

        if (!$passwordResetToken) {
            throw new \Exception(__('Token de recuperação não encontrado'), 404);
        }

        if (!$passwordResetToken->canUse()) {
            throw new \Exception(__('Token de recuperação expirado'), 403);
        }

        $user = (new UserResource())->findByEmail($passwordResetToken->email);
        $user->password = bcrypt($request->password);

        $passwordResetToken->password_updated = true;

        if ($user->save() && $passwordResetToken->save()) {
            return __('Senha alterada com sucesso');
        }

        return false;
    }
}
