<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'exercise_attempt_id',
        'question_id',
        'option_id',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function exerciseAttempt()
    {
        return $this->belongsTo(ExerciseAttempt::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}
