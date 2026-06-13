<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RejectMentorRequest;
use App\Services\Admin\MentorVerificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MentorVerificationController extends Controller
{
    public function __construct(
        private readonly MentorVerificationService $mentorVerificationService
    ) {}

    public function index(): View
    {
        $pendingMentors = $this->mentorVerificationService->getPendingMentors();

        return view('admin.verify.mentors', compact('pendingMentors'));
    }

    public function approve(int|string $id): RedirectResponse
    {
        $this->mentorVerificationService->approve($id);

        return back()->with('success', 'Mentor berhasil disetujui.');
    }

    public function reject(RejectMentorRequest $request, int|string $id): RedirectResponse
    {
        $this->mentorVerificationService->reject(
            $id,
            $request->validated('reason')
        );

        return back()->with('success', 'Mentor berhasil ditolak.');
    }
}
