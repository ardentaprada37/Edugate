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
        $classes = \App\Models\SchoolClass::query()
            ->active()
            ->orderBy('id')
            ->get();

        foreach ($classes as $class) {
            $email = 'walas.class' . $class->id . '@school.com';

            \App\Models\User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => 'Walas ' . $class->name,
                    'password' => \Illuminate\Support\Facades\Hash::make('walas12345'),
                    'role' => 'walas',
                    'assigned_class_id' => $class->id,
                ]
            );
        }
    }
}
