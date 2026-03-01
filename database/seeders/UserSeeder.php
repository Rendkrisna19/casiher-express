<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@express.com'], // Kita pakai email sebagai 'username' login
            [
                'name' => 'Administrator',
                'password' => Hash::make('password123'), // Password default
            ]
        );

        // 2. Akun Kasir
        User::updateOrCreate(
            ['email' => 'kasir@express.com'],
            [
                'name' => 'Kasir Utama',
                'password' => Hash::make('password123'),
            ]
        );
    }
}