<?php

namespace Database\Seeders\Data;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Data\DataPlan;

class DataPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DataPlan::create([
            'name' => 'Silver',
            'rescue' => '180',
            'porcent' => '0.1',
        ]);

        DataPlan::create([
            'name' => 'Gold',
            'rescue' => '180',
            'porcent' => '0.1',
        ]);

        DataPlan::create([
            'name' => 'Black',
            'rescue' => '365',
            'porcent' => '0.1',
        ]);

        DataPlan::create([
            'name' => 'Infinity',
            'rescue' => '365',
            'porcent' => '0.1',
        ]);

        DataPlan::create([
            'name' => 'VIP',
            'rescue' => '365',
            'porcent' => '0.1',
            'type' => false,
        ]);
    }
}
