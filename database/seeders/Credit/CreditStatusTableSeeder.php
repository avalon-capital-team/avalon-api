<?php

namespace Database\Seeders\Credit;

use App\Models\Credit\CreditStatus;
use Illuminate\Database\Seeder;

class CreditStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CreditStatus::create([
            'name' => 'Em Ativo',
            'color' => 'success',
            'icon' => '/assets/icons/credits/active.png'
        ]);

        CreditStatus::create([
            'name' => 'Pendente',
            'color' => 'warning',
            'icon' => '/assets/icons/credits/pending.png'
        ]);

        CreditStatus::create([
            'name' => 'Cancelado',
            'color' => 'danger',
            'icon' => '/assets/icons/credits/cancelled.png'
        ]);
    }
}
