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
            'birth_date' => $user->birth_date,
            'country' => ($user->address->country) ? $user->address->country->name : null,
            'phone' => $user->phone,
            'genre' => ($user->genre) ? $user->genre->name : null,
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

        $genre = (new DataGenreResource())->findByName($validated['genre']);
        if (!$genre) {
            throw new \Exception('Gênero selecionado inválido.', 400);
        }

        $country = (new DataCountryResource())->findByName($validated['country']);
        if (!$country) {
            throw new \Exception('País selecionado inválido.', 400);
        }

        $request->user()->address->update([
            'country_id' => $country->id
        ]);

        return $request->user()->update([
            'email' => $validated['email'],
            'birth_date' => $validated['birth_date'],
            'phone' => $validated['phone'],
            'genre_id' => $genre->id
        ]);
    }
}
