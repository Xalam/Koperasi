<?php

namespace Database\Seeders;

use App\Models\Simpan_Pinjam\Master\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        User::create([
            'name'           => 'Admin',
            'username'       => 'admin',
            'password'       => Hash::make('primkopsmg123'),
            'role'           => 'admin',
            'remember_token' => Str::random(60),
        ]);
    }
}
