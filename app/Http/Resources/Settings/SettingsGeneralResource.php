<?php

namespace App\Http\Resources\Settings;

use App\Http\Requests\Settings\SettingsGeneralRequest;
use App\Http\Resources\Data\DataCountryResource;
use App\Http\Resources\Data\DataGenreResource;
use App\Models\User;

class SettingsGeneralResource
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
            'email' => $user->email,
            'name' => $user->name,
            'document' => $user->document,
            'phone' => $user->phone,
            'birth_date' => $user->birth_date,
        ];
    }

    /**
     * @param  \App\Http\Requests\Settings\SettingsGeneralRequest $request
     * @return bool
     * @throws \Exception
     */
    public function update(SettingsGeneralRequest $request)
    {
        $validated = $request->validated();

        return $request->user()->update([
            'email' => $validated['email'],
            'birth_date' => $validated['birth_date'],
            'phone' => $validated['phone'],
        ]);
    }
}
