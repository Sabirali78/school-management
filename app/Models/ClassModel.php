<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    protected $table = 'classes';
    
    protected $fillable = ['name', 'level', 'description'];

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function sections()
    {
        return $this->hasMany(Section::class, 'class_id');
    }
}
