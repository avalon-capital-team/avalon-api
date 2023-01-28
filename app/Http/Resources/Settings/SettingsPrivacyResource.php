<?php

namespace App\Http\Resources\Settings;

use App\Http\Requests\Settings\SettingsPrivacyRequest;
use App\Http\Resources\Data\DataPrivacyTypeOptionResource;
use App\Http\Resources\Data\DataPrivacyTypeResource;
use App\Models\User;

class SettingsPrivacyResource
{
    /**
     * Data user privacy
     *
     * @param  \App\Models\User $user
     *
     * @return array
     */
    public function data(User $user)
    {
        return $user->privacy()
            ->join('data_privacy_types', 'users_privacy.privacy_type_id', '=', 'data_privacy_types.id')
            ->join('data_privacy_types_options', 'users_privacy.privacy_type_option_id', '=', 'data_privacy_types_options.id')
            ->select('data_privacy_types.code as type_code', 'data_privacy_types.name as type_name', 'data_privacy_types_options.code as selected_code', 'data_privacy_types_options.name as selected_name')
            ->get()
            ->toArray();
    }

    /**
     * @param  \App\Http\Requests\Settings\SettingsPrivacyRequest $request
     *
     * @return bool
     * @throws \Exception
     */
    public function update(SettingsPrivacyRequest $request)
    {
        $validated = $request->validated();

        $privacyType = (new DataPrivacyTypeResource())->findByCode($validated['code']);
        if (!$privacyType) {
            throw new \Exception('Tipo de privacidade selecionado inválido.', 400);
        }

        $privacyTypeOption = (new DataPrivacyTypeOptionResource())->findByCode($validated['option_code']);
        if (!$privacyTypeOption) {
            throw new \Exception('Tipo de privacidade selecionado inválido.', 400);
        }

        return $request->user()->privacy()->where('privacy_type_id', $privacyType->id)->update([
            'privacy_type_option_id' => $privacyTypeOption->id,
        ]);
    }
}
