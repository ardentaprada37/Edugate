<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@school.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'assigned_class_id' => null,
            ]
        );

        // Teacher users
        \App\Models\User::updateOrCreate(
            ['email' => 'teacher@school.com'],
            [
                'name' => 'Teacher John',
                'password' => bcrypt('password'),
                'role' => 'teacher',
                'assigned_class_id' => null,
            ]
        );

        // Homeroom teacher for Grade 10 PPLG
        \App\Models\User::updateOrCreate(
            ['email' => 'homeroom.pplg@school.com'],
            [
                'name' => 'Homeroom Teacher PPLG',
                'password' => bcrypt('password'),
                'role' => 'homeroom_teacher',
                'assigned_class_id' => 1, // Grade 10 PPLG
            ]
        );

        // Homeroom teacher for Grade 10 DKV
        \App\Models\User::updateOrCreate(
            ['email' => 'homeroom.dkv@school.com'],
            [
                'name' => 'Homeroom Teacher DKV',
                'password' => bcrypt('password'),
                'role' => 'homeroom_teacher',
                'assigned_class_id' => 4, // Grade 10 DKV
            ]
        );
    }
}
