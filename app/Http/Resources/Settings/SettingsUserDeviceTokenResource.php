<?php

namespace App\Http\Resources\Settings;

use App\Http\Requests\Settings\SettingsDeviceTokenUpdateRequest;
use App\Models\User;

class SettingsUserDeviceTokenResource
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
     * @param  \App\Http\Requests\Settings\SettingsDeviceTokenUpdateRequest $request
     * @return bool
     * @throws \Exception
     */
    public function update(SettingsDeviceTokenUpdateRequest $request)
    {
        $validated = $request->validated();

        if($request->type == 'app'){
            return $request->user()->tokenDevice()->update(['device_token_app' => $request->token]);
        }else{
            return $request->user()->tokenDevice()->update(['device_token_web' => $request->token]);
        }
    }
}
