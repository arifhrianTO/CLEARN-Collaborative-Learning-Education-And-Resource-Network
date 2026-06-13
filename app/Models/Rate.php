<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'enrollment_id',
        'course_comment',
        'course_rate',
    ];

    protected $casts = [
        'course_rate' => 'decimal:2',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }
}
