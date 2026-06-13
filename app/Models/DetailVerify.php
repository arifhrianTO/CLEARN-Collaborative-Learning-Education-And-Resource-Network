<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailVerify extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'mentor_id',
        'action',
        'mentor_rejection_reason',
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

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }
}
