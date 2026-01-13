<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceDetail extends Model
{
    // table created without id/timestamps; treats records as simple rows
    public $timestamps = false;

    protected $fillable = [
        'attendance_id',
        'student_id',
        'status',
    ];

    public function attendance()
    {
        return $this->belongsTo(\App\Models\Attendance::class, 'attendance_id');
    }

    public function student()
    {
        return $this->belongsTo(\App\Models\Student::class, 'student_id');
    }
}
