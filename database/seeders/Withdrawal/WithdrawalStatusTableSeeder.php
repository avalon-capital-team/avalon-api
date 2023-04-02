<?php

namespace Database\Seeders\Withdrawal;

use App\Models\Withdrawal\WithdrawalStatus;
use Illuminate\Database\Seeder;

class WithdrawalStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WithdrawalStatus::create([
            'name' => 'Cancelado',
            'color' => 'danger',
            'icon' => '/assets/icons/withdrawals/cancelled.png'
        ]);

        WithdrawalStatus::create([
            'name' => 'Aprovado',
            'color' => 'success',
            'icon' => '/assets/icons/withdrawals/approved.png'
        ]);

        WithdrawalStatus::create([
            'name' => 'Pendente',
            'color' => 'warning',
            'icon' => '/assets/icons/withdrawals/pending.png'
        ]);

        WithdrawalStatus::create([
            'name' => 'Processando',
            'color' => 'info',
            'icon' => '/assets/icons/withdrawals/processing.png'
        ]);
    }
}
