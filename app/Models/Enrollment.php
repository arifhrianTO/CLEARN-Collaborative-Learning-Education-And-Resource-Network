<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'progress',
        'start_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'progress'   => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function rate()
    {
        return $this->hasOne(Rate::class);
    }

    public function exerciseAttempts()
    {
        return $this->hasMany(ExerciseAttempt::class);
    }

    public function exerciseResults()
    {
        return $this->hasMany(ExerciseResult::class);
    }

    public function finalProjectResults()
    {
        return $this->hasMany(FinalProjectResult::class);
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }
}
