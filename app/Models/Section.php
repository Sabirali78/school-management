<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
     protected $fillable = ['name', 'class_id', 'capacity'];

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'section_id');
    }
}
