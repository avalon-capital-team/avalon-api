<?php

namespace App\Http\Resources\Settings;

use App\Helpers\FileUploadHelper;
use App\Http\Requests\Settings\SettingsComplianceRequest;
use App\Models\User;

class SettingsComplianceResource
{
    /**
     * Data user compliance
     *
     * @param  \App\Models\User $user
     * @return array
     */
    public function data(User $user)
    {
        return [
            'status' => [
                'name' => $user->compliance->status->name,
                'id' => $user->compliance->status_id,
            ],
            'message' => $user->compliance->message,
        ];
    }

    /**
     * @param  \App\Http\Requests\Settings\SettingsComplianceRequest $request
     * @return bool
     * @throws \Exception
     */
    public function update(SettingsComplianceRequest $request)
    {
        $validated = $request->validated();

        if (!in_array($request->user()->compliance->status_id, [1, 3])) {
            throw new \Exception('Já existem documentos sendo validados. Você será notificado quando finalizarmos.', 400);
        }

        $archives = [];

        # Front Document
        if ($validated['front_document']) {
            $front_document = (new FileUploadHelper())->storeFile($validated['front_document'], 'compliance');
            $archives[] = [
                'type' => 'front_document',
                'file' => $front_document
            ];
        }

        # Back Document
        if (isset($validated['back_document'])) {
            $back_document = (new FileUploadHelper())->storeFile($validated['back_document'], 'compliance');
            $archives[] = [
                'type' => 'back_document',
                'file' => $back_document
            ];
        }

        return $request->user()->compliance->update([
            'archives' => json_encode($archives),
            'status_id' => 2
        ]);
    }
}
