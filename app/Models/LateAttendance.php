<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LateAttendance extends Model
{
    protected $fillable = [
        'student_id',
        'class_id',
        'late_reason_id',
        'recorded_by',
        'late_date',
        'arrival_time',
        'notes',
        'status',
        'telegram_sent',
        'telegram_sent_at',
    ];

    protected $casts = [
        'late_date' => 'date',
        'arrival_time' => 'datetime',
        'telegram_sent' => 'boolean',
        'telegram_sent_at' => 'datetime',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function lateReason()
    {
        return $this->belongsTo(LateReason::class, 'late_reason_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('late_date', $date);
    }

    public function scopeByMonth($query, $month, $year)
    {
        return $query->whereMonth('late_date', $month)
                     ->whereYear('late_date', $year);
    }

    public function scopeByClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeExcused($query, $date = null)
    {
        $date = $date ?: today();
        return $query->whereHas('student', function($q) use ($date) {
            $q->whereHas('exitPermissions', function($exitQ) use ($date) {
                $exitQ->whereDate('exit_date', $date)
                      ->where('status', 'approved');
            });
        });
    }

    // Helper methods
    public function getMinutesLate()
    {
        $arrivalTime = \Carbon\Carbon::parse($this->arrival_time);
        $schoolStart = \Carbon\Carbon::parse($this->late_date)->setTime(7, 0, 0);
        return $schoolStart->diffInMinutes($arrivalTime);
    }

    public function isExcused()
    {
        return $this->student->hasApprovedExitPermission($this->late_date);
    }

    public function getLateSeverity()
    {
        $minutesLate = $this->getMinutesLate();
        
        if ($minutesLate > 30) {
            return 'severe'; // Red badge
        } elseif ($minutesLate > 15) {
            return 'moderate'; // Yellow badge
        } else {
            return 'mild'; // Light yellow badge
        }
    }
}
