<?php

namespace App\Http\Resources\Settings;

use App\Models\User;

use App\Models\Data\DataBank;

class SettingsFinancialResource
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
            'data' => $user->financial,
        ];
    }

    /**
     * Update financial PIX of user
     *
     * @param  \App\Models\User $user
     * @param  string $coin_id
     * @param  string $address
     * @param  string $network
     * @return bool
     */
    public function updateCrypto(User $user, $item)
    {
        $data = [
            'coin_id' => $item['coin_id'],
            'address' => $item['address'],
            'network' => $item['network'],
        ];

        return $user->financial
            ->where('type', 'crypto')
            ->first()
            ->update([
                'data' => ($data),
            ]);
    }

    /**
     * Update financial PIX of user
     *
     * @param  \App\Models\User $user
     * @param  string $key_type
     * @param  string $key
     * @return bool
     */
    public function updatePix(User $user, $item)
    {
        $data = [
            'key_type' => $item['key_type'],
            'key' => $item['key']
        ];

        return $user->financial
            ->where('type', 'pix')
            ->first()
            ->update([
                'data' => ($data),
            ]);
    }

    /**
     * Update financial BANK of user
     *
     * @param  \App\Models\User $user
     * @param  int $bank_id
     * @param  string $type
     * @param  string $agency
     * @param  string $account
     * @param  string $account_digit
     * @return bool
     */
    public function updateBank(User $user, $item)
    {
        $bank = DataBank::where('id', $item['bank_id'])->first();

        $data = [
            'bank_id' => $bank->id,
            'bank_code' => $bank->code,
            'bank_name' => $bank->name,
            'type' => $item['type'],
            'account' => $item['account'],
            'account_digit' => $item['account_digit'],
            'agency' => $item['agency'],
        ];

        return $user->financial
            ->where('type', 'bank')
            ->first()
            ->update([
                'data' => ($data),
            ]);
    }
}
