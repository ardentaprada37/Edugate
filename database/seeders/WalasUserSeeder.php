<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WalasUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create shared Walas account - dedicated for exit permission approval only
        \App\Models\User::updateOrCreate(
            ['email' => 'walas@sekolah.com'],
            [
                'name' => 'Walas Shared Account',
                'password' => \Illuminate\Support\Facades\Hash::make('walas2024'),
                'role' => 'walas',
                'email_verified_at' => now(),
            ]
        );
    }
}
