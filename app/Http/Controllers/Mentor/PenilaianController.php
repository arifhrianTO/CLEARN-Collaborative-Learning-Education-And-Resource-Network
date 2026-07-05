<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\FinalProjectResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    public function index()
    {
        $mentorId = Auth::id();

        $courses = Course::where('mentor_id', $mentorId)
            ->whereHas('sessions', function ($q) {
                $q->whereHas('finalProjects', function ($q) {
                    $q->whereHas('results', function ($q) {
                        $q->whereNull('final_project_score');
                    });
                });
            })
            ->addSelect(['pending_count' => function ($q) {
                $q->selectRaw('COUNT(*)')
                    ->from('final_project_results')
                    ->join('final_projects', 'final_project_results.final_project_id', '=', 'final_projects.id')
                    ->join('sessions', 'final_projects.sessions_id', '=', 'sessions.id')
                    ->whereColumn('sessions.course_id', 'courses.id')
                    ->whereNull('final_project_score');
            }])
            ->addSelect(['total_submissions' => function ($q) {
                $q->selectRaw('COUNT(*)')
                    ->from('final_project_results')
                    ->join('final_projects', 'final_project_results.final_project_id', '=', 'final_projects.id')
                    ->join('sessions', 'final_projects.sessions_id', '=', 'sessions.id')
                    ->whereColumn('sessions.course_id', 'courses.id');
            }])
            ->get();

        return view('mentor.penilaian.index', compact('courses'));
    }

    public function course(Course $course)
    {
        $this->authorizeMentor($course);

        $results = FinalProjectResult::whereHas('finalProject.session', function ($q) use ($course) {
            $q->where('course_id', $course->id);
        })
            ->with(['finalProject', 'enrollment.student', 'enrollment.course'])
            ->orderByRaw('final_project_score IS NULL DESC, final_project_score ASC')
            ->latest()
            ->get();

        return view('mentor.penilaian.course', compact('course', 'results'));
    }

    public function show(FinalProjectResult $result)
    {
        $result->load(['finalProject', 'enrollment.student', 'enrollment.course.mentor']);
        $this->authorizeMentor($result->enrollment->course);

        return view('mentor.penilaian.show', compact('result'));
    }

    public function grade(Request $request, FinalProjectResult $result)
    {
        $result->load(['enrollment.course']);
        $this->authorizeMentor($result->enrollment->course);

        $data = $request->validate([
            'final_project_score' => ['required', 'integer', 'min:0', 'max:100'],
            'mentor_notes'        => ['nullable', 'string', 'max:1000'],
        ], [
            'final_project_score.required' => 'Skor wajib diisi.',
            'final_project_score.integer'  => 'Skor harus berupa angka.',
            'final_project_score.min'      => 'Skor minimal 0.',
            'final_project_score.max'      => 'Skor maksimal 100.',
            'mentor_notes.max'             => 'Catatan maksimal 1000 karakter.',
        ]);

        $result->update($data);

        return redirect()
            ->route('mentor.penilaian.course', $result->enrollment->course_id)
            ->with('success', 'Penilaian berhasil disimpan.');
    }

    private function authorizeMentor(Course $course): void
    {
        if ($course->mentor_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke kursus ini.');
        }
    }
}
