<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'midtrans_order_id',
        'transaction_id',
        'payment_type',
        'connection_status',
        'gross_amount',
        'paid_at',
        'raw_response',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'paid_at'      => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function revenueShares()
    {
        return $this->hasMany(RevenueShare::class);
    }
}
