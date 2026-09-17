<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'solana.azcurra@hotmail.com', // ⚠️ Vamos a usar un .env
            'password' => Hash::make('ecofinder123'), // ⚠️ Vamos a usar un .env
            'role' => 'super_admin',
            'user_type' => 'super_admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}