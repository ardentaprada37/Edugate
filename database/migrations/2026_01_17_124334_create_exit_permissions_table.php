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
        Schema::create('exit_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('submitted_by')->constrained('users')->onDelete('cascade');
            $table->date('exit_date');
            $table->time('exit_time')->nullable();
            $table->text('reason');
            $table->text('additional_notes')->nullable();
            
            // Walas approval
            $table->enum('walas_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('walas_approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('walas_approved_at')->nullable();
            $table->text('walas_notes')->nullable();
            
            // Admin approval
            $table->enum('admin_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('admin_approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('admin_approved_at')->nullable();
            $table->text('admin_notes')->nullable();
            
            // Overall status (approved only if both walas and admin approve)
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exit_permissions');
    }
};
