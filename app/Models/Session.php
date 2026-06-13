<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'sessions_title',
        'sessions_description',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'sessions_id');
    }

    public function exercises()
    {
        return $this->hasMany(Exercise::class, 'sessions_id');
    }

    public function exercise()
    {
        return $this->hasOne(Exercise::class, 'sessions_id');
    }

    public function finalProjects()
    {
        return $this->hasMany(FinalProject::class, 'sessions_id');
    }
}