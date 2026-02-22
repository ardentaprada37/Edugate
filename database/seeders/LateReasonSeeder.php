<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LateReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reasons = [
            ['reason' => 'Woke up late', 'description' => 'Student overslept', 'is_active' => true],
            ['reason' => 'Transportation issue', 'description' => 'Problems with transportation', 'is_active' => true],
            ['reason' => 'Heavy rain', 'description' => 'Weather-related delays', 'is_active' => true],
            ['reason' => 'Discipline issue', 'description' => 'Behavioral problems', 'is_active' => true],
            ['reason' => 'Other', 'description' => 'Other reasons', 'is_active' => true],
        ];

        foreach ($reasons as $reason) {
            \App\Models\LateReason::updateOrCreate(
                ['reason' => $reason['reason']],
                $reason
            );
        }
    }
}
