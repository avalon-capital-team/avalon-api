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

        $bank = PaymentMethod::create([
            'coin_id' =>  $coin['BRL']->id,
            'code' => 'bank_manual',
            'name' => 'Banco Manual',
            'name_show' => 'Transferência bancária (TED/DOC)',
            'type' => 'external',
            'status' => 1,

        ]);

        $pix = PaymentMethod::create([
            'coin_id' =>  $coin['BRL']->id,
            'code' => 'pix',
            'name' => 'PIX',
            'name_show' => 'PIX (Pagamento instantâneo)',
            'type' => 'external',
            'status' => 1,
        ]);
    }
}
