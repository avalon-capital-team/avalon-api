<?php

namespace Database\Seeders\System;

use Illuminate\Database\Seeder;
use App\Http\Resources\Coin\CoinResource;
use App\Models\System\PaymentMethod\PaymentMethod;

class PaymentMethodTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coin['BRL'] = (new CoinResource())->findBySymbol('BRL');

        $bankManual = PaymentMethod::create([
            'coin_id' =>  $coin['BRL']->id,
            'code' => 'bank_manual',
            'name' => 'Banco Manual',
            'name_show' => 'Transferência bancária (TED/DOC)',
            'type' => 'external',
            'status' => 0,
            'data' => [
                'bank_name' => 'Nu Bank Pagamentos S.A. (260)',
                'beneficiary' => 'Avalon Capital LTDA ',
                'document' => '00.000.000/0000-00',
                'agency' => '9999-9',
                'account' => '888888',
            ]
        ]);

        $balance = PaymentMethod::create([
            'coin_id' =>  $coin['BRL']->id,
            'code' => 'balance_brl',
            'name' => 'Saldo Real',
            'type' => 'internal',
            'name_show' => 'Saldo disponível',
            'status' => 1,
        ]);
    }
}
