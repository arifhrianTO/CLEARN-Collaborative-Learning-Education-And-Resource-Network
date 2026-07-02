<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;

class LandingController extends Controller
{
    public function index()
    {
        $courses = Course::where('status_publish', 'published')
            ->with('enrollments')
            ->latest()
            ->take(6)
            ->get();

        $heroCategories = Category::pluck('category_name')->toArray();
        $studentCount = \App\Models\User::where('role', 'student')->count();
        $courseCount = Course::where('status_publish', 'published')->count();
        $mentorCount = \App\Models\User::where('role', 'mentor')->count();

        $mentors = User::where('role', 'mentor')
            ->withCount(['courses' => function ($query) {
                $query->where('status_publish', 'published');
            }])
            ->with(['courses.enrollments'])
            ->take(4)
            ->get()
            ->map(function ($mentor) {
                $mentor->student_count = $mentor->courses->sum(function ($course) {
                    return $course->enrollments->count();
                });
                return $mentor;
            });


        return view('landing.index', compact('courses', 'heroCategories', 'studentCount', 'courseCount', 'mentorCount', 'mentors'));
    }

    public function course()
    {
        $courses = Course::where('status_publish', 'published')
            ->with('enrollments')
            ->latest()
            ->get();

        return view('landing.course', compact('courses'));
    }

    public function category()
    {
        $categories = Category::all();
        return view('landing.category', compact('categories'));
    }

    public function mentor()
    {
        $mentors = User::where('role', 'mentor')
            ->whereIn('status', ['active', 'verified', 'pending']) // Mengizinkan status lain untuk sementara
            ->with(['profileAccount'])
            ->withCount(['courses' => function ($query) {
                $query->where('status_publish', 'published');
            }])
            ->with(['courses.enrollments'])
            ->get()
            ->map(function ($mentor) {
                $mentor->student_count = $mentor->courses->sum(function ($course) {
                    return $course->enrollments->count();
                });
                return $mentor;
            });

        $totalMentors = $mentors->count();
        $totalStudents = $mentors->sum('student_count');
        
        // Asumsi rata-rata rating (bisa disesuaikan nanti dengan tabel review jika ada)
        $averageRating = 4.8; 

        return view('landing.mentor', compact('mentors', 'totalMentors', 'totalStudents', 'averageRating'));
    }
}
