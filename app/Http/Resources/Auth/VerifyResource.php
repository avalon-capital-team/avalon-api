<?php

namespace App\Http\Resources\Auth;

use App\Http\Requests\Auth\VerifyRequest;
use App\Http\Resources\User\UserResource;
use App\Helpers\CodeVerifyHelper;
use App\Notifications\Auth\VerifyCodeNotification;
use App\Models\User;

class VerifyResource
{
    /**
     * @param \App\Http\Requests\Auth\VerifyRequest $request
     * @return bool
     * @throws \Exception
     */
    public function verifyResource(VerifyRequest $request)
    {
        $validated = $request->validated();

        $code = (new UserResource())->verifyCode($validated['code'], $validated['email']);

        if (!$code) {
            throw new \Exception('E-mail não pode ser validado', 404);
        }

        $code->email_verified_at = date("Y-m-d H:i:s");
        $code->save();

        return true;
    }

    /**
     * @param \App\Http\Requests\Auth\VerifyRequest $request
     * @return bool
     * @throws \Exception
     */

    public function requestVerify($user)
    {
        $code = CodeVerifyHelper::generateCode();
        $user->verification_code = $code;
        $user->notify(new VerifyCodeNotification($user->id, $code));
        $user->save();

        return $user->verification_code;
    }
}
