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

    public function hasRole($role)
    {
        return isset($this->role) && strcasecmp($this->role, $role) === 0;
    }

    public function isAdmin()
    {
        return $this->hasRole('Admin');
    }

    public function isTeacher()
    {
        return $this->hasRole('Teacher');
    }

    public function isStudent()
    {
        return $this->hasRole('Student');
    }

    public function isParent()
    {
        return $this->hasRole('Parent');
    }

    // Add relationship for teacher profile
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    // Add relationship for student profile
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    // Add relationship for parent profile
    public function parent()
    {
        return $this->hasOne(ParentModel::class);
    }
}
