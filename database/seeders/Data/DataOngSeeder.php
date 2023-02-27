<?php

namespace Database\Seeders\Data;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Data\DataOng;

class DataOngSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DataOng::create([
            'name' => 'Greenpeace',
            'image_url' => 'https://avalon-space.sfo3.digitaloceanspaces.com/ongs%2Fgreenpeace.png',
        ]);

        DataOng::create([
            'name' => 'Médicos sem fronteiras',
            'image_url' => 'https://avalon-space.sfo3.digitaloceanspaces.com/ongs%2Fmedicos_sem_fronteiras.png',
        ]);

        DataOng::create([
            'name' => 'Peta',
            'image_url' => 'https://avalon-space.sfo3.digitaloceanspaces.com/ongs%2Fpeta.png',
        ]);
    }
}
