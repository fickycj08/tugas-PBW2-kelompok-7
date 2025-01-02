<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Tambahkan user dengan role 'kasir'
        User::create([
            'user_id' => 1, // Pastikan ID tidak konflik
            'name' => 'Admin Kasir',
            'email' => 'kasir@example.com',
            'password' => Hash::make('password'), // Ganti dengan password yang sesuai
            'role' => 'kasir',
        ]);

        // Tambahkan user dengan role 'user'
        User::create([
            'user_id' => 2, // Pastikan ID tidak konflik
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'), // Ganti dengan password yang sesuai
            'role' => 'user',
        ]);
    }
}
