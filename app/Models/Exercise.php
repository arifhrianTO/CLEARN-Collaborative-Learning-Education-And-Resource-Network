<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'sessions_id',
        'exercise_title',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function session()
    {
        return $this->belongsTo(Session::class, 'sessions_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function attempts()
    {
        return $this->hasMany(ExerciseAttempt::class);
    }
}
