<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'qualification'
    ];

    /**
     * Get the user associated with the teacher
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
        // Disable timestamps since the table doesn't have them
    public $timestamps = false;

    /**
     * Get the user associated with the teacher
     */
}