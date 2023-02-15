<?php

namespace Database\Seeders\User;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Admin Seeder */
        $admin = User::create([
            'type' => 'admin',
            'username' => 'admin',
            'name' => 'Admin Avalon',
            'email' => 'admin@avalon.dev',
            'password' => Hash::make('admin'),
            'email_verified_at' => date('Y-m-d H:i:s')
        ]);

        /* Client Seeder */
        $client = User::create([
            'type' => 'user',
            'username' => 'user',
            'name' => 'Usuário Avalon',
            'email' => 'user@avalon.dev',
            'password' => Hash::make('user'),
            'email_verified_at' => date('Y-m-d H:i:s')
        ]);
    }
}
