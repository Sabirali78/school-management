<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'user_id',
        'class_id',
        'section_id',
        'dob',
        'roll_number',
        'phone',
        'address',
    ];
    /**
     * Student belongs to a user (auth record)
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Student belongs to a class
     */
    public function class()
    {
        return $this->belongsTo(\App\Models\ClassModel::class, 'class_id');
    }

    /**
     * Student belongs to a section
     */
    public function section()
    {
        return $this->belongsTo(\App\Models\Section::class, 'section_id');
    }
}
