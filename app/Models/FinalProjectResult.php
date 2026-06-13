<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalProjectResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'final_project_id',
        'enrollment_id',
        'final_project_score',
        'submission_file',
    ];

    protected $casts = [
        'final_project_score' => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function finalProject()
    {
        return $this->belongsTo(FinalProject::class);
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }
}
