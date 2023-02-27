<?php

namespace App\Http\Resources\User;


use App\Models\User;

class UserProfileResource
{
    /**
     * @param  \App\Models\User $user
     *
     * @return array
     */
    public function profileDetail(User $user): array
    {
        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'avatar' => $user->profile->avatar,
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
                'avatar' => $user->profile->avatar,
            ],
        ];
    }
}
