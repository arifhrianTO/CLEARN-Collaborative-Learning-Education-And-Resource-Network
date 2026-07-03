<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailVerify;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class MentorVerificationController extends Controller
{
    /**
     * List mentor pending
     */
    public function index(): View
    {
        $pendingMentors = User::with(['profileAccount', 'verifyRecord'])
            ->where('role', 'mentor')
            ->where('status', 'pending')
            ->latest()
            ->paginate(3);

        return view('admin.verify.mentors', compact('pendingMentors'));
    }

    /**
     * Approve mentor
     */
    public function approve(int|string $mentorId): RedirectResponse
    {
        $admin = Auth::user();

        if (!$admin || $admin->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat menyetujui pengajar.');
        }

        DB::transaction(function () use ($mentorId, $admin) {
            $mentor = User::where('role', 'mentor')->findOrFail($mentorId);

            $mentor->update([
                'status' => 'active',
            ]);

            DetailVerify::updateOrCreate(
                ['mentor_id' => $mentor->id],
                [
                    'admin_id' => $admin->id,
                    'action' => 'approved',
                    'mentor_rejection_reason' => null,
                    'verify_at' => now(),
                ]
            );
        });

        return back()->with('success', 'Pengajar berhasil disetujui.');
    }

    /**
     * Reject mentor
     */
    public function reject(Request $request, int|string $mentorId): RedirectResponse
    {
        $admin = Auth::user();

        if (!$admin || $admin->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat menolak pengajar.');
        }

        // VALIDASI LANGSUNG DI CONTROLLER
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ], [
            'reason.required' => 'Alasan penolakan wajib diisi.',
            'reason.string'   => 'Alasan penolakan harus berupa teks.',
            'reason.max'      => 'Alasan penolakan maksimal 1000 karakter.',
        ]);

        DB::transaction(function () use ($mentorId, $admin, $validated) {
            $mentor = User::where('role', 'mentor')->findOrFail($mentorId);

            $mentor->update([
                'status' => 'rejected',
            ]);

            DetailVerify::updateOrCreate(
                ['mentor_id' => $mentor->id],
                [
                    'admin_id' => $admin->id,
                    'action' => 'rejected',
                    'mentor_rejection_reason' => $validated['reason'],
                    'verify_at' => now(),
                ]
            );
        });

        return back()->with('success', 'Pengajar berhasil ditolak.');
    }
}
