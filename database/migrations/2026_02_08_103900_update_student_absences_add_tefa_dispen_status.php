<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('student_absences', function (Blueprint $table) {
            // Update enum to include T (Tefa) and D (Dispen)
            $table->enum('status', ['S', 'I', 'A', 'T', 'D'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_absences', function (Blueprint $table) {
            // Rollback to original enum values
            $table->enum('status', ['S', 'I', 'A'])->change();
        });
    }
};
