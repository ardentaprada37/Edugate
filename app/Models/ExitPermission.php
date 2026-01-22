<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExitPermission extends Model
{
    protected $fillable = [
        'student_id',
        'class_id',
        'submitted_by',
        'exit_date',
        'exit_time',
        'reason',
        'additional_notes',
        'walas_status',
        'walas_approved_by',
        'walas_approved_at',
        'walas_notes',
        'admin_status',
        'admin_approved_by',
        'admin_approved_at',
        'admin_notes',
        'status',
    ];

    protected $casts = [
        'exit_date' => 'date',
        'exit_time' => 'datetime',
        'walas_approved_at' => 'datetime',
        'admin_approved_at' => 'datetime',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function walasApprovedBy()
    {
        return $this->belongsTo(User::class, 'walas_approved_by');
    }

    public function adminApprovedBy()
    {
        return $this->belongsTo(User::class, 'admin_approved_by');
    }

    // Helper methods
    public function isFullyApproved()
    {
        return $this->walas_status === 'approved' && $this->admin_status === 'approved';
    }

    public function isRejected()
    {
        return $this->walas_status === 'rejected' || $this->admin_status === 'rejected';
    }

    public function isPending()
    {
        return $this->walas_status === 'pending' || $this->admin_status === 'pending';
    }

    // Update overall status based on walas and admin statuses
    public function updateOverallStatus()
    {
        if ($this->walas_status === 'rejected' || $this->admin_status === 'rejected') {
            $this->status = 'rejected';
        } elseif ($this->walas_status === 'approved' && $this->admin_status === 'approved') {
            $this->status = 'approved';
        } else {
            $this->status = 'pending';
        }
        $this->save();
    }

    // Scopes
    public function scopeForClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('exit_date', $date);
    }
}
