<?php

namespace App\Services\Mentor;

use App\Models\User;
use Illuminate\Support\Collection;

class StudentService
{
    public function getStudentData(int $mentorUserId, array $filters = []): array
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
