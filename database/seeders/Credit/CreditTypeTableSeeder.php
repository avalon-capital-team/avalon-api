<?php

namespace Database\Seeders\Credit;

use App\Models\Credit\CreditType;
use Illuminate\Database\Seeder;

class CreditTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = array(
            array(
                'id'    => 1,
                'name'  => 'Depósito',
                'code' => 'deposit',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'id'    => 2,
                'name'  => 'Saque',
                'code' => 'withdrawal',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'id'    => 3,
                'name'  => 'Rendimento',
                'code' => 'rent',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'id'    => 4,
                'name'  => 'Rendimento de indicado',
                'code' => 'rent_sponsor',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'id'    => 5,
                'name'  => 'Aporte',
                'code' => 'placed',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'id'    => 6,
                'name'  => 'COnversão',
                'code' => 'convert',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            )
        );

        CreditType::insert($status);
    }
}
