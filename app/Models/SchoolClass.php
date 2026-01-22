<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $table = 'classes';

    protected $fillable = [
        'name',
        'grade',
        'major',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function lateAttendances()
    {
        return $this->hasMany(LateAttendance::class, 'class_id');
    }

    public function exitPermissions()
    {
        return $this->hasMany(ExitPermission::class, 'class_id');
    }

    public function homeroomTeacher()
    {
        return $this->hasOne(User::class, 'assigned_class_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
