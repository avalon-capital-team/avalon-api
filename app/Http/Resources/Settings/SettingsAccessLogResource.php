<?php

namespace App\Http\Resources\Settings;

use App\Models\User;

class SettingsAccessLogResource
{
    /**
     * Data user access log
     *
     * @param  \App\Models\User $user
     * @return array
     */
    public function data(User $user)
    {
        return $user->authentications;
    }
}
