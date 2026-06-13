<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'revenue_shares_id',
        'wallet_permissions',
        'source_id',
        'source_type',
        'source_amount',
        'amount',
    ];

    protected $casts = [
        'source_amount' => 'decimal:2',
        'amount'        => 'decimal:2',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function revenueShare()
    {
        return $this->belongsTo(RevenueShare::class, 'revenue_shares_id');
    }
}
