<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevenueShare extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'user_id',
        'receiver_role',
        'percentage',
        'total_amount',
        'amount',
        'status',
    ];

    protected $casts = [
        'percentage'   => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount'       => 'decimal:2',
        'receiver_role'=> 'string',
        'status'       => 'string',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function walletTransaction()
    {
        return $this->hasOne(WalletTransaction::class, 'revenue_shares_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }
}
