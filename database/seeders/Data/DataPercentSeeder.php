<?php

namespace Database\Seeders\Data;

use Illuminate\Database\Seeder;
use App\Models\Data\DataPercent;

class DataPercentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DataPercent::create([
            'name' => 'Gestor/Assessor',
            'tag' => 'sponsor',
            'porcent' => '0.1',
        ]);
    }
}
