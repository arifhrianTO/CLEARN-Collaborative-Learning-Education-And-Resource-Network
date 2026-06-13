<?php

namespace App\Services\Admin;

use App\Models\DetailVerify;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MentorVerificationService
{
    public function getPendingMentors(int $perPage = 10)
    {
        return User::with(['profileAccount', 'verifyRecord'])
            ->where('role', 'mentor')
            ->where('status', 'pending')
            ->latest()
            ->paginate($perPage);
    }

    public function approve(int|string $mentorId): User
    {
        return DB::transaction(function () use ($mentorId) {
            $admin = auth()->user();

            if (!$admin || $admin->role !== 'admin') {
                abort(403, 'Hanya admin yang dapat menyetujui mentor.');
            }

            $mentor = User::where('role', 'mentor')->findOrFail($mentorId);

            $mentor->update([
                'status' => 'active',
            ]);

            DetailVerify::updateOrCreate(
                [
                    'mentor_id' => $mentor->id,
                ],
                [
                    'admin_id' => $admin->id,
                    'action' => 'approved',
                    'mentor_rejection_reason' => null,
                    'verify_at' => now(),
                ]
            );

            return $mentor;
        });
    }

    public function reject(int|string $mentorId, string $reason): User
    {
        return DB::transaction(function () use ($mentorId, $reason) {
            $admin = auth()->user();

            if (!$admin || $admin->role !== 'admin') {
                abort(403, 'Hanya admin yang dapat menolak mentor.');
            }

            $mentor = User::where('role', 'mentor')->findOrFail($mentorId);

            $mentor->update([
                'status' => 'rejected',
            ]);

            DetailVerify::updateOrCreate(
                [
                    'mentor_id' => $mentor->id,
                ],
                [
                    'admin_id' => $admin->id,
                    'action' => 'rejected',
                    'mentor_rejection_reason' => $reason,
                    'verify_at' => now(),
                ]
            );

            return $mentor;
        });
    }
}
