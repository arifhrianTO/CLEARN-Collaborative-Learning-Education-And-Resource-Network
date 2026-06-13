<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'sessions_id',
        'lessons_title',
        'lessons_description',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function session()
    {
        return $this->belongsTo(Session::class, 'sessions_id');
    }

    public function materials()
    {
        return $this->hasMany(LessonMaterial::class, 'lesson_id');
    }
}
