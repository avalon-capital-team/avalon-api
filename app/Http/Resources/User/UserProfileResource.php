<?php

namespace App\Http\Resources\User;


use App\Models\User;
use App\Http\Resources\Auth\VerifyResource;


class UserProfileResource
{
    /**
     * @param  \App\Models\User $user
     *
     * @return array
     */
    public function profileDetail(User $user): array
    {
        if ($user->email_verified_at == null) {
            $email_verified_at = false;
            (new VerifyResource())->requestVerify($user);
        } else {
            $email_verified_at = true;
        }

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'avatar' => $user->profile->avatar,
                'phone' => $user->phone,
                'document' => $user->document,
                'type' => $user->type,
                'email_verified' => $email_verified_at
            ]
        ];
    }

    /**
     * @param  \App\Models\User $user
     *
     * @return array
     */
    public function profileDetailSimple(User $user): array
    {
        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'avatar' => $user->profile->avatar,
                'phone' => $user->phone,
                'document' => $user->document,
                'type' => $user->type
            ],
        ];
    }
}
