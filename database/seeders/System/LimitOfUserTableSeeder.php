<?php

namespace Database\Seeders\System;

use Illuminate\Database\Seeder;
use App\Models\System\Rules\LimitOfUser;

class LimitOfUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LimitOfUser::create([
            'deposit_fiat_user_not_validated' => 1000,
            'withdrawal_fiat_user_not_validated' => 0,
            'deposit_fiat_user_validated' => 100000,
            'withdrawal_fiat_user_validated' => 100000,
        ]);
    }
}
