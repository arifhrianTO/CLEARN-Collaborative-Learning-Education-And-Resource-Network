<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Session;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function editByCourse(Course $course)
    {
        if ($course->mentor_id !== auth()->id()) {
            abort(403);
        }

        $course->load([
            'sessions.lessons',
            'sessions.exercises',
            'sessions.finalProjects',
        ]);

        return view('mentor.sessions.edit-by-course', compact('course'));
    }

    public function updateByCourse(Request $request, Course $course)
    {
        if ($course->mentor_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'sessions' => 'required|array',
            'sessions.*.id' => 'required|exists:sessions,id',
            'sessions.*.sessions_title' => 'required|string|max:255',
            'sessions.*.sessions_description' => 'nullable|string',
        ]);

        foreach ($request->sessions as $sessionData) {
            $session = Session::where('id', $sessionData['id'])
                ->where('course_id', $course->id)
                ->first();

            if ($session) {
                $session->update([
                    'sessions_title' => $sessionData['sessions_title'],
                    'sessions_description' => $sessionData['sessions_description'] ?? null,
                ]);
            }
        }

        return redirect()
            ->route('mentor.courses.show', $course->id)
            ->with('success', 'Semua session berhasil diperbarui.');
    }
}
