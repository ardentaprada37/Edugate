<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [
            // Grade 10 PPLG
            ['name' => 'Ahmad Rizki', 'student_number' => 'STD001', 'class_id' => 1, 'gender' => 'Male', 'phone' => '081234567890', 'parent_phone' => '081234567891', 'is_active' => true],
            ['name' => 'Siti Nurhaliza', 'student_number' => 'STD002', 'class_id' => 1, 'gender' => 'Female', 'phone' => '081234567892', 'parent_phone' => '081234567893', 'is_active' => true],
            ['name' => 'Budi Santoso', 'student_number' => 'STD003', 'class_id' => 1, 'gender' => 'Male', 'phone' => '081234567894', 'parent_phone' => '081234567895', 'is_active' => true],
            ['name' => 'Dewi Lestari', 'student_number' => 'STD004', 'class_id' => 1, 'gender' => 'Female', 'phone' => '081234567896', 'parent_phone' => '081234567897', 'is_active' => true],
            ['name' => 'Eko Prasetyo', 'student_number' => 'STD005', 'class_id' => 1, 'gender' => 'Male', 'phone' => '081234567898', 'parent_phone' => '081234567899', 'is_active' => true],
            
            // Grade 11 PPLG
            ['name' => 'Fani Wijaya', 'student_number' => 'STD006', 'class_id' => 2, 'gender' => 'Female', 'phone' => '081234567900', 'parent_phone' => '081234567901', 'is_active' => true],
            ['name' => 'Gilang Ramadhan', 'student_number' => 'STD007', 'class_id' => 2, 'gender' => 'Male', 'phone' => '081234567902', 'parent_phone' => '081234567903', 'is_active' => true],
            ['name' => 'Hana Safitri', 'student_number' => 'STD008', 'class_id' => 2, 'gender' => 'Female', 'phone' => '081234567904', 'parent_phone' => '081234567905', 'is_active' => true],
            
            // Grade 12 PPLG
            ['name' => 'Indra Gunawan', 'student_number' => 'STD009', 'class_id' => 3, 'gender' => 'Male', 'phone' => '081234567906', 'parent_phone' => '081234567907', 'is_active' => true],
            ['name' => 'Jasmine Putri', 'student_number' => 'STD010', 'class_id' => 3, 'gender' => 'Female', 'phone' => '081234567908', 'parent_phone' => '081234567909', 'is_active' => true],
            
            // Grade 10 DKV
            ['name' => 'Kevin Anggara', 'student_number' => 'STD011', 'class_id' => 4, 'gender' => 'Male', 'phone' => '081234567910', 'parent_phone' => '081234567911', 'is_active' => true],
            ['name' => 'Lisa Amelia', 'student_number' => 'STD012', 'class_id' => 4, 'gender' => 'Female', 'phone' => '081234567912', 'parent_phone' => '081234567913', 'is_active' => true],
            ['name' => 'Malik Hakim', 'student_number' => 'STD013', 'class_id' => 4, 'gender' => 'Male', 'phone' => '081234567914', 'parent_phone' => '081234567915', 'is_active' => true],
            
            // Grade 11 DKV
            ['name' => 'Nina Kartika', 'student_number' => 'STD014', 'class_id' => 5, 'gender' => 'Female', 'phone' => '081234567916', 'parent_phone' => '081234567917', 'is_active' => true],
            ['name' => 'Omar Faruq', 'student_number' => 'STD015', 'class_id' => 5, 'gender' => 'Male', 'phone' => '081234567918', 'parent_phone' => '081234567919', 'is_active' => true],
            
            // Grade 12 DKV
            ['name' => 'Putri Anggraini', 'student_number' => 'STD016', 'class_id' => 6, 'gender' => 'Female', 'phone' => '081234567920', 'parent_phone' => '081234567921', 'is_active' => true],
            ['name' => 'Qori Rahman', 'student_number' => 'STD017', 'class_id' => 6, 'gender' => 'Male', 'phone' => '081234567922', 'parent_phone' => '081234567923', 'is_active' => true],
        ];

        foreach ($students as $student) {
            \App\Models\Student::updateOrCreate(
                ['student_number' => $student['student_number']],
                $student
            );
        }
    }
}
