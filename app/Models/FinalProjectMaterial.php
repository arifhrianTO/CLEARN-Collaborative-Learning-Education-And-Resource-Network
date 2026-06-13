<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalProjectMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'final_project_id',
        'type',
        'file_path',
        'url',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function finalProject()
    {
        return $this->belongsTo(FinalProject::class);
    }
}
