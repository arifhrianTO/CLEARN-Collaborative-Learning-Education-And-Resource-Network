<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentor_id',
        'category_id',
        'course_title',
        'course_slug',
        'course_description',
        'course_thumbnail',
        'course_price',
        'status_publish',
        'status_review',
    ];

    protected $casts = [
        'course_price'   => 'decimal:2',
        'status_publish' => 'string',
        'status_review'  => 'string',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function verifications()
    {
        return $this->hasMany(VerifyCourse::class);
    }

    public function rates()
    {
        return $this->hasMany(Rate::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('status_publish', 'published');
    }

    public function scopeApproved($query)
    {
        return $query->where('status_review', 'approved');
    }
}
