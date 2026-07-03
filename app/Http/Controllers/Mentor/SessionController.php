<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function editByCourse(Course $course)
    {
        if ($course->mentor_id !== Auth::id()) {
            abort(403);
        }

        $course->load([
            'sessions.lessons.materials',
            'sessions.exercises.questions',
            'sessions.finalProjects.materials',
        ]);

        return view('mentor.sessions.edit-by-course', compact('course'));
    }

    public function updateByCourse(Request $request, Course $course)
    {
        if ($course->mentor_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'sessions'                        => 'required|array',
            'sessions.*.id'                   => 'required|exists:sessions,id',
            'sessions.*.sessions_title'       => 'nullable|string|max:255',
            'sessions.*.sessions_description' => 'nullable|string',
        ]);

        foreach ($request->sessions as $sessionData) {
            $session = Session::where('id', $sessionData['id'])
                ->where('course_id', $course->id)
                ->first();

            if ($session && !empty($sessionData['sessions_title'])) {
                $session->update([
                    'sessions_title'       => $sessionData['sessions_title'],
                    'sessions_description' => $sessionData['sessions_description'] ?? null,
                ]);
            }
        }

        return redirect()
            ->route('mentor.courses.show', $course->id)
            ->with('success', 'Semua pertemuan berhasil diperbarui.');
    }
}