<?php

namespace App\Http\Resources\Settings;

use App\Http\Requests\Settings\SettingsSocialRequest;
use App\Models\User;

class SettingsSocialResource
{
    /**
     * Data user social
     *
     * @param  \App\Models\User $user
     * @return array
     */
    public function data(User $user)
    {
        return [
            'link_facebook' => $user->profile->link_facebook,
            'link_instagram' => $user->profile->link_instagram,
            'link_tiktok' => $user->profile->link_tiktok,
            'link_twitter' => $user->profile->link_twitter,
        ];
    }

    /**
     * @param  \App\Http\Requests\Settings\SettingsSocialRequest $request
     * @return bool
     * @throws \Exception
     */
    public function update(SettingsSocialRequest $request)
    {
        $validated = $request->validated();
        return $request->user()->profile()->update($validated);
    }
}
