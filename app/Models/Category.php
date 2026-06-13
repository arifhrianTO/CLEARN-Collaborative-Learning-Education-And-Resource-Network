<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'category_name',
        'category_icon',
        'category_description',
        'category_color',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
