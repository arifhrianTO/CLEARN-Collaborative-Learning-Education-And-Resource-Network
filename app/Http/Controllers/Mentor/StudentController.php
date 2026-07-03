<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'mentor') {
            abort(403);
        }

        $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $filters = [
            'search' => $request->input('search'),
        ];

        $data = $this->getStudentData(Auth::id(), $filters);

        return view('mentor.student.index', $data);
    }

    private function getStudentData(int $mentorUserId, array $filters = []): array
    {
        $students = $this->getStudents($mentorUserId, $filters);

        return [
            'totalStudents' => $students->count(),
            'activeStudents' => $students->where('status', 'Aktif')->count(),
            'avgCourses' => $this->getAverageCourses($students),
            'students' => $students,
        ];
    }

    private function getStudents(int $mentorUserId, array $filters = []): Collection
    {
        $query = User::query()
            ->where('role', 'student')
            ->whereHas('enrollments.course', function ($query) use ($mentorUserId) {
                $query->where('mentor_id', $mentorUserId);
            })
            ->withCount([
                'enrollments as courses_count' => function ($query) use ($mentorUserId) {
                    $query->whereHas('course', function ($query) use ($mentorUserId) {
                        $query->where('mentor_id', $mentorUserId);
                    });
                }
            ])
            ->latest();

        if (!empty($filters['search'])) {
            $query->where(function ($query) use ($filters) {
                $query->where('name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('email', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->get()->map(function ($student) {
            return [
                'name' => $student->name,
                'email' => $student->email,
                'date' => optional($student->created_at)->format('d M Y'),
                'courses' => $student->courses_count ?? 0,
                'status' => $this->getStudentStatus($student),
            ];
        });
    }

    private function getStudentStatus(User $student): string
    {
        if (isset($student->status)) {
            return strtolower($student->status) === 'active' || strtolower($student->status) === 'aktif'
                ? 'Aktif'
                : 'Nonaktif';
        }

        return 'Aktif';
    }

    private function getAverageCourses(Collection $students): float
    {
        if ($students->count() === 0) {
            return 0;
        }

        return round($students->avg('courses'), 1);
    }
}
