<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExerciseResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'exercise_attempt_id',
        'enrollment_id',
        'exercise_result_score',
    ];

    protected $casts = [
        'exercise_result_score' => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function exerciseAttempt()
    {
        return $this->belongsTo(ExerciseAttempt::class);
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }
}
