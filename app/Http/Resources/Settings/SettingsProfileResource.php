<?php

namespace App\Http\Resources\Settings;

use App\Helpers\FileUploadHelper;
use App\Http\Requests\Settings\SettingsProfileRequest;
use App\Models\User;
use Illuminate\Support\Arr;

class SettingsProfileResource
{
    /**
     * Data user general
     *
     * @param  \App\Models\User $user
     * @return array
     */
    public function data(User $user)
    {
        return [
            'name' => $user->name,
            'username' => $user->username,
            'city' => ($user->address) ? $user->address->city : null,
            'state' => ($user->address) ? $user->address->state : null,
            'bio' => $user->profile->bio,
            'profession' => $user->profile->profession,
            'cover' => $user->profile->cover,
            'avatar' => $user->profile->avatar,
        ];
    }

    /**
     * @param  \App\Http\Requests\Settings\SettingsProfileRequest $request
     * @return bool
     * @throws \Exception
     */
    public function update(SettingsProfileRequest $request)
    {
        $validated = $request->validated();

        # Avatar
        if ($validated['avatar']) {
            $avatar_url = (new FileUploadHelper())->storeFile($validated['avatar'], 'avatars');
            $request->user()->profile()->update([
                'avatar' => $avatar_url
            ]);
        }

        return true;
    }
}
