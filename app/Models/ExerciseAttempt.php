<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExerciseAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'exercise_id',
        'attempt_number',
        'start_time',
        'end_time',
        'attempt_status',
    ];

    protected $casts = [
        'attempt_number' => 'integer',
        'start_time'     => 'datetime',
        'end_time'       => 'datetime',
        'attempt_status' => 'string',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }

    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class);
    }

    public function result()
    {
        return $this->hasOne(ExerciseResult::class);
    }
}
