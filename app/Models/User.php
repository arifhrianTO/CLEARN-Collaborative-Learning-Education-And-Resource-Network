<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'phone',
        'username',
        'profile_picture',
        'role',
        'status',
        'occupation',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'role'              => 'string',
        'status'            => 'string',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function banks()
    {
        return $this->hasOne(Bank::class);
    }

    /** Profil akun (mentor/student) */
    public function profileAccount()
    {
        return $this->hasOne(ProfileAccount::class);
    }

    /** Kategori yang dibuat oleh admin ini */
    public function categories()
    {
        return $this->hasMany(Category::class, 'admin_id');
    }

    /** Kursus yang dibuat sebagai mentor */
    public function courses()
    {
        return $this->hasMany(Course::class, 'mentor_id');
    }

    /** Enrollment sebagai student */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    /** Verifikasi mentor yang dilakukan admin (sebagai admin) */
    public function verifiedMentors()
    {
        return $this->hasMany(DetailVerify::class, 'admin_id');
    }

    /** Verifikasi mentor yang diterima (sebagai mentor) */
    public function verifyRecord()
    {
        return $this->hasOne(DetailVerify::class, 'mentor_id');
    }

    /** Revenue share yang diterima user */
    public function revenueShares()
    {
        return $this->hasMany(RevenueShare::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeRole($query, string $role)
    {
        return $query->where('role', $role);
    }
}
