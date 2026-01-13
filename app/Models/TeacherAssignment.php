<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherAssignment extends Model
{
    protected $fillable = ['teacher_id','class_id','section_id'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
}
