<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            ['name' => 'Grade 10 PPLG', 'grade' => '10', 'major' => 'PPLG', 'description' => 'Software Engineering', 'is_active' => true],
            ['name' => 'Grade 11 PPLG', 'grade' => '11', 'major' => 'PPLG', 'description' => 'Software Engineering', 'is_active' => true],
            ['name' => 'Grade 12 PPLG', 'grade' => '12', 'major' => 'PPLG', 'description' => 'Software Engineering', 'is_active' => true],
            ['name' => 'Grade 10 DKV', 'grade' => '10', 'major' => 'DKV', 'description' => 'Visual Communication Design', 'is_active' => true],
            ['name' => 'Grade 11 DKV', 'grade' => '11', 'major' => 'DKV', 'description' => 'Visual Communication Design', 'is_active' => true],
            ['name' => 'Grade 12 DKV', 'grade' => '12', 'major' => 'DKV', 'description' => 'Visual Communication Design', 'is_active' => true],
        ];

        foreach ($classes as $class) {
            \App\Models\SchoolClass::updateOrCreate(
                ['name' => $class['name']],
                $class
            );
        }
    }
}
