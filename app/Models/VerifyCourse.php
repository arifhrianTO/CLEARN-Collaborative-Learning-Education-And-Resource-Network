<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifyCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'course_id',
        'action',
        'course_rejection_reason',
        'verify_at',
    ];

    protected $casts = [
        'action'    => 'string',
        'verify_at' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
