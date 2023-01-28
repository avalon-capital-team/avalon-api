<?php

namespace App\Http\Resources\Settings;

use App\Http\Requests\Settings\SettingsSecurity2FaActivateRequest;
use App\Http\Requests\Settings\SettingsSecurity2FaDisableRequest;
use App\Http\Requests\Settings\SettingsSecurityPasswordRequest;
use App\Models\User;
use PragmaRX\Google2FALaravel\Facade as Google2FA;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class SettingsSecurityResource
{
    /**
     * @param  \App\Http\Requests\Settings\SettingsSecurityPasswordRequest $request
     *
     * @return bool
     * @throws \Exception
     */
    public function updatePassword(SettingsSecurityPasswordRequest $request)
    {
        $validated = $request->validated();

        if (!Hash::check($validated['password_current'], $request->user()->password)) {
            throw new \Exception('A senha atual informada é inválida.', 403);
        }

        if (Hash::check($validated['password_new'], $request->user()->password)) {
            throw new \Exception('Informe uma senha nova que ainda não utilizou antes.', 406);
        }

        return $request->user()->update([
            'password' => Hash::make($validated['password_new'])
        ]);
    }

    /**
     * @param  \App\Models\User $user
     *
     * @return array
     */
    public function get2faData(User $user)
    {
        if ($user->security->google_2fa) {
            return [
                'secret' => $user->security->google_2fa
            ];
        }

        $twoauth['secret'] = Google2FA::generateSecretKey();
        $twoauth['image'] = Google2FA::getQRCodeInline(
            config('app.name'),
            $user->email,
            $twoauth['secret'],
            200
        );

        return $twoauth;
    }

    /**
     * @param  \App\Http\Requests\Settings\SettingsSecurity2FaActivateRequest $request
     *
     * @return bool
     * @throws \Exception
     */
    public function activateTwoAuth(SettingsSecurity2FaActivateRequest $request)
    {
        $validated = $request->validated();

        if ($request->user()->security->google_2fa) {
            throw new \Exception(__('Você já tem o 2FA ativo, não pode ativa novamente, apenas após desativar.'), 403);
        }

        $valid = Google2FA::verifyKey($validated['secret'], $validated['code_twoauth']);
        if (!$valid) {
            throw new \Exception(__('Código 2FA informado esta incorreto.'));
        }

        return $request->user()->security->update([
            'google_2fa' => Crypt::encryptString($validated['secret'])
        ]);
    }

    /**
     * @param  \App\Http\Requests\Settings\SettingsSecurity2FaDisableRequest $request
     *
     * @return boolean
     * @throws \Exception
     */
    public function disableTwoAuth(SettingsSecurity2FaDisableRequest $request)
    {
        $validated = $request->validated();

        if (!$request->user()->security->google_2fa) {
            throw new \Exception(__('Você não tem o 2FA ativo para poder desativar.'), 403);
        }

        $valid = Google2FA::verifyKey($request->user()->security->google_2fa, $validated['code_twoauth']);
        if (!$valid) {
            throw new \Exception(__('Código 2FA informado esta incorreto.'));
        }

        return $request->user()->security->update([
            'google_2fa' => null
        ]);
    }
}
