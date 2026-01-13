<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    // attendance table doesn't have timestamps in migration
    public $timestamps = false;

    protected $fillable = [
        'date',
        'class_id',
        'section_id',
    ];

    public function details()
    {
        return $this->hasMany(\App\Models\AttendanceDetail::class, 'attendance_id');
    }

    public function class()
    {
        return $this->belongsTo(\App\Models\ClassModel::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(\App\Models\Section::class, 'section_id');
    }
}
