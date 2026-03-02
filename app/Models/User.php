<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'assigned_class_id',
        'whatsapp_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function assignedClass()
    {
        return $this->belongsTo(SchoolClass::class, 'assigned_class_id');
    }

    public function recordedAttendances()
    {
        return $this->hasMany(LateAttendance::class, 'recorded_by');
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isTeacher()
    {
        return $this->role === 'teacher';
    }

    public function isHomeroomTeacher()
    {
        return $this->role === 'homeroom_teacher';
    }

    public function isWalas()
    {
        return $this->role === 'walas';
    }

    public function isClassScopedRole()
    {
        return in_array($this->role, ['homeroom_teacher', 'walas'], true);
    }

    public function hasAssignedClass()
    {
        return !is_null($this->assigned_class_id);
    }
}
