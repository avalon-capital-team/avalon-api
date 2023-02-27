<?php

namespace Database\Seeders\User;

use App\Models\User\UserComplianceStatus;
use Illuminate\Database\Seeder;

class UserComplianceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserComplianceStatus::create([
            'name' => 'Pendente',
            'color' => 'warning',
            'icon' => '/assets/icons/compliance/pending.png'
        ]);

        UserComplianceStatus::create([
            'name' => 'Aprovado',
            'color' => 'success',
            'icon' => '/assets/icons/compliance/success.png'
        ]);

        UserComplianceStatus::create([
            'name' => 'Rejeitado',
            'color' => 'danger',
            'icon' => '/assets/icons/compliance/rejected.png'
        ]);

        UserComplianceStatus::create([
            'name' => 'Processando',
            'color' => 'info',
            'icon' => '/assets/icons/compliance/pending.png'
        ]);
    }
}
