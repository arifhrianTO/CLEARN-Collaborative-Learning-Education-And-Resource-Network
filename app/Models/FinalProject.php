<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'sessions_id',
        'project_title',
        'project_description',
        'start_date',
        'due_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'due_date'   => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function session()
    {
        return $this->belongsTo(Session::class, 'sessions_id');
    }

    public function materials()
    {
        return $this->hasMany(FinalProjectMaterial::class);
    }

    public function results()
    {
        return $this->hasMany(FinalProjectResult::class);
    }
}
